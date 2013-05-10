#!/usr/bin/perl -w
use strict;
use DBI;

#
# NOTE: This is the main database patch driver script.
# All incremental database patches are applied via this script.
#
# Individual DB patches should be added to the perl file db-patches, not here.
#
# DO NOT ATTEMPT TO RUN the script 'db-patches' by itself.
#

#
# 1. Connect to the database and see if you can connect successfully.
# 2. Get current version V of DB and set version to V.
# 3. All SQL updates with a version higher than V should get executed.
#    The updates should be transactional.  
# 4. After every DB update, increment the current version in database by 1.
#    This allows this script to be run any number of times.
# 
#

#
#=========================
# How to write a DB patch?
#=========================
#
# A DB patch is a single if { } block in the file db-patches.
# A sample commented patch is shown in db-patches.
#
# The utility functions, set_version() and get_current_version()
# may be used from within the patch. Please call set_version()
# at the end when all sql pertaining to the patch has been executed
# and it is time to update the database version. The special variable
# '$dbh' is available for use from within any database patch and
# it represents the database handle. No connection need be maded to the
# database.
#
# The patch author may assume that his/her patch is being run
# in a transaction. Calling set_version() commits the transaction.
#


sub connect {
    my ($dsn, $user, $pass) = @_;

    if (! defined($dsn) or 
        ! defined($user) or 
        ! defined($pass)) {
             die "DB connection information not available\n";
    }

    my $dbh;

    eval {
        $dbh = DBI->connect($dsn, $user, $pass);
    };

    die "\nUnable to connect to database: $@\n" if $@;

    $dbh->{AutoCommit} = 0; # enable transactions
    $dbh->{RaiseError} = 1; 

    return $dbh;
}

sub get_current_version {
    my ($dbh) = @_;
    my $sth;

    die "No database handle\n" unless defined $dbh;

    $sth = $dbh->prepare('SELECT version FROM VERSION');

    $sth->execute;

    return $sth->fetchrow_hashref->{'version'};
}

sub set_version {
    my ($dbh, $version) = @_;

    die "No database handle\n" unless defined $dbh;
    die "No version to set?\n" unless defined $version;

    #
    # We use localtime() instead of a standard date-time function such as ParseDate() 
    # in Date::Manip because we want to avoid having to install CPAN modules to be 
    # able to run this script.
    #
    # Also, datetimes are stored in local timezone. We don't worry about GMT and daylight 
    # time savings for now.

    my ($sec, $min, $hour, $mday, $mon, $year, $wday, $yday, $isdst) = localtime(time);

    my $date = sprintf("%4d-%02d-%02d %2d:%2d:%2d", (1900 + $year), ($mon + 1), $mday, $hour, $min, $sec);

    eval {
        $dbh->do("INSERT INTO VERSION_LOG ('version', 'date') VALUES ($version, $date)");
        $dbh->do("UPDATE VERSION ('current_version', 'date') VALUES ($version, $date)");
    }; 

    if ($@) {
        $dbh->rollback;
    } else {
        $dbh->commit;
    }
}

my ($line);
my $config = {};

open(FH, "< ./config.txt") or die "Unable to read config.txt: $!\n";
while (defined($line = <FH>)) {
   chomp($line);
   next if ($line =~ m/^\s+$/ or $line =~ m/^#/);
   my ($key, $val) = split /:\s+/, $line;
   $config->{$key} = $val;
}
close(FH);

my $dsn  = 'dbi:mysql:' . $config->{'database'} . ';host=' . $config->{'host'} . ';port=' . $config->{port};
my $user = $config->{'user'};
my $pass = $config->{'pass'};

no strict;

$dbh = &connect($dsn, $user, $pass);
# Make $dbh available to patches in db_patches.

do './db_patches'; # Executes all database patches in order!


