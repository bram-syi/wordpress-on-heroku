<? 
define ('PJAX', isset($_SERVER['HTTP_X_PJAX']));

if (!session_id()) {
  session_start();
}

global $siteinfo,$give_url,$post,$pagenow;
global $NO_SIDEBAR, $IS_HOME_PAGE;

do_action('syi_has_sidebar'); 
if (!$NO_SIDEBAR && !has_action('get_sidebar'))
  add_action('get_sidebar', 'home_sidebar');

if (!defined('FB_PLACEMENT'))
  define('FB_PLACEMENT', 'footer');

// Heavy-handed nocache until we can donut cache
nocache_headers();

global $is_https_page;
if ($_SERVER['HTTPS'])
  $is_https_page = TRUE;

if (!PJAX) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
<? if ($_REQUEST['debug_hooks'] == 'yes') 
   add_action( 'all', create_function( '', 'echo "{{" . current_filter() . "}}";' ) ); 
?>
<? core_scripts(); ?>
<!-- www.phpied.com/conditional-comments-block-downloads/ -->
<!--[if IE]><![endif]-->
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->

<!--[if lte IE 8]>
<style type="text/css">
.is-old { display: block !important; }
.is-not-old { display: none !important; }
</style>
<![endif]-->
<!-- <?= SITE_URL ?> -->

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="apple-touch-icon" href="<?= __C("images/syi-vertical-logo.png") ?>"/>

<title><?= wp_title(' - ', FALSE, 'right'); ?><?php bloginfo('name'); ?></title>
<? if (has_action('facebook_meta')) { 
  do_action('facebook_meta');
} else if (is_single()) { 
  global $post; 
  $ex = get_excerpt($post);
?>
  <meta name="description" content="<?= esc_attr($ex) ?>" />
<? } ?>

<? if ($_SERVER['REQUEST_URI'] === '/') {
  echo SyiFacebook::array_as_metatags(array(
    'url'         => site_url(),
    'type'        => SyiFacebook::name_space() . ':fundraiser',
    'title'       => 'Meet the life you change',
    'description' => 'We make it easy to give to the cause you care about and share stories about the lives you\'ve changed',
  ));
}
else {
  do_action('syi_meta_tags');
} ?>

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<?php wp_head();

global $typekit;
if (!empty($typekit)) {
?>
<script type="text/javascript" src="http://use.typekit.net/<?=$typekit?>.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?
}

/*
if (!is_user_logged_in()) { 
  ?><script src="//cdn.optimizely.com/js/3680022.js"></script><?
}
*/
?>

<? if (is_live_site()) { // segment.io -> one call for all analytics
global $bp;
?>
<script type="text/javascript">
<? if (is_user_logged_in()) { ?>
  var clicky_custom = {};
  clicky_custom.session = {
    username: '<?= xml_entities($bp->loggedin_user->userdata->user_login) ?>',
    email: '<?= xml_entities($bp->loggedin_user->userdata->user_email) ?>'
  };
<? } ?>

  var analytics=analytics||[];analytics.load=function(e){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src=("https:"===document.location.protocol?"https://":"http://")+"d2dq2ahtl5zl1z.cloudfront.net/analytics.js/v1/"+e+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);var r=function(e){return function(){analytics.push([e].concat(Array.prototype.slice.call(arguments,0)))}},i=["identify","track","trackLink","trackForm","trackClick","trackSubmit","pageview","ab","alias"];for(var s=0;s<i.length;s++)analytics[i[s]]=r(i[s])};
  analytics.load("ulf6gnojxb");
<? if (is_user_logged_in()) { ?>
  analytics.identify('<?= xml_entities($bp->loggedin_user->userdata->user_login) ?>', {
    name: '<?= xml_entities($bp->loggedin_user->fullname) ?>',
    email: '<?= xml_entities($bp->loggedin_user->userdata->user_email) ?>',
    created: '<?= xml_entities($bp->loggedin_user->userdata->user_registered) ?>'
  });
<? } else { /* our tracker */ ?>
  window.trackViews = true;
<? } ?>
</script>
<? } ?>

