#!/usr/bin/env perl
use strict;
use warnings;

# greps the "theme_data" table in intelligent ways
# NOTE: input is assumed to be perl regexs
#
# $0 foobar
#   returns all themes matching 'foobar'
#
# $0 foobar < foo
#   merge in json from stdin into themes matching 'foobar'
# 
# $0 foobar remove keyname
#   remove key "keyname" from the theme json

use DBUtils;
use JSON;

my $dbh = DBUtils::wordpress();

my $themes = $dbh->selectcol_arrayref('select name from theme_data');

if (@ARGV > 0) {
    # print matching themes

    my @matches = grep { $_ =~ qr/$ARGV[0]/ } @$themes;
    die "no matching themes found" unless @matches;

    foreach my $theme (@matches) {
        if (@ARGV > 1 and $ARGV[1] eq 'remove' and $ARGV[2]) {
            my $old = get_theme($theme);
            delete $old->{$ARGV[2]};
            save_theme($theme, $old);
        }

        if (not -t) {
            my $stdin = do { local $/; <STDIN> };
            my $new = from_json($stdin);

            my $old = get_theme($theme);

            while (my ($k, $v) = each %$new) {
                $old->{$k} = $v;
            }

            save_theme($theme, $old);
        }
        else {
            print_theme($theme);
        }
    }
}
else {
    # print all themes
    foreach (@$themes) {
        print_theme($_);
    }
}

sub get_theme {
    my $str = $dbh->selectrow_array('select contents from theme_data where name = ?', undef, $_[0]);
    return from_json($str);
}

sub save_theme {
    $dbh->do('replace theme_data(name, contents) values (?, ?)', undef,
        $_[0], to_json($_[1]));
}

sub print_theme {
    my $hash = get_theme($_[0]);

    my $json = JSON->new;
    print "\"$_[0]\": " . $json->pretty->encode($hash) . "\n";
}

