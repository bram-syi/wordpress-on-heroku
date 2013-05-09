<?php

// GET ?allthemes=1 - return a list of theme names, with some additional description
//   that is intended to be used in auto-completion
// GET ?theme=foo - return the json for theme "foo"
// POST theme=foo&data=json - set the json for theme foo

include_once('../wp-load.php');
include_once(ABSPATH . '/wp-content/mu-plugins/teams.php');
include_once(ABSPATH . '/a/api/campaign.php');

$wpdb->show_errors();

if (array_key_exists('allthemes', $_GET)) {
  // dump all theme names from database
  dump_all_themes();
}
else if (array_key_exists('theme', $_GET)) {
  // return json of the theme
  get_one_theme($_GET['theme']);
}
else if ($_POST) {
  update_theme();
}
else if (array_key_exists('orgs', $_GET)) {
  print json_encode($wpdb->get_col('select SUBSTRING_INDEX(domain, ".", 1) as name from wp_blogs order by name'));
}
else {
  die("unexpected parameters");
}

function dump_all_themes() {
  global $wpdb;

  $all_themes = <<<EOS
SELECT `name` from theme_data
EOS;

  print json_encode( $wpdb->get_results($all_themes) );
}

function get_one_theme($theme) {
  $c = CampaignApi::getOne($theme);
  echo json_encode($c);
}

function update_theme() {
  // update json for this theme
  $p = stripslashes_deep($_POST);

  if (!isset($p['theme']))
    die("parameter 'theme' was empty");

  prefix_hack($p);
  $req = (object)$p;
  $req->name = $req->theme;
  $req->legacy = TRUE;

  $c = CampaignApi::create($req); // This will actually create OR update

  $campaign = "{$c->partner_domain}/{$c->name}";
  if ($p['teams']) {
    $teams = explode("\n", $p['teams']);
    foreach ($teams as $t) {
      Team::create_team($campaign, (object)array('name' => $t));
    }
  }

  print '{ "status":"ok", "url":"' . $c->url . '" }';
}

// this function will take any keys that begin with specific prefixes:
// - "h20_"
// - "facebook"
// and turn them into a hash of their own inside $data, eg
//   $data[h20_foo] = 'this is foo'
//   $data[h20_bar] = 'this is bar'
// would get turned into
//   $data[h20][foo] = 'this is foo'
//   $data[h20][bar] = 'this is bar'
function prefix_hack (&$data) {
  $prefixes = array('h20', 'facebook');

  foreach ($data as $key => $val) {
    foreach ($prefixes as $prefix) {
      if (preg_match("/^${prefix}_(.*)/", $key, $m)) {
        if (!array_key_exists($prefix, $data)) {
          $data[$prefix] = array();
        }

        $data[$prefix][$m[1]] = $val;
        unset($data[$key]);
      }
    }
  }

}
