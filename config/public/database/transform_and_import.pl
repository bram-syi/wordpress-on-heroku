#!/usr/bin/perl -w
use strict;
use DBI;
use DBUtils;
use Getopt::Long;
$| = 1;

#
# Transform data after initial preparation phase is complete.
# and transfer them according to rules in xform2.txt ... Shanker.N
# Configuration information is obtained from config.txt.
#

# PLEASE MAKE SURE THAT THE LOCAL DATABASE HAS NO DATA IN IT.
# PLEASE RUN initdb.pl PRIOR TO RUNNING THIS SCRIPT.

#
# Steps:
#
# 1. Run prepare_load.pl on source database.
#    prepare_load.pl creates a data/ directory and exports data there.
# 2. Load the schema to create an empty, up-to-date database.
# 3. Then run this script to transform the data contained in the text
#    files in data/ directory and load it into the blank database.
#

my ( $config, $webconf, $tables, $munge ) = ( {}, {}, {} );
my $siteinfo = {};
my ( $line, $data, $data_dir, $tgt, $bkup );

my $result = GetOptions( "target|t=s" => \$tgt )
  or die "No tgt environment specified to $0";

my $utils = DBUtils->new;

die $utils->_usage( tgt => 1 ) if ( !$tgt );

$config = $utils->config();

# Basic checks

if ( !exists( $config->{$tgt} ) ) {
    die
"No configuration information available for target environment ($tgt) in config.txt";
}

foreach my $item (qw(database host port user pass)) {
    if (   !exists( $config->{$tgt}->{$item} )
        or !defined( $config->{$tgt}->{$item} ) )
    {
        die
"No information available for '$tgt.$item' in config.txt to connect to your local database";
    }
}

my $db = $config->{$tgt}{database} || die "no 'database' found in config for $tgt";
die "don't touch live" if $db =~ /_live$/i;

if ( exists( $config->{data}->{dir} ) ) {
    $data_dir = $config->{data}->{dir};
}
else {
    $data_dir = ".";
}

if ( !-d "${data_dir}" ) {
    die
"Expected data files to be in '$data_dir', but directory doesn't exist!\nSet data.dir in config.txt to point to location of data directory, then place a file named xform2.txt in that directory with the needed transformation rules in that directory. Check README.txt in database/ directory for more information about transformation rules";
}

# going to be an array of hashes:
#   { table => qr//, col => q//, xform => sub { } }
my @rules;

# Read file with transformation rules
if (open my $fh, '<', "./xform2.txt" ) {
    my $x = do { local $/; <$fh> };
    @rules = eval $x;
    if ($@) {
        die "error eval'ing xform2.txt: $@";
    }
}
else {
    die "couldn't open xform2.txt: $!";
}

# these are replacements from the config file
my $code = 'return unless defined $_[0];'."\n";
foreach (grep { /_/ } keys %{$config->{$tgt}}) {
    $code .= '$_[0] =~ s!__\['.quotemeta($_).']__!'.quotemeta($config->{$tgt}{$_}).'!igo;'."\n";
}
my $remap = eval 'sub { '.$code.'; return $_[0] };';
die $@ if $@;

# We assume that the target database can be cleaned and loaded from scratch.
# This script is not for importing data incrementally. There are separate
# scripts to load data incrementally and track version changes.

my $dsn =
    'dbi:mysql:'
  . $config->{$tgt}->{database}
  . ';host='
  . $config->{$tgt}->{host}
  . ';port='
  . $config->{$tgt}->{port};
my $user = $config->{$tgt}->{user};
my $pass = $config->{$tgt}->{pass};
my $dbh =
  DBI->connect( $dsn, $user, $pass, { 'RaiseError' => 1, 'AutoCommit' => 0 , PrintError=>0 } )
  or die "Unable to connect to database: $DBI::errstr";

# we keep track of the current insert, and all the values for it. Every
# X values, we force an insert. Or if the table changes, we flush the data
# for the table we have. Simple buffering.
my ($insert, @values, $buf) = ('', (), 0);

