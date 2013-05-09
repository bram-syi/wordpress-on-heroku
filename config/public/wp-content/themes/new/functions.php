<?

// Placement of MINIFIED files
add_action('roots_head', 'place_minified_files_here');
function place_minified_files_here() {
?><!-- WP-Minify JS --><?
}

// define('WRAP_CLASSES',              'container-fluid');
define('SIDEBAR_CLASSES',              'span4 gradient');

global $HAS_SIDEBAR;
$HAS_SIDEBAR = FALSE;

// Include LESS processing
$file = WP_CONTENT_DIR . '/plugins/wp-less/bootstrap-for-theme.php';
if (file_exists($file)) {
  require $file;
  $WPLessPlugin->dispatch();
  add_action('wp_enqueue_scripts', array($WPLessPlugin, 'processStylesheets'), 10000, 0);
}

function get_js_dir($dir) {
  return "/wp-content/themes/new/js";
}
function get_css_dir($dir) {
  return "/wp-content/themes/new/css";
}
add_filter('get_js_dir', 'get_js_dir');
add_filter('get_css_dir', 'get_css_dir');

// Apply our local styles here
add_action('wp_enqueue_scripts', 'theme_resources', 200); // After Roots
function theme_resources() {
  $dir = "/wp-content/themes/new";
  $css = apply_filters('get_css_dir', "$dir/css");
  $js = apply_filters('get_js_dir', "$dir/js");

  // We take over all of the Bootstrap styles in our app
  wp_dequeue_style('roots_child_style');
  wp_dequeue_style('roots_bootstrap_style');
  wp_dequeue_style('roots_bootstrap_responsive_style');
  wp_dequeue_style('roots_app_style');

  $syi = "/wp-content/themes/syi";
  $ver = "3.0";
  wp_enqueue_style('syi-quotes', "$syi/quotes.css", null, $ver);
  wp_enqueue_style('syi-partner', "$syi/partner.css", null, $ver);
  wp_enqueue_style('syi-fundraiser', "$syi/fundraiser.css", null, $ver);
  wp_enqueue_style('syi-page', "$syi/standard-page.css", null, $ver);
  wp_enqueue_style('syi-campaign', "$syi/campaign.css", null, $ver);
  wp_enqueue_style('syi-buttons', "$syi/buttons.css", null, $ver);

  wp_enqueue_style('select2', "$js/select2/select2.css");
  wp_enqueue_script('select2', "$js/select2/select2.js");
  wp_enqueue_script('animation', "$js/animation.js");
  wp_enqueue_script('app', "$js/jquery.fitvids.js");

  wp_enqueue_style('syi', "$dir/../syi/style.css");
  wp_enqueue_style('app', "$css/app.less");
  wp_enqueue_script('app', "$js/app.js");

}

// For not logged in users
if (!is_user_logged_in()) {
/* Steve: TODO
  // Add an optional "What is SeeYourImpact?" header
  add_action('roots_header_inside', 'theme_header_inside');
  function theme_header_inside() {
    ?><div class="container nav-top"><div class="nav-top-msg">
      <strong>Hello!</strong>
      This site here is probably the coolest site you've ever used.
      <a href="#">learn more</a>
    </div></div><?
  }
*/
}

add_action('nav_main_after', 'theme_signin_button');
function theme_signin_button() {
  global $bp;

  ?>
  <ul class="nav pull-right">
    <? if (!is_user_logged_in()) { ?>
      <li><a id="signin-link" href="<?= get_signin_url() ?>">Log in</a></li>
      <? if (is_fb_connect_enabled()) { ?>
        <li><? display_fb_login(true); ?></li>
      <? } ?>
      </li>
    <? } else { ?>
      <? /* <li><? show_cart_link(); ?></li> */ ?>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <?
          switch_to_blog(1);
          $avatar = get_avatar( get_current_user_id(), 18 );
          restore_current_blog();
          echo $avatar;
          ?>
          <?= xml_entities(eor(get_displayname( get_current_user_id(), TRUE), "Me")) ?>
          <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <? do_action('member_actions_li'); ?>
          <li class="divider"></li>
          <li><a href="<?= get_signout_link() ?>">Log out</a></li>
        </ul>
      </li>
    <? } ?>
  </ul>

  <ul class="nav pull-right">
    <li><a name="search" class="search-box">
    <form class="fr-search visible-desktop" method="get" action="<? bloginfo('home'); ?>">
      <input class="span3" type="hidden" id="fr-search" name="s" value="" placeholder="Find people or charities" data-validators="required" data-speech-enabled="" x-webkit-speech="x-webkit-speech" autocomplete="off">
    </form>
    </a></li>
  </ul>
  <?
}