<script type="text/javascript">
  mpact = {"key":"510b0bd10fdc36993300011f","queue":[],"widget_class":"mpact_widgets"};

  var mpScript = document.createElement('script');
  mpScript.type = 'text/javascript';
  mpScript.src = '//mpact.it/framework/mpact.min.js';
  mpScript.async = true;
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mpScript, s);
</script>

</head>
<body id="body" <?php body_class(); ?>>
<? if (FB_PLACEMENT == 'header') { ?>
<div id="fb-root"></div>
<script type="text/javascript">
window.fbAsyncInit = function() { FB.init({appId: '123397401011758', channelUrl: '//<?= $_SERVER["SERVER_NAME"] ?>/fb-channel.php', status: true, cookie: true, xfbml: true}); };
(function() {
  var e = document.createElement('script'); e.async = true;
  e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
  e.async = true;
  document.getElementById('fb-root').appendChild(e);
  }());
</script>
<? } ?>

<div id="outer">
<? if (!isset($_REQUEST['NOHDR'])) { ?>

<?
global $header_file;
global $context;

// give the theme a chance to enhance the context
$context = apply_filters('modify_team_context', $context);

if (empty($header_file))
  $header_file = "default";

$header = ABSPATH . "/wp-content/headers/$header_file.php";
if (!file_exists($header))
$header = "default-header.php";

?>
<header id="header" class="page-center header-<?=$header_file?>" style="overflow:visible;">
<? include_once($header); ?>
</header>
<? } ?>
<? } // !PJAX ?>
<div id="container" class="page-center <?= $NO_SIDEBAR ? 'without' : 'with' ?>-sidebar">

<? do_action('full_width_top'); ?>

<? if (!$NO_SIDEBAR) { ?>
<section class="left sidebar evs" id="sidebar">
<? if (!$IS_HOME_PAGE) { ?>
  <div class="sidebar-gap"></div>
  <div class="sidebar1">
    <div class="sidebar2">
      <div class="sidebar3">
        <? get_sidebar(); ?>
      </div>
    </div>
    <div class="sidebar-shadow"></div>
  </div>
<? } ?>
  <div class="social">
    <? do_action('syi_sidebar'); ?>
  </div>
</section>
<? } ?>
<section id="content" class="content page-content"><?

do_action('syi_pagetop'); 

function draw_login_bar($show = TRUE) {
  global $bp, $blog_id, $BLOG_ID;

  // When we display a site-1 page on a charity subsite, we set BLOG_ID --
  // This lets us draw the right login_bar
  if ($BLOG_ID > 0)
    switch_to_blog($BLOG_ID);

  ?><div class="login-bar"><?
  if ($show == TRUE) {
    do_action('header_left');
    if (is_user_logged_in()) {
      $name = get_firstname(get_current_user_id());
      if (current_user_can('level_1')) {
        $admin_blog = $blog_id;
      } else {
        $blogs = get_blogs_of_user( get_current_user_id() );
        if ($blogs !== NULL) 
          foreach ($blogs as $blog) {
            if (current_user_can_for_blog($blog->userblog_id, 'level_2')) {
              $admin_blog = $blog->userblog_id;
              break;
            }  
          }
      }
      ?>
      <strong>Hi, <?= $name ?>: </strong>

      <? if ($admin_blog > 0) { ?>
        <? if ($admin_blog > 1) { ?>
          <a id="publish-link" href="<?=get_site_url($admin_blog, "/publish/")?>">publish stories</a> |
        <? } ?>
        <a id="admin-link" href="<?=get_site_url($admin_blog, "/wp-admin/")?>">administration</a> |
      <? } 
      ?><a id="profile-link" href="<?= $bp->loggedin_user->domain ?>">your profile</a> | <?

      $url = wp_logout_url();
      if ($blog_id > 1)
        $url = add_query_arg('ch',$blog_id, $url);
      ?><a id="signout-link" href="<?= $url ?>">sign out</a><?

      show_cart_link();
    } else { 
      ?><a id="signin-link" href="<?= get_signin_url() ?>">sign in</a><? 

      if (is_fb_connect_enabled()) {
        echo '<span style="margin:0 7px;">or</span>'; 
        display_fb_login(true);
      }

      show_cart_link();
    }
  } 
  ?></div><?

  if ($BLOG_ID > 0)
    switch_to_blog(1);
}
