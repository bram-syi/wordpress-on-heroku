<?
/*
*/

if ( !defined( 'BP_AVATAR_FULL_WIDTH' ) )
  define( 'BP_AVATAR_FULL_WIDTH', 200 );

if ( !defined( 'BP_AVATAR_FULL_HEIGHT' ) )
  define( 'BP_AVATAR_FULL_HEIGHT', 200 );

if (!defined('CAMPAIGN_SLUG')) 
  define ('CAMPAIGN_SLUG', 'campaign');

define("BP_DEFAULT_COMPONENT","summary");

function campaign_setup_globals() {
  global $bp;

  $bp->campaign = (object)array();
  $bp->campaign->id = 'campaign';
  $bp->campaign->slug = CAMPAIGN_SLUG;
  $bp->active_components[$bp->campaign->slug] = $bp->campaign->id;
}
add_action('bp_setup_globals', 'campaign_setup_globals', 9999);

function set_bp_displayed_user_id($user_id) {
  global $bp;

  $bp->displayed_user->id = $user_id;
  // The domain for the user currently being displayed
  $bp->displayed_user->domain   = bp_core_get_user_domain( $bp->displayed_user->id );
  // The core userdata of the user who is currently being displayed
  $bp->displayed_user->userdata = bp_core_get_core_userdata( $bp->displayed_user->id );
  // Fetch the full name displayed user
  $bp->displayed_user->fullname = bp_core_get_user_displayname( $bp->displayed_user->id );
}

function has_active_campaign($uid = 0) {
  global $bp;

  // Is it cached?
  if ($uid === $bp->displayed_user->id && $bp->displayed_user->has_campaign !== NULL)
    return $bp->displayed_user->has_campaign;

  if ($uid == 0)
    $uid = $bp->displayed_user->id;

  if ($uid == 0)
    return NULL;

  $pid = get_campaign_for_user($uid);
  $post = get_post($pid);
  
  // disable non owner and non admin for draft campaign 
  if (get_post_type($pid) != 'event')
    $pid = 0;
  else if (get_post_status($pid) == 'draft' && !can_manage_campaign($pid))
    $pid = 0;

  // Cache it in the displayed user info
  if ($uid == $bp->displayed_user->id)
    $bp->displayed_user->has_campaign = $pid;

  return $pid;
}

function campaign_screen() {
  include_once( ABSPATH . '/wp-content/themes/syi/campaign.php');
  die();
}

function campaign_screen_edit() {
  global $edit_campaign;
  $edit_campaign = TRUE;
  include_once( ABSPATH . '/wp-content/themes/syi/page-start.php');
  die();
}

function profile_tab($prof, $component, $label, $display = true) {
  global $bp;

  if ($bp->current_component == $component)
    $display = true;

  if (!$display)
    return;

  ?><a href="<?
  echo $prof;
  if ($component != $bp->default_component)
    echo $component;
  ?>" class="tab tab-<?
  echo $component;
  if ($bp->current_component == $component)
    echo " tab-current";
  ?>"><?
  echo xml_entities($label);
  ?></a><?
}

function member_crumbs($crumbs) {
  global $bp;
  $name = get_displayname($bp->displayed_user->id, false);

  $prof = bp_core_get_user_domain($bp->displayed_user->id);

  $mine = bp_is_my_profile();

  $l = (object)apply_filters('profile_tab_labels', array(
    'fundraiser' => 'fundraiser',
    'updates' => 'updates',
    'profile' => $bp->displayed_user->has_campaign ? 'profile' : 'impact',
    'settings' => 'settings'));

  $update_count = apply_filters('get_update_count', 0);
  if ($update_count > 0)
    $update_count = " ($update_count)";
  else
    $update_count = "";

  ?>
  <div class="tabs">
    <? 
    profile_tab($prof, 'campaign', "my $l->fundraiser", $mine || $bp->displayed_user->has_campaign ); 
    profile_tab($prof, 'updates', "$l->updates$update_count", !empty($update_count));
    profile_tab($prof, 'profile', $l->profile);
    profile_tab($prof, 'settings', $l->settings, $mine);
    ?>
  </div>

  <? draw_avatar_box($bp->displayed_user->id, FALSE, TRUE); ?>
  <? 
    $loc = bp_get_profile_field_data('field=Location');
    $loc = apply_filters('member_location', xml_entities($loc));
    if (!empty($loc)) { ?>
    <span class="info">
      <img src="/wp-content/themes/syi/images/location.gif" width="11" height="15"> 
      <b><?= $loc ?></b>
    </span>
  <? } ?>

  <?

    $cause = apply_filters('member_cause', '');
    if ($cause) {
      echo "<span class=\"info\" style=\"color:#2B4E64;\">$cause</span>";
    }
}
function profile_body_class($classes) {
  global $event_theme, $blog_id;

  $classes[] = "profile-page";
  if ($blog_id > 1)
    $classes[] = "charity-page";

  if (bp_is_my_profile())
    $classes[] = 'my-profile';

  return $classes;
}

function profile_edit_body_class($classes) {
  $classes = profile_body_class($classes);

  $classes[] = "profile-edit";

  return $classes;
}


// Do the same for settings updated
add_action('xprofile_avatar_uploaded', 'back_to_my_profile');
function back_to_my_profile($loc) {
  wp_redirect(get_member_link(NULL, "profile"));
  die();
}

function more_profile_nav() {
  if (is_admin())
    return;

  global $bp;

  $profile_link = $bp->loggedin_user->domain . $bp->profile->slug . '/';
  $settings_link = $bp->loggedin_user->domain . $bp->settings->slug . '/';

  bp_core_reset_subnav_items('profile');
  bp_core_reset_subnav_items('settings');

  // This tricks BuddyPress into displaying either the
  // fundraiser or the profile of a user, depending on whether they
  // have a current campaign.  But only checks when you're actually
  // looking at a user's profile (for perf reasons)
  $uid = $bp->displayed_user->id;
  if ($uid > 0) {
    $bp->default_component = has_active_campaign($uid) ? 'campaign' : 'profile';
  }
  if ($bp->current_component == 'summary') {
    $bp->current_component = $bp->default_component;
  }

  // settings, pledge, campaign
  bp_core_new_nav_item( array( 'name' => 'Settings', 'slug' => 'settings', 'screen_function' => 'syi_profile_screen', 'position' => 60 ) );
  bp_core_new_nav_item( array( 'name' => 'Pledge', 'slug' => 'pledge', 'screen_function' => 'syi_profile_screen', 'position' => 70 ) );
  bp_core_new_nav_item( array( 'name' => 'Updates', 'slug' => 'updates', 'screen_function' => 'syi_profile_screen', 'position' => 75 ) );
  bp_core_new_nav_item( array( 'name' => 'Campaign', 'slug' => $bp->campaign->slug, 'screen_function' => 'syi_profile_screen', 'position' => 80 ));

  // campaign/edit
  bp_core_new_subnav_item(array(
    'name' => 'Edit Campaign',
    'parent_slug' => $bp->campaign->slug,
    'slug' => 'edit',
    'parent_url' => get_member_link($bp->loggedin_user->id, 'campaign'),
    'screen_function' => 'campaign_screen_edit',
    'position' => 90
  ));

 // profile/customize, profile/thanks, profile/campaign, profile/settings, profile/payments
  bp_core_new_subnav_item( array( 'name' => 'Customize' , 'slug' => 'customize', 'parent_url' => $profile_link, 'parent_slug' => $bp->profile->slug, 'screen_function' => 'syi_profile_screen', 'position' => 36 ) );
  bp_core_new_subnav_item( array( 'name' => 'Thanks' , 'slug' => 'thanks', 'parent_url' => $profile_link, 'parent_slug' => $bp->profile->slug, 'screen_function' => 'syi_profile_screen', 'position' => 35 ) );
  bp_core_new_subnav_item( array( 'name' => 'Campaign' , 'slug' => 'campaign', 'parent_url' => $profile_link, 'parent_slug' => $bp->profile->slug, 'screen_function' => 'syi_profile_screen', 'position' => 34 ) );
  bp_core_new_subnav_item( array( 'name' => 'Edit' , 'slug' => 'settings', 'parent_url' => $profile_link, 'parent_slug' => $bp->profile->slug, 'screen_function' => 'syi_profile_screen', 'position' => 33 ) );
  bp_core_new_subnav_item( array( 'name' => 'Payments and monthly giving' , 'slug' => 'payments', 'parent_url' => $profile_link, 'parent_slug' => $bp->profile->slug, 'screen_function' => 'syi_profile_screen', 'position' => 32 ) );

  // settings/payments
  bp_core_new_subnav_item( array( 'name' => 'Payments and monthly giving' , 'slug' => 'payments', 'parent_url' => $settings_link, 'parent_slug' => $bp->settings->slug, 'screen_function' => 'syi_profile_screen', 'position' => 32 ) );

}
function syi_profile_screen() {
  global $bp;

  if (($bp->current_action == 'edit' || $bp->current_component == 'settings')
    && !bp_is_my_profile() && !is_super_admin() )
    return false;

  bp_core_load_template( 'members/single/home' );
}

add_action( 'bp_setup_nav', 'more_profile_nav', 2 );
add_action( 'admin_menu', 'more_profile_nav', 2 );





function show_user_info() {
  ?><div class="sidebar-panel current-panel charity-panel profile-panel"><?
  locate_template( array( 'members/single/member-header.php' ), true );
  ?></div><?

}

function draw_bottom_widgets() {
  global $post;

  if ($post != null) {
    $part = $post->post_type;

    if (is_front_page())
      $part = "home";
    else if (is_page())
      $part = $post->post_name;
    get_template_part( 'widgets', $part );
  }
}
add_action('syi_bottom_widgets', 'draw_bottom_widgets', 0);

function profile_widgets() {
  global $bp;
  $title = get_displayname($bp->displayed_user->id, true);

  draw_widget('slideshow_widget', array(
    'cols' => 6,
    'large' => 2,
    'title' => "See $title's Impact:",
    'user_id' => $bp->displayed_user->id,
    'slideshow' => true,
    'limit' => 60
  ));
}





