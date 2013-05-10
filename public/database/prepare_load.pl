#!/usr/bin/perl -w
use strict;
use DBI;
use DBUtils;
use Getopt::Long;
$| = 1;

#
# Load data from tables listed in impactdb_charity on mysql.smallgiving.org.
# and transfer them according to rules in xform.txt, generating a bunch of flat files. Shanker.N
#

my ( $config ) = ( {} );
my ( $line, $data, $src, $tgt, $force, $quick, $skip_schema_generate, $skip_data, $full_data );

my $result = GetOptions(
    "source|s=s" => \$src,
    "force|f"    => \$force,
    "skipschema" => \$skip_schema_generate,
    "skipdata"   => \$skip_data,
    "fulldata"   => \$full_data,
) or die "No source and target environment specified to $0";


my $utils = DBUtils->new;

die $utils->_usage( src => 1, force => 1 ) if ( !$src );

$config = $utils->config();

die "no data dir is defined" unless $config->{data}{dir};
system "rm -rf '$config->{data}{dir}'";
mkdir("$config->{data}->{dir}");
die "Unable to create data directory" unless -d $config->{data}{dir};

foreach my $item (qw(database host port user pass)) {
    if (   !exists( $config->{$src}->{$item} )
        or !defined( $config->{$src}->{$item} ) )
    {
        die
"No information available for '$src.$item' in config.txt to connect to your local database";
    }
}

my $src_db   = $config->{$src}->{database};
my $src_host = $config->{$src}->{host};
my $src_port = $config->{$src}->{port};
my $src_dsn  = "dbi:mysql:${src_db};host=${src_host};port=${src_port}";
my $src_user = $config->{$src}->{user};
my $src_pass = $config->{$src}->{pass};

my $dbh =
  DBI->connect( $src_dsn, $src_user, $src_pass,
    { 'RaiseError' => 1, 'AutoCommit' => 0 } )
  or die
"Unable to connect to $src_db database on $src_host and fetch database meta information: $DBI::errstr";

my $sth = $dbh->prepare(
    'SELECT table_name, column_name FROM information_schema.columns '.
    'WHERE table_schema = ?'
);
$sth->execute($src_db);

my %all_tables;
while (my $row = $sth->fetchrow_arrayref) {
    $all_tables{$row->[0]} //= [];
    push @{$all_tables{$row->[0]}}, $row->[1];
}

if (not $skip_schema_generate) {
    my $cmd = "mysqldump ";
    $cmd .= DBUtils::mysql_args( $src_host, $src_user, $src_pass, $src_port );
    $cmd .=
    " '$src_db' --no-data --create-options --no-create-db > '$config->{data}{dir}/_schema.sql'";
    print "dumping schema\n";
    system $cmd;
    die "schema dump failed" if $? != 0;
}

# 20120605 - some InnoDB table snuck into the live database, so we hack those out with a rusty chainsaw:
system
"perl -pi.bak -e 's/ENGINE=InnoDB/ENGINE=MyISAM/' '$config->{data}{dir}/_schema.sql'";
if ( $? == 0 ) {
    print "InnoDB tables have been changed to MyISAM\n";
}
else {
    die "InnoDB->MyISAM table change failed";
}

# going to be an array of hashes:
#   { table => qr//, col => sub { } [, col2 => sub { }, ... ] }
my @rules;

# Read file with transformation rules
if (open my $fh, '<', "./xform.txt" ) {
    my $x = do { local $/; <$fh> };
    @rules = eval "($x)";
    die "error eval'ing xform.txt: $@" if $@;
}
else {
    die "couldn't open xform.txt: $!";
}

# now we are going to pre-compile all the regex to transform the keys
# in the config hash
my $code = 'return unless defined $_[0];'."\n";
foreach (grep { /_/ } sort { length($config->{$src}{$b}) <=> length($config->{$src}{$a}) }  keys %{$config->{$src}}) {
    $code .= 'return $_[0] if $_[0] =~ s!'.quotemeta($config->{$src}{$_}).'!__['.quotemeta($_).']__!igo;'."\n";
}
my $remap = eval 'sub { '.$code.'return $_[0] };';
die $@ if $@;

my $bail = 0;
$SIG{INT} = sub {
    print $bail ? "aborting abort\n" : "aborting\n";
    $bail = not $bail;
};

while ( my ($table, $cols) = each %all_tables ) {
    last if $skip_data;

    if (not $full_data) {
      # by default, we skip useless tables
      next if $table eq 'cartDebug';
    }

    print "Exporting table $table ... ";

    if ($bail) {
        print "stopping dump\n";
        last;
    }

    open( OUT, ">$config->{data}->{dir}/$table.out.txt" )
      or die "Unable to dump data for table $table";

    # Check for any special transformation rules.
    my $xform_rule = undef;

    foreach my $rule (@rules) {
        if ( $table =~ m!$rule->{'table'}! ) {
            %$xform_rule = %$rule;
        }
    }

    print OUT join( ",", @$cols ),
      "\n";   # Header contains column names. '---' is an identification marker.

    my $count = 0;

    my $sth = $dbh->prepare("select * from $table");
    $sth->execute;
    while ( my $row = $sth->fetchrow_hashref ) {
        $count++;
        foreach my $col (@$cols) {
            $row->{$col} = $remap->($row->{$col});

            if ( defined($xform_rule) ) {    # Apply transformation
                if ( exists( $xform_rule->{$col} ) ) {
                    $_ = $row->{$col};
                    $xform_rule->{$col}();
                    $row->{$col} = $_;
                }
            }
            print OUT ( defined( $row->{$col} ) ? $row->{$col} : "" ) . "[||]";
        }
        print OUT "@@@@";
    }

    close(OUT);

    print "$count rows\n";
}

# Copy wordpress configuration file
print "\nBacking up wordpress configuration data ...\n";
system(
"cp $config->{$src}->{doc_root}/wp-config.php $config->{data}->{dir}/_wp-config.php"
);
die "Error backing up wp-config.php: $!" if ( $? != 0 );

if ($bail) {
    die "bailing out!";
}
else {
    print
"\nSUCCESS! Database $config->{$src}->{database} on $src environment backed up at $config->{data}->{dir}/\n";
}
