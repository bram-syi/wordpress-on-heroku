package DBUtils;
use warnings;
use strict;

use DBI;

my $conf;

sub new {
    my ($proto, %opts) = @_;
    my ($class);
    my $self = {};

    $class = ref($proto) || $proto;

    bless ($self, $class);

    return $self;
}

# Parse config.txt. Assume it is in present directory, if unspecified.

sub config {
    my ($self, $dir) = @_;
    return $conf if $conf;  # Parse only once.

    $dir = '.' unless $dir;
    {
       local $/ = undef;
       open(CONF, "$dir/config.txt") or die "Unable to open file $dir/config.txt\n";
       my $info = <CONF>;        # Slurp file.


       while ($info =~ m/^\s*(\S+)\s*[:=]\s*(\S+)\s*$/mg) {
           my ($key, $val) = ($1, $2);
           next if ($key =~ m/^#/);    # skip comments
           if ($key !~ m/^[^\.]+\.[^\.]+$/) {   # At the moment, we don't allow chaining (eg: dev.host.port = 2345)
               close(CONF);
               die "Parsing error in config.txt. Property ($key) not prefixed by environment.\nCorrect syntax is: environment.property = value\nEg: dev.host = localhost\n";
           }
           my ($env, $property) = split /\./, $key;
           $conf->{$env}->{$property} = $val;
       }
       close(CONF);

       if (scalar keys %$conf <= 0) {
           die "No properties are set in config.txt. Syntax to set properties is: environment.property = value\nEg: stage.database = syi\n";
       }

       if (! exists($conf->{data}->{dir})) {
           $conf->{data}->{dir} = "./data";
       }
    }

    return $conf;
}

sub dbh {
    my ($self, $env) = @_;
    die "config() must be called before dbh()" if not $conf;
    return DBI->connect( "dbi:mysql:host=mysql.seeyourimpact.com:database=$conf->{$env}{database}",
        $conf->{$env}{user}, $conf->{$env}{pass}, { RaiseError => 1, PrintError => 0 } );
}

sub _usage {
    my ($self, %opts) = @_;
    my ($src_msg, $tgt_msg, $force_msg, $bkup_msg) = ( "", "", "", "", "" );
    my ($src, $tgt, $bkup, $force) = ( "", "", "", "", "" );

    if (exists($opts{src}) && $opts{src}) {
       $src = "--source=<source>";
    }
    if (exists($opts{tgt}) && $opts{tgt}) {
       $tgt = "--target=<target>";
    }
    if (exists($opts{force}) && $opts{force}) {
       $force = "[ --force ]";
    }
    if (exists($opts{bkup}) && $opts{bkup}) {
       $force = "--bkup=</path/to/backup/file>";
    }

    $src_msg = "<source> refers to the environment from which data is taken." if $src;
    $tgt_msg = "<target> refers to the environment to which data is written to." if $tgt;
    $force_msg = "--force makes the script run non-interactively" if $force;
    $bkup_msg = "--bkup refers to the absolute path of the database backup tar.gz file." if $bkup;

return <<"MSG";

Usage: $0 $src $tgt $bkup $force

$src_msg
$tgt_msg
$bkup_msg

New environments can be specified in config.txt.
Available environments: stage, dev

$force_msg

MSG
}

sub mysql_args {
    my $fmt = "-h '%s' -u '%s' -p'%s' --port '%d' ";
    die "mysql_args requires at least 1 argument" unless @_ > 0;
    if (@_ == 4) {
        return sprintf($fmt, @_);
    }
    else {
        my ($env) = @_;
        die "environment '$env' doesn't exist" if not exists $conf->{$env};
        return sprintf($fmt, map { $conf->{$env}{$_} } qw/host user pass port/);
    }
}

sub wp_config {
    my $path = '..';
    while (not -f "$path/wp-config.php") {
      $path .= "/..";
      die "couldn't find wp-config.php in parent paths" if length($path) > 40;
    }

    open my $fh, '<', "$path/wp-config.php" or die "couldn't read $path/wp-config.php";
    my %db;
    while (<$fh>) {
        if (m/define\(\s*['"]DB_(.*?)['"],\s*['"](.*?)['"]/) {
            if (grep {$1 eq $_} qw/NAME USER PASSWORD HOST/) {
                $db{lc($1)} = $2;
            }
        }
    }
    die "couldn't find all database parameters (name,user,password,host)"
        unless 4 == keys %db;
    return %db;
}

sub wordpress {
    if (@_) {
        return DBI->connect( "dbi:mysql:host=mysql.seeyourimpact.com:database=$_[0]",
            "syidb", "nischal1999", { RaiseError => 1, PrintError => 0 } );
    }

    my %db = wp_config();

    return DBI->connect("dbi:mysql:host=$db{host};database=$db{name}",
        $db{user}, $db{password}, {PrintError=>0, RaiseError=>1});
}

1;
