#!/usr/bin/env perl
use strict;
use warnings;

# run the same query for all the blogs in wp_blogs
#
# "wp_X_" is string-replaced with each blog_id in wp_blogs, and the final
# selected output will always have the 2 columns "blog_id" and "blog_domain"
# appended to the row.
#
# $ ./mysql_grep.pl --database impactdb_dev2 --query 'select * from wp_X_postmeta where meta_key = "_wp_page_template" and meta_value = "chapter-page.php"'
# blog_id    blog_name   meta_id post_id meta_key    meta_value
# 9  isha    1362    1047    _wp_page_template   chapter-page.php
# 9  isha    1363    1050    _wp_page_template   chapter-page.php
# 9  isha    1365    1052    _wp_page_template   chapter-page.php
#
# The name of the database is required to be passed in with --database, and
# the query can either be passed in via --query or STDIN.
#
# With --filter, you can pass in perl code to run on each item in each row
# (set to $_). Eg, "s/.*(<style.*?<\/style>).*/$1/s" would filter every column
# of every row through a substitution regex.

use FindBin;
use lib $FindBin::Bin.'/../../database';
use DBUtils;
use Getopt::Long;

my %o;
if (not -t) {
    local $/;
    $o{query} = <STDIN>;
}
GetOptions(\%o, 'database=s', 'query=s', 'filter=s') or die "invalid options";

if ($o{filter}) {
    $o{filter} = eval "sub { my \$old = \$_; s/\r//g; s/\\n//g; return $o{filter} ? \$_ : \$old }";
    die $@ if $@;
}

die "both --database <name> and --query <sql> must be specified"
    unless $o{database} and $o{query};

my $dbh = DBUtils::wordpress($o{database});

my $blogs = $dbh->selectall_arrayref('select blog_id, substring_index(domain, ".", 1) from wp_blogs');
my $header_printed = 0;

foreach my $b (@$blogs) {
    my $sql = $o{query};
    $sql =~ s/wp_X_/wp_$b->[0]_/g;

    my $sth = $dbh->prepare($sql);
    $sth->execute;
    my $row = $sth->fetch;
    if ($row) {
        if (not $header_printed) {
            print join("\t", 'blog_id', 'blog_name', @{$sth->{NAME}}), "\n";
            $header_printed = 1;
        }
        while ($row) {
            if ($o{filter}) {
                $_ = $o{filter}->() foreach @$row;
            }
            print join("\t", $b->[0], $b->[1], @$row), "\n";
            $row = $sth->fetch;
        }
    }
}
