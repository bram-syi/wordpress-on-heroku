#!/usr/bin/env perl

use FindBin;
use lib $FindBin::Bin.'/../../database';
use DBUtils;
use Text::CSV;

# run a query against a database, return as CSV (really this should be built
# into the standard mysql client)

my $csv = Text::CSV->new({ always_quote=>1, eol=>"\n" });

if (not -t) {
    # assume a single query on stdin
    my $dbh = @ARGV ? DBUtils::wordpress($ARGV[0]) : DBUtils::wordpress();
    my $sql = do { local $/; <STDIN> };
    if (not $sql =~ /^\s*select/i) {
        die "non-select statements are not allowed";
    }
    my $sth = $dbh->prepare($sql);
    execute_and_output($sth, STDOUT);
}
else {
    # go look for a custom query outside of stdin
    my $dbh = DBUtils::wordpress('impactdb_dev2');
    my $temp_tables = do { local $/; `cat donor-charity.sql`; };
    for (split(/;/, $temp_tables)) {
        eval { $dbh->do($_) };
        die $@ if $@;
    }

    for my $category (qw/pratham education_success others/) {
        open my $fh, '>', "$category.csv" or die $!;
        my $sth = $dbh->prepare("select * from t_$category");
        execute_and_output($sth, $fh);
    }
}

sub execute_and_output {
    my ($sth, $fh) = @_;
    $sth->execute;
    $csv->print($fh, $sth->{NAME_lc});
    while (my $row = $sth->fetchrow_arrayref) {
        $csv->print($fh, $row);
    }
}
