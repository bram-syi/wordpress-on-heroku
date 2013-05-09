#!/usr/bin/env perl

use strict;
use warnings;

# "Progress dashboard" for importing old data into new tables
#
# Uses the queries defined in diff_queries.sql to find similarities
# and differences (with various nuances) between old<->new tables.

use FindBin;
use lib $FindBin::Bin.'/../../database';
use DBUtils;
use Getopt::Long;
use CGI qw/:standard *table *Tr *td/;
use CGI::Carp qw/fatalsToBrowser/;

$| = 1;

print header, start_html(
    -title => 'donation report progress',
    -style => {
        -code => "
* {
    white-space:pre-wrap;
    font-family:monospace;
}

td {
    vertical-align:top;
    padding:1ex;
}

td,th {
    border: 1px solid;
}

table {
    border-spacing:0;
    border-collapse:collapse;
    margin-bottom:1ex;
}
",
    },
);

my $dbh = DBUtils::wordpress();
#$dbh->{TraceLevel} = '1|SQL';

my @old_tables = qw/donationGifts payment donationAcct donation donationAcctTrans/;
my @new_tables = qw/a_gift a_payment a_account a_donation a_transaction/;
my @id_fields = qw/ID id id donationID id/;

print_count_groups('donation_report', 'status', 'type2');

print start_table({-style=>'border:0'}), start_Tr, start_td;
print_count_groups('a_account', 'type');
print end_td, start_td;
print_count_groups('a_account', 'donationAcctTypeId');
print end_td, start_td;
print_count_groups('a_payment', 'provider');
print end_td, start_td;
print_count_groups('a_transaction', 'type');
print end_td, end_Tr, end_table;

print start_table, Tr(th(['old table', 'new table', 'notes']));

for my $i (0..4) {
    my $missing_ids = diff($i, 'missing');
    my $different_rows = diff($i, 'different');
    my $copied_ids = diff($i, 'copied');
    my $new_ids = diff($i, 'new');

    my $succeeded = $copied_ids - $different_rows - $new_ids;
    $succeeded = 0 if $succeeded < 0;

    my $old_count = scalar(@{$dbh->selectcol_arrayref("select 1 from `$old_tables[$i]`")});
    my $new_count = scalar(@{$dbh->selectcol_arrayref("select 1 from `$new_tables[$i]`")});

    my $notes = join("\n", map {
        sprintf "%s: %d (%0.1f%%)", $_->[0], $_->[1], 100 * $_->[1]/$_->[2]
    } (
        ['missing', $missing_ids, $old_count],
        ['changed', $different_rows, $old_count],
        ['succeeded', $succeeded, $old_count],
        ['new rows', $new_ids, $new_count],
    ));
    $notes = "enough of a difference\nto make statistics\nmeaningless" if $i == 4;

    print Tr(td({-style=>'white-space:pre'}, [
        desc($old_tables[$i]),
        desc($new_tables[$i]),
        $notes,
    ]));
}

print end_table;

print end_html;

my @diff_queries;
sub diff {
    my ($i, $which) = @_;
    return 99999 if $i == 4; # donationAcctTrans <-> a_transaction is pointless to compare

    my %offset = (
        'missing' => 0,
        'copied' => 1,
        'new' => 2,
        'different' => 3,
    );
    my %hit;

    if (not @diff_queries) {
        open my $fh, '<', $FindBin::Bin.'/diff_queries.sql' or die $!;
        my $re = '^-- (' . join('|', keys %offset) . ') ';
        while (<$fh>) {
            if (/$re/) {
                push @diff_queries, "\n$_";
                $hit{$1}++;
            }
            else {
                $diff_queries[-1] .= $_;
            }
        }
        if (grep { $hit{$_} != 4 } keys %offset) {
            die "didn't get the right 4 queries per table from diff_queries.sql";
        }
    }

    my $sql = $diff_queries[4 * $i + $offset{$which}];
    return scalar @{$dbh->selectcol_arrayref($sql)};
}

sub print_count_groups {
    my ($table, $col, $group_concat) = @_;
    my $select = "select `$col`, count(*) as c" . ($group_concat ? ", group_concat(distinct `$group_concat`)" : '');

    my @th = ("$table.$col", "count");
    push @th, $group_concat if $group_concat;

    print start_table, Tr(th(\@th));

    foreach (@{$dbh->selectall_arrayref("
        $select
        FROM $table
        GROUP BY $col
        ORDER BY c desc")})
    {
        print Tr(td($_));
    }

    print end_table;
}

sub desc {
    my ($t) = @_;
    my $desc = "$t (".$dbh->selectrow_arrayref("select count(1) from $t")->[0]." rows)\n";
    for my $row (@{$dbh->selectall_arrayref("describe $t")}) {
        $desc .= '  ' . join(' ', map { defined($_) ? $_ : '-' } @$row) . "\n";
    }
    return $desc;
}
