#!/usr/bin/env perl

use strict;
use warnings;

use FindBin;
use lib $FindBin::Bin.'/../../database';
use DBUtils;
use Getopt::Long;

my $db   = 'impactdb_dev2';
my $user = '';

GetOptions(
    'db=s'   => \$db,
    'user=s' => \$user,
) or die "invalid options";

die "--user <wp_users.user_login> is required" unless $user;

my $dbh = DBUtils::wordpress($db);

my $user_id =
  $dbh->selectrow_array( 'select id from wp_users where user_login = ?',
    undef, $user );
print "wp_users.id: $user_id\n";

foreach (
    @{
        $dbh->selectall_arrayref(
            "select post_id, meta_id from wp_1_postmeta 
    where meta_key = 'syi_active_owner' and meta_value = $user_id"
        )
    }
  )
{
    my ( $post_id, $meta_id ) = @$_;
    print "wp_1_postmeta.post_id: $post_id";
    $dbh->do("delete from wp_1_postmeta where post_id = $post_id");
    $dbh->do("delete from wp_1_posts where id = $post_id");
    print " deleted\n";
}
