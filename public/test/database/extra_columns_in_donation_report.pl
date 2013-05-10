#!/usr/bin/env perl

# modifies the "notes" column of donation_report to remove:
#   - DAT#
#   - ACCT#
#   - PMT# (this is already in "payment_id" column anyway)

use strict;
use warnings;

use FindBin;
use lib $FindBin::Bin.'/../../database';
use DBUtils;

my $dbh = DBUtils::wordpress('impactdb_dev3');

my $sth = $dbh->prepare('select notes, id from donation_report');
$sth->execute;
while (my $row = $sth->fetchrow_arrayref) {
    my $notes = $row->[0];
    if ($notes =~ s/\s*DAT\s*#\s*(\d+)\s*//) {
        $dbh->do('update donation_report set dat_id = ? where id = ?', undef, $1, $row->[1]);
    }
    if ($notes =~ s/\s*ACCT\s*#\s*(\d+)\s*//) {
        $dbh->do('update donation_report set acct_id = ? where id = ?', undef, $1, $row->[1]);
    }
    $notes =~ s/\s*PMT\s*#\s*\d+\s*//;
    if ($notes ne $row->[0]) {
        $dbh->do('update donation_report set notes = ? where id = ?', undef, $notes, $row->[1]);
    }
}
