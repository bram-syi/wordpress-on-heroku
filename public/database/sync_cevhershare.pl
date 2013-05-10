#!/usr/bin/env perl

#  1. copies the "wp_1_cevhershare" table to a multisite blog, eg to copy
#     to the "tss" blog:
#         $0 --name tss
#  2. copies all cevhershare settings from wp_1_options to wp_x_options
#  3. add cevhershare to the list of activated plugins
#
# optionally, pass in --all (instead of --name <org>) to do this copy for all
# blog ids > 1
#
# pass in --verbose if you want soothing output
#
# pass in --missing to see if the cevhershare settings table is missing from
# any microblogs

use DBUtils;
use PHPSerialization qw/serialize unserialize/;
use Getopt::Long;

my %o;
GetOptions(\%o, 'all', 'verbose', 'name=s', 'missing') or die "invalid arguments";

my $dbh = DBUtils::wordpress();
if ($o{missing}) {
    foreach (@{$dbh->selectall_arrayref('select blog_id, domain from wp_blogs where blog_id > 1')}) {
        check_blog(@$_);
    }
}
elsif ($o{all}) {
    foreach (@{$dbh->selectall_arrayref('select blog_id, domain from wp_blogs where blog_id > 1')}) {
        do_blog(@$_);
    }
}
elsif ($o{name}) {
    my ($id, $domain) = $dbh->selectrow_array('select blog_id, domain from wp_blogs where domain like ?', undef, "$o{name}\%");
    die "couldn't get blog id of $ARGV[0]" unless $id;
    do_blog($id, $domain);
}
else {
    die "nothing to do!";
}

sub do_blog {
    my ($id, $domain) = @_;
    die "changing blog 1 is not allowed" if $id == 1;

    if ($o{verbose}) {
        printf("%3d: %s\n", $id, $domain);
    }

    $dbh->do("drop table if exists wp_${id}_cevhershare");
    $dbh->do("create table wp_${id}_cevhershare like wp_1_cevhershare");
    $dbh->do("insert wp_${id}_cevhershare select * from wp_1_cevhershare");

    print "   wp_${id}_cevhershare table created\n" if $o{verbose};

    foreach (@{$dbh->selectall_arrayref('select option_value, autoload, option_name from wp_1_options where option_name like "cevhershare%"')}) {
        my $sql;
        if ($dbh->selectrow_arrayref("select 1 from wp_${id}_options where option_name = ?", undef, $_->[2])) {
            $sql = "update wp_${id}_options set option_value = ?, autoload = ? where option_name = ?";
        }
        else {
            $sql = "insert wp_${id}_options(option_value, autoload, option_name) values(?,?,?)";
        }

        $dbh->do($sql, undef, @$_);
    }

    print "   options copied from wp_1_options to wp_${id}_options\n" if $o{verbose};

    my $s_array = $dbh->selectrow_array("select option_value from wp_${id}_options where option_name = 'active_plugins'");
    my $plugins = unserialize($s_array);
    if (not grep { /cevhershare/ } @$plugins) {
        push @$plugins, 'cevhershare/cevhershare.php';
        $dbh->do("update wp_${id}_options set option_value = ? where option_name = 'active_plugins'", undef, serialize($plugins));
        print "   cevhershare plugin is now activated\n" if $o{verbose};
    }
    else {
        print "   cevhershare plugin was already activated\n" if $o{verbose};
    }
}

sub check_blog {
    my ($id, $domain) = @_;
    eval {
        my $row_count = $dbh->selectrow_array("select count(1) from wp_${id}_cevhershare");
        # print "$domain: $row_count rows in cevhershare table\n";
    };
    if ($@) {
        print "$domain ($id): no cevhershare table\n";
    }
}