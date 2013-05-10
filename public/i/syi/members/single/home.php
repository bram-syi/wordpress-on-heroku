<?
global $bp, $current_user, $cartID;

if ($bp->current_component == 'campaign') {
  locate_template( array('campaign.php'), true);
  die();
}
if ($bp->current_component == 'pledge') {
  locate_template( array('pledge.php'), true);
  die;
}
if ($bp->current_component == 'updates') {
  locate_template( array('updates.php'), true);
  die;
}

if (isset($_REQUEST['cid'])) {
  $cartID = decrypt_cart($_REQUEST['cid']);
  $bp->current_component = 'profile';
} 

global $NO_SIDEBAR;
$NO_SIDEBAR = TRUE;

add_action('get_crumbs', 'member_crumbs');
add_filter('body_class','profile_body_class');
add_filter('wp_title', 'profile_wp_title', 2, 2);

function profile_wp_title($title, $sep) {
  global $bp;
  $title = get_displayname($bp->displayed_user->id);
  if ($bp->current_component == 'settings')
    $title .= "'s Settings";
  return $title . $sep;
}

function is_my_profile_page() {
  global $bp;

  return bp_is_my_profile()
    && ($bp->current_component == 'profile')
    && ($bp->current_action == 'public' || $bp->current_action == '');
}

function show_about_me() {
  global $bp, $cartID;

  ?><div class="about-me widget"><div class="interior"><p><? 
  draw_sharing_horizontal();
  $about = bp_get_profile_field_data('field=About Me');
  if ($bp->displayed_user->id == 1909) {
    draw_promo_content('ubs-holidays');
  } else if (!empty($about)) { 
    echo nl2br(xml_entities($about)); 
  } else if (is_my_profile_page()) {
    profile_ad('customize-profile', 'personalize your profile', get_member_link($bp->loggedin_user->id, 'settings'));
  }
  ?></p><?

  if (is_my_profile_page() || (!is_user_logged_in() && $cartID>0)) { //own profile or unlogged thank you page
    ?><br/><? draw_promo_content('thanks-matching'); 
    ?><br/><? draw_promo_content('thanks-contact');
  }

  ?></div></div><? 
}

get_header();

?>
<div class="profile-panel">
    <? draw_sharing_vertical(); ?>
  <div class="profile-left">
    <? include_once('member-header.php'); ?>
    <? show_about_me(); ?>
  </div>
  <article class="profile-right type-profile based">
<?

  do_action( 'template_notices' );
  do_action( 'bp_before_member_home_content' );
  do_action( 'bp_before_member_body' );
  
  thankyou_widget();  
  
  if ( bp_is_user_activity() || !bp_current_component() )
   locate_template( array( 'members/single/activity.php' ), true );
  else if ( bp_is_user_blogs() )  
   locate_template( array( 'members/single/blogs.php' ), true );
  else if ( bp_is_user_friends() )  
   locate_template( array( 'members/single/friends.php' ), true );
  else if ( bp_is_user_groups() )  
   locate_template( array( 'members/single/groups.php' ), true );
  else if ( bp_is_user_messages() )  
   locate_template( array( 'members/single/messages.php' ), true );
  else if ( bp_is_user_profile() ) 
   locate_template( array( 'members/single/profile.php' ), true );
  else if ($bp->current_component == 'settings') 
   locate_template( array( 'members/single/profile.php' ), true );
  else if ($bp->current_component == 'payments') // not actually registered yet
   locate_template( array( 'members/single/profile.php' ), true );

  do_action( 'bp_after_member_body' );
  do_action( 'bp_after_member_home_content' );

  if (bp_current_action() == 'public' || bp_current_action() == '') {
    $first_name = get_userdata($bp->displayed_user->id)->display_name;
    slideshow_widget(array(
      'cols' => 6,
      'large' => 2,
      'title' => bp_is_my_profile() ? "Click on a story to see your impact:" : "Click on a story to see " . $first_name . "'s impact:",
      'user_id' => $bp->displayed_user->id,
      'slideshow' => true,
      'limit' => 30
    ));
  }
 
  if (is_my_profile_page()) {
    profile_ad('start-campaign', 'start fundraising', get_member_link(NULL, "campaign"));
    profile_ad('edit-settings', 'edit account settings', get_member_link(NULL, "settings"));

    $show_monthly_giving = current_user_can('level1') || get_user_meta($bp->displayed_user->id,'show_monthly_giving',true);

    if($show_monthly_giving) {
      echo '<br style="clear:both;"/>';
      profile_ad('monthly-giving', 'setup monthly giving', get_member_link(NULL, "profile/payments"));
    }
  }

  add_action('jquery_scripts', 'profile_scripts');


?></article><?
?></div><?

get_footer();
?>
