#!/usr/bin/perl -w
use strict;
use Getopt::Long;
use DBUtils;

my ( $newdb, $force, $tgt );
my $result = GetOptions(
    "force|f"    => \$force,
    "target|t=s" => \$tgt
) or die "Error parsing arguments: $!";

my $utils = DBUtils->new;

die $utils->_usage( force => 1, tgt => 1 )
  if ( !$tgt );

print <<MSG;
***
***IMPORTANT !!! ***
***
This script takes the data files given to you by your database administrator
and loads it into your local database.

Please change your directory to the 'database' directory in source control
before continuing with this script.

To configure settings for your local database, please edit config.txt in the 
database directory.  A sample config.txt is checked into source control.

To apply transformations to the data before it is imported to your local
database, edit xform2.txt. The format of this file should be self-evident.
A sample xform2.txt is checked into source control. 

Running this script will drop/create the database as specified in config.txt.
MSG

print "************\n";
print "Continue? [Y/N]";
my $input;

unless ($force) {
    chomp( $input = <STDIN> );
}
else {
    $input = 'Y';
    print "(forcing yes)\n";
}

if ( $input !~ m/Y|y|YES|yes|Yes/ ) {
    print "\nHave a good day.\n";
    exit 0;
}

if ( !-f "./transform_and_import.pl" ) {
    die
"Could not find script transform_and_import.pl in the current directory. Make sure you are cd'ed into the database/ directory";
}
 
my $config = $utils->config();
my $dbh = $utils->dbh($tgt);

mkdir( "$config->{data}->{dir}", 0755 );
die "Unable to create directory $config->{data}->{dir}"
  unless -d $config->{data}{dir};

my $db = $config->{$tgt}{database} || die "no database name in \$config->{$tgt}";
die "no touching live database!" if $db =~ /_live$/;

my $mysql_cmd = 'mysql ' . DBUtils::mysql_args($tgt);

my $cmd = "$mysql_cmd --skip-column-names '$db' | $mysql_cmd '$db'";
my $sql = "
SELECT concat('DROP TABLE IF EXISTS ', table_name, ';')
FROM information_schema.tables
WHERE table_schema = '$db'
    AND TABLE_NAME LIKE 'wp_%';
";

print "dropping all wp_* tables: $sql\n";
open my $ps, '|-', $cmd or die "couldn't run mysql: $!";
print $ps $sql;
close $ps or die "mysql didn't exit successfully";

$cmd = "$mysql_cmd '$db' < '$config->{data}{dir}/_schema.sql'";
print "loading schema: $cmd\n";
system $cmd;
die "mysql failed to load schema" if ( $? != 0 );

$cmd =
  "perl transform_and_import.pl  --target=$tgt";
print "running transform_and_import.pl: $cmd\n";
system $cmd;
die "transform_and_import.pl didn't exit 0" if ( $? != 0 );

# setup emailing
my %smtp = (
    'smtp_host' => 'smtp.gmail.com',
    'smtp_auth' => 'true',
    'smtp_user' => 'impact@seeyourimpact.org',
    'smtp_pass' => 'microcharity',
    'smtp_port' => '465',
    'smtp_ssl'  => 'ssl',
);

while (my ($k, $v) = each %smtp) {
    $dbh->do('update wp_1_options set option_value = ? where option_name = ?',
        undef, $v, $k);
}

print "\nSUCCESS! Data imported into your local database!\n";

rewrite_email_addresses();
facebook_settings();

exit 0;

sub usage {
    return <<"MSG";

Usage: $0 --target=<target> [ --force ]

New environments can be specified in config.txt.
Available environments: stage, dev

--force makes the script run non-interactively.

MSG

}

# this rewrites emails addresses found in wp_1_options which have become
# invalid (because devX.seeyourimpact.com have no valid mx records)
sub rewrite_email_addresses {
    my %env = (
        dev1 => 'steve',
        dev2 => 'alex',
        dev3 => 'alex',
    );

    my $alias = exists($env{$tgt}) ? $env{$tgt} : 'devs';

    my $sth = $dbh->prepare("select * from wp_1_options");
    $sth->execute;
    while (my $row = $sth->fetchrow_arrayref) {
        if ($row->[2] =~ /\S+\@\S+/ and $row->[2] !~ /[{}]/ and $row->[1] =~ /^(\S+)_email$/) {
            my $email = "$alias+$1\@seeyourimpact.org";
            print "rewriting wp_1_options $row->[1]: $row->[2] -> $email\n";
            $dbh->do('update wp_1_options set option_value = ? where option_id = ?',
                undef, $email, $row->[0]);
        }
    }

    # ugh just enough differences between wp_1_options and wp_sitemeta to make
    # too much of a pain to function-ize
    $sth = $dbh->prepare("select * from wp_sitemeta");
    $sth->execute;
    while (my $row = $sth->fetchrow_arrayref) {
        if ($row->[3] =~ /\S+\@\S+/ and $row->[3] !~ /[{}]/ and $row->[2] =~ /^(\S+)_email$/) {
            my $email = "$alias+$1\@seeyourimpact.org";
            print "rewriting wp_sitemeta $row->[2]: $row->[3] -> $email\n";
            $dbh->do('update wp_sitemeta set meta_value = ? where meta_id = ?',
                undef, $email, $row->[0]);
        }
    }
}

sub facebook_settings {
    my %env = (
        appid => '470745049637347',
        secret => '6cfe75b509de021ffe2b8fe189427d7d',
        namespace => 'syidevtwo',
    );

    my $sth = $dbh->prepare('replace wp_1_options(option_name, option_value) values (?,?)');
    while (my ($name, $value) = each %env) {
        print "assigning fb_$name to $value\n";
        $sth->execute("fb_$name", $value);
    }
}