sub insert {
    my ($ins, $val) = @_;

    $buf += length($val) + 10;

    if ($ins eq $insert) {
        flush_inserts() if $buf >= 15_000_000;
    }
    else {
        flush_inserts();
        $insert = $ins;
    }

    push @values, $val;
}
sub flush_inserts {
    $insert =~ /^insert into (\S+)/i;
    if ($insert and @values) {
        print "Inserting into $1 ... ";
        my $sql = "$insert VALUES ".join(',', map { "( $_ )" } @values);
        $dbh->do($sql);
        print "wrote ".scalar(@values)." rows to table\n";
    }
    @values = ();
    $buf = 0;
}
foreach (`cd '$config->{data}{dir}' && ls -1`) {
    chomp;
    my $datafile = $config->{data}{dir} . "/$_";
    next unless -f $datafile;

    my ($table) =
      ( $datafile =~ m!^.*/(.*?)\.! );    # Datafile matches table name.
    my @sqls = ();

    next if ( $table =~ m/^(?:_schema|_wp-config)/ );
    next unless ( ( stat($datafile) )[7] );    # Ignore 0 byte sized files.

    # Check for any special transformation rules.
    my $xform2_rule = undef;

    foreach my $rule (@rules) {
        if ( $table =~ $rule->{'table'} ) {
            %$xform2_rule = %$rule;
        }
    }

    open( FH, $datafile ) or die $!;
    my $header;
    chomp( $header = <FH> );
    my @colheaders = split /,/,
      $header;    # First line always lists column headers.

    {
        local $/ = '@@@@';
        while ( defined( $line = <FH> ) ) {
            chomp($line);
            next unless $line;
            
            my @cols = split /\[\|\|\]/, $line, -1;
            pop(@cols);
            my $s1 = @colheaders;
            my $s2 = @cols;

            foreach (@cols) {
                $_ = $remap->($_);
            }

            if ( defined($xform2_rule) ) {    # Apply transformation
                for ( my $i = 0 ; $i < scalar(@cols) ; $i++ ) {
                    my $col = $colheaders[$i];
                    if ( exists( $xform2_rule->{$col} ) ) {
                        $_ = $cols[$i];
                        $xform2_rule->{xform}();
                        $cols[$i] = $_;
                    }
                }
            }

            my $diff;
            if ( $diff = ( scalar(@colheaders) - scalar(@cols) ) > 0 ) {

                # Hack for split(): It removes trailing spaces by default.
                for ( my $c = 1 ; $c <= $diff ; $c++ ) {
                    $cols[ $#cols + $c ] = 'NULL';
                }
            }

            my $sql = "";

            # One very special check. We'll generalize this into framework
            # if this sort of hack is needed again elsewhere. But, for now,
            # this will do.
            #
            if ( $table eq 'wp_users' ) {
                my %temp_map;
                @temp_map{@colheaders} = @cols;

                # Use default n*****1*** pass
                my $default_pass = '$P$BLzvudAu81pkhpCju2guClRKFmHZNy.';
                $temp_map{user_pass} =~ s/^.*$/$default_pass/
                  if ( $temp_map{"user_login"} eq 'admin' );

                # Clever trick. We determine correct insert order
                # by sorting on values twice.
                insert("INSERT INTO $table (" . join( ",", map  { '`' . $_ . '`' }
                    sort { $temp_map{$a} cmp $temp_map{$b} } keys %temp_map ) . ")",
                    join( ",", map { $dbh->quote($_) } sort values %temp_map )
                );
            }
            else {
                my @a = map { $dbh->quote($_) } @cols;
                $s2 = @a;
                insert("INSERT INTO $table (" . join(",", map { '`' . $_ . '`' } @colheaders ) . ")",
                    join( ", ", map { $dbh->quote($_) } @cols )
                );
            }

            if ( $s1 ne $s2 ) {
                print $line . "\r\n";
                print @cols . "\r\n";
                die( "Column mismatch: " . $s1 . "!=" . $s2 . "\r\n" . $sql );
            }
        }
    }

    close(FH);
}

flush_inserts();

# Now configure site
print "\nConfiguring site ... ";
open( FH, "<$config->{data}->{dir}/_wp-config.php" )
  or die "Expected file, _wp-config.php not found in $config->{data}->{dir}";
{
    local $/ = undef;
    $webconf = <FH>;
}
close(FH);

$webconf =~ s!('DB_NAME', ')\w+'\);!$1$config->{$tgt}->{database}'\);!;
$webconf =~ s!('DB_USER', ')\w+'\);!$1$config->{$tgt}->{user}'\);!;
$webconf =~ s!('DB_PASSWORD', ')\S+'\);!$1$config->{$tgt}->{pass}'\);!;
$webconf =~ s!('DB_HOST', ')\S+'\);!$1$config->{$tgt}->{host}'\);!;
$webconf =~ s!('ENVIRONMENT', ')\S+'\);!$1$tgt'\);!;

open( NEW, ">$config->{$tgt}->{doc_root}/wp-config.php" )
  or die "Unable to write to $config->{$tgt}->{doc_root}/wp-config.php";
print NEW $webconf;
close(NEW);

$SIG{INT} = sub { $dbh->disconnect; };

DESTROY {
    $dbh->disconnect;
}

