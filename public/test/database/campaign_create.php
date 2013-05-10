<?php

// These are helper functions for creating the Wordpress objects for campaigns
// (for example, the Pratham Readathon was the first):
// - creating campaigns
// - creating teams inside the campaigns

require_once( dirname(__FILE__) . '/../../wp-load.php' );
require_once( dirname(__FILE__) . '/../../wp-content/admin-plugins/NewCharity.php' );
require_once( dirname(__FILE__) . '/../../wp-admin/includes/taxonomy.php' );
require_once('db-functions.php');

print "<pre>";

define('XMLRPC_REQUEST', false);
require('../wp-blog-header.php');
ensure_logged_in_admin();

if (21259304 == 24) {
  global $wpdb;
  foreach (array('sowa/olympics', 'isha/2012-07', 'kidsco/bbq') as $x) {
    $parts = explode('/', $x);
    $blog_id = $wpdb->get_var("select blog_id from wp_blogs where domain like '$parts[0].%'");

    $dead = "from wp_{$blog_id}_posts where post_date >= '2012-07-11'";
    $c = $wpdb->query("delete from wp_{$blog_id}_postmeta where post_id in (select id $dead)");
    error_log("deleted $c rows");
    $c = $wpdb->query("delete $dead");
    error_log("deleted $c rows");

    error_log("wiped out $x");
  }
}

if (0) {
  Team::create_campaign("sowa/wintergames", (object)array(
    'desc' => 'Winter World Games',
    'goal' => 51000
  ));
}

if (0) {
  Team::create_campaign("isha/isha", (object)array(
    'desc' => 'Isha Vidhya',
    'goal' => 10000
  ));

  $teams = array(
    array( 'name' => 'New York' ),
    array( 'name' => 'New Jersey' ),
    array( 'name' => 'Michigan' ),
    array( 'name' => 'California' ),
    array( 'name' => 'Florida' ),
    array( 'name' => 'Tennessee' ),
    array( 'name' => 'Arizona' ),
    array( 'name' => 'Toronto, Canada' ),
    array( 'name' => 'Georgia' )
  );

  foreach ($teams as $t) {
    $t['goal'] = 250;
    Team::create_team('isha/isha', (object)$t);
  }
}

if (0) {
  Team::create_campaign("kidsco/bbq", (object)array(
    'desc' => 'Kids Co. BBQ',
    'goal' => 10000
  ));

  $teams = array(
    array( 'name' => 'Adams' ),
    array( 'name' => 'Queen Anne' ),
    array( 'name' => 'TOPS' ),
    array( 'name' => 'Island Park (Mercer Island)' ),
    array( 'name' => 'Lakeridge (Mercer Island)' ),
    array( 'name' => 'Graham Hill' ),
    array( 'name' => 'McDonald' ),
    array( 'name' => 'John Hay' ),
    array( 'name' => 'John Muir' ),
    array( 'name' => 'South Shore' )
  );

  foreach ($teams as $t) {
    $t['goal'] = 1000;
    Team::create_team('kidsco/bbq', (object)$t);
  }
}

if (1) {
  Team::create_campaign("pratham/marathon", (object)array(
    'desc' => 'Pratham Marathon',
    'goal' => 51000
  ));
}
