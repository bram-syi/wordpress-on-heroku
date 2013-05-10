<?php

include_once(ABSPATH . '/a/api/campaign.php');

//
// Team functionality helper class
// All public functions take a "$campaign":
//  $campaign:  org & theme in the format "pratham/readathon"
//
class Team
{
  protected static $cache = array();

  public static function create_campaign($campaign, $args) {
    return CampaignApi::create_campaign($campaign, $args);
  }

  // Get the list of all teams in a particular campaign
  //  return: array of format url=>name, ie.
  //    "readathon/austin" => "Austin",
  //    "readathon/charlotte" => "Charlotte",
  public function get_teams($campaign) {
    global $wpdb;

    Team::prep($campaign);

    $key = $campaign->org . "/" . $campaign->name;
    if (isset(Team::$cache[$key]))
      return Team::$cache[$key];

    $teams = apply_filters('get_campaign_teams', array());
    if (count($teams) == 0) {
      $id = Team::get_team_parent($campaign);
      if ($id == 0)
        trace_up('unable to find team parent: '.var_export($campaign,1));
      else
        $rows = Team::get_team_pages($campaign, $id);

      foreach ($rows as $row) {
        $teams["$campaign->name/" . $row->post_name] = $row->post_title;
      }
    }

    Team::$cache[$key] = $teams;
    return $teams;
  }

  // Create a new team for a campaign for a particular organization
  // $campaign - org & name
  // $team (object) - options for the new team:
  //    * name - human-readable name
  //    (optional after this)
  //    * slug - slug-useable name (made from 'name')
  //    * goal - goal amount for team
  // returns an array with two things: post id, and permalink
  function create_team($campaign, $team) {
    global $wpdb;

    Team::prep($campaign);

    if (is_null($campaign->blog_id)) {
      trace_up("invalid campaign: $campaign->org/$campaign->name");
      return 0;
    }

    Team::check_slug($team->slug, $team->name);

    switch_to_blog($campaign->blog_id);

    // get campaign page id
    $campaign_page_id = $wpdb->get_var( $wpdb->prepare(
      "SELECT post_id FROM wp_{$campaign->blog_id}_postmeta WHERE meta_key = 'campaign' AND meta_value = %s", "$campaign->org/$campaign->name"
    ));
    if (is_null($campaign_page_id)) {
      trace_up("couldn't find post_id for campaign $campaign->org/$campaign->name in postmeta");
      return 0;
    }

    // create WP page with parent as the campaign Page
    $page_id = db_new_page(
      $campaign->blog_id,
      1,
      $team->name,
      '',
      $team->slug,
      0,
      $campaign_page_id
    );

//    error_log("create_team: $page_id (parent is $campaign_page_id)");

    // add postmeta
    add_post_meta($page_id, '_wp_page_template', 'chapter-page.php', true);
    if (property_exists($team, 'goal')) {
      add_post_meta($page_id, 'goal', $team->goal);
    }

    // setup theme promos (sidebar, header, etc)
    $parent_promo = $wpdb->get_var( $wpdb->prepare(
      "select id from wp_{$campaign->blog_id}_posts where post_name = %s", "sidebar-$campaign->name"
    ));
    db_new_page(
      $campaign->blog_id,
      1,
      $team->name,
      '<div>build your team sidebar</div>',
      $team->slug,
      0,
      $parent_promo,
      'promo'
    );

    $permalink = get_permalink($page_id);

    restore_current_blog();

    return array($page_id, $permalink);
  }

  public function get_team($campaign, $slug) {
    Team::prep($campaign);
    $teams = Team::get_teams($campaign);
    return $teams["$campaign->theme/$slug"];
  }

  public function get_other_team($campaign) {
    Team::prep($campaign);

    foreach (Team::get_team_pages($campaign) as $row) {
      $other_team = get_post_meta($row->id, 'is_independent', 1);
      if ($other_team and preg_match('/yes/i', $other_team)) {
        return $row->post_title;
      }
    }
  }

  // helper to validate a slug for our purposes, also may set it if empty
  public function check_slug(&$slug, $default) {
    $slug = sanitize_title($slug ? $slug : $default);
    $slug = strtolower($slug);
  }

  // get the individual team pages
  protected function get_team_pages($campaign, $id=NULL) {
    Team::prep($campaign);
 
    if (is_null($id)) {
      $id = Team::get_team_parent($campaign);
    }

    global $wpdb;
    $old_id = $wpdb->set_blog_id($campaign->blog_id);
    $rows = $wpdb->get_results($sql = $wpdb->prepare(
      "SELECT id,post_name,post_title FROM $wpdb->posts
      WHERE post_type='page' AND post_status='publish'
       AND post_parent=%d
       ORDER BY post_title",
      $id));
    $wpdb->set_blog_id($old_id);

    if (count($rows) == 0)
      return array();
    return $rows;
  }

  // get the master campaign page that owns all team pages
  protected function get_team_parent($campaign) {
    Team::prep($campaign);

    global $wpdb;
    $old_id = $wpdb->set_blog_id($campaign->blog_id);
    $id = $wpdb->get_var($sql = $wpdb->prepare(
      "SELECT id FROM $wpdb->posts p
      INNER JOIN $wpdb->postmeta pm ON pm.post_id = p.ID
      WHERE p.post_type='page' AND p.post_status='publish'
       AND pm.meta_key = 'campaign' and pm.meta_value = %s",
      "$campaign->org/$campaign->name"));
    $wpdb->set_blog_id($old_id);

    return $id;
  }

  // prep the campaign token if it hasn't already been prep
  public function prep(&$campaign) {
    if (is_object($campaign))
      return;

    list($org, $name) = explode('/', $campaign);

    global $wpdb;
    $bid = $wpdb->get_var($wpdb->prepare(
      "SELECT blog_id FROM charity
      WHERE domain LIKE %s",
      "$org%"));

    if (is_null($bid) or !$org or !$name) {
      trace_up("Team::prep failed: $campaign");
    }

    $campaign = (object)array(
      'blog_id' => $bid,
      'org' => $org,
      'name' => $name
    );
  }
}