add_action('roots_head', 'fix_gradients');
function fix_gradients() {
?>
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
<?
}

add_action('member_actions_li', 'theme_member_actions');
function theme_member_actions() {
  global $bp, $blog_id;

  if (current_user_can('level_1')) {
    $admin_blog = $blog_id;
  } else {
    $blogs = get_blogs_of_user( $bp->loggedin_user->id );
    if ($blogs !== NULL)
      foreach ($blogs as $blog) {
        if (current_user_can_for_blog($blog->userblog_id, 'level_2')) {
          $admin_blog = $blog->userblog_id;
          break;
        }
      }
  }

  ?><li><a id="profile-link" href="<?= $bp->loggedin_user->domain ?>">My profile</a></li><?
  ?><li><a id="settings-link" href="<?= $bp->loggedin_user->domain ?>settings">Account settings</a></li><?
  if ($admin_blog > 0) {
    ?><li class="divider"></li><?
    if ($admin_blog > 1) { 
      ?><li><a id="publish-link" href="<?=get_site_url($admin_blog, "/publish/")?>">Publish stories</a></li><?
    }
    ?><li><a id="admin-link" href="<?=get_site_url($admin_blog, "/wp-admin/")?>">Administration</a></li><?
  }
}




// SIGNIN / SIGNOUT overrides

function get_signin_url() {
  global $blog_id;

  $url = wp_login_url();
  if ($blog_id > 1)
    $url = add_query_arg('ch',$blog_id, $url);

  $to = $_SERVER["REQUEST_URI"];
  if (!empty($to) && $to != "/")
    $url = add_query_arg('to', $to, $url);
  return $url;
}

function get_signout_link() {
  global $blog_id;
  $url = wp_logout_url();
  if ($blog_id > 1)
    $url = add_query_arg('ch', $blog_id, $url);
  return $url;
}

function syi_login_url( $path, $redirect = null ) {
  $url = get_site_url(1, "/signin/", 'login');

  if ( !empty($redirect) ) {
    $url = add_query_arg('redirect_to', urlencode($redirect), $url);
  }

  return $url;
}
function syi_logout_url( $path, $redirect = null ) {
  $url = get_site_url(1, "/signin/", 'login');

  if ( !empty($redirect) ) {
    $url = add_query_arg('redirect_to', urlencode($redirect), $url);
  }
  $url = add_query_arg( 'action', 'logout', $url);
  $url = wp_nonce_url( $url, 'log-out' );

  return $url;
}
add_filter('login_url', 'syi_login_url', 10, 2);
add_filter('logout_url', 'syi_logout_url', 10, 2);
add_filter('wp_signup_location', 'syi_login_url');
add_filter('lostpassword_url', 'syi_login_url');
add_filter('register', 'syi_login_url');

// Always show the blog 1 footer, even on other sites
add_filter('roots_footer_before', 'use_common_footer');
function use_common_footer() {
  switch_to_blog(1);
}

// Show page titles in a big section at top of page 
add_action('syi_page_title', 'syi_page_title');
function syi_page_title() {
  global $HAS_PAGE_TITLE;
  if ($HAS_PAGE_TITLE === FALSE)
    return;
 
  $title = apply_filters('syi_title', get_the_title());
  if (empty($title))
    return;

  ?>
    <h1 id="syi-title" class="syi-title"><?= $title ?></h1>
  <?
}

add_action('roots_wrap_before', 'syi_wrap_before');
function syi_wrap_before() {
  ?><div id="syi-page" class="syi-page container"><?
  do_action('syi_page_title');
}

// Sidebars for footers
$footer_menus = array(
  'footer-story' => 'Our Story',
  'footer-team' => 'Our Team',
  'footer-social' => 'Connect with SeeYourImpact.org'
);
foreach ($footer_menus as $id=>$name) {
  register_sidebar(array(
    'id' => $id,
    'name' => $name,
    'description' => 'for use in the footer'
  ));
}
register_nav_menus(array('footer' => 'Footer'));




