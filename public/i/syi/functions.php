<?php

global $siteinfo, $give_url, $site_url;

$siteinfo = get_blog_details( 1 );
$site_url = $siteinfo->siteurl;
$site_name = $siteinfo->blogname;
$give_url = get_site_url(1, '/give/');

define('SYI_THEME', dirname(__FILE__) . '/');

register_sidebar(array(
  'name' => 'Home Page Bottom',
  'id' => 'sidebar-home-bottom',
  'description' => 'Widgets appear at the bottom of the home page',
  'before_title' => '<h3>',
  'after_title' => '</h3>'
));
register_sidebar(array(
  'name' => 'Page Bottom',
  'id' => 'sidebar-bottom',
  'description' => 'Widgets appear at the bottom of every page but the home page',
  'before_title' => '<h3>',
  'after_title' => '</h3>'
));

function turn_off_sidebar() {
  global $NO_SIDEBAR;

  // Turn off sidebar on profile fundraiser page
  if(bp_current_action() == 'campaign')
    $NO_SIDEBAR = true;

  if (is_page('fundraisers') || is_page('cloud'))
    $NO_SIDEBAR = true;
}
add_action('syi_has_sidebar', 'turn_off_sidebar');

add_theme_support('post-thumbnails');
add_theme_support('custom-header');
add_theme_support('automatic-feed-links');
add_theme_support('nav-menus');

add_filter( 'show_admin_bar', '__return_false' );

register_nav_menu('top-menu', 'Menu at top of page'); 
register_nav_menu('primary_navigation', 'Primary Navigation'); 
//register_nav_menu('side-menu', 'Menu in sidebar');

add_editor_style('article.css');

function fff($mce_css) {
  //var_dump($mce_css);
}
add_filter('mce_css', fff);

//remove auto loading rel=next post link in header
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action( 'wp_head', 'index_rel_link');
remove_action( 'wp_head', 'parent_post_rel_link');
remove_action( 'wp_head', 'start_post_rel_link');
remove_action( 'wp_head', 'next_post_rel_link');
remove_action( 'wp_head', 'wp_generator'); // Removes the Wordpress version i.e. - WordPress 2.8.4

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

function home_sidebar() {
  $font = "quattrocento-sans"; // "m-1c";
?>
  <script src="http://use.edgefonts.net/<?= $font ?>.js"></script>
  <div id="featured-photo-sidebar" class="panel sidebar-panel current-panel home-panel">
    <p class="wf" style="font: bold 30px <?= $font ?>, arial;">Create and celebrate impact through stories of change.</p>
    <p>We make it easy for you and your friends to give to the cause you care about, and share stories about the lives you've changed.</p>
    <p style="text-align:center; margin: 15px 0 20px;"><a id="get-started" class="button orange-button big-button left-button" style="width: 190px; border: 2px solid white; padding: 0.5em;" href="/give/?gpg=home">See ways to help &raquo;</a></p>
  </div>
<?
}

function syi_should_seo() {
  return is_single() OR is_page();
}

function syi_logo_tag() {
  return syi_should_seo() ? "h2" : "h1";
}

function syi_article_tag() {
  return syi_should_seo() ? "h1" : "h2";
}

function syi_default_sidebar() {
?><section id="sidebar">
  <? get_sidebar(); ?>
</section>
<?
}

$gwo_write_control = array();
$gwo_variation = array();
$gwo_conversion = array();

function gwo_write_control($ua, $exp) {
return;
?>
<!-- Google Website Optimizer Control Script -->
<script>
function utmx_section(){}function utmx(){}
(function(){var k='<?= $exp ?>',d=document,l=d.location,c=d.cookie;function f(n){
if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.indexOf(';',i);return c.substring(i+n.
length+1,j<0?c.length:j)}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;
d.write('<sc'+'ript src="'+
'http'+(l.protocol=='https:'?'s://ssl':'://www')+'.google-analytics.com'
+'/siteopt.js?v=1&utmxkey='+k+'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='
+new Date().valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
'" type="text/javascript" charset="utf-8"></sc'+'ript>')})();
</script><script>utmx("url",'A/B');</script>
<!-- End of Google Website Optimizer Control Script -->
<?
}

function gwo_write_tracking($ua, $exp, $page="test") {
return;
?>
<!-- Google Website Optimizer Tracking Script -->
<script type="text/javascript">
if(typeof(_gat)!='object')document.write('<sc'+'ript src="http'+
(document.location.protocol=='https:'?'s://ssl':'://www')+
'.google-analytics.com/ga.js"></sc'+'ript>')</script>
<script type="text/javascript">
try {
var gwoTracker=_gat._getTracker("<?= $ua ?>");
gwoTracker._trackPageview("/<?= $exp ?>/<?= $page ?>");
}catch(err){}</script>
<!-- End of Google Website Optimizer Tracking Script -->
<?
}

function gwo_ab_test($exp, $start, $end) {
  $url = $_SERVER["REQUEST_URI"];
  if ($url == $start)
    gwo_control($exp);
  else if ($url == $end)
    gwo_conversion($exp);
}
function gwo_ab_variation($exp, $page) {
  $url = $_SERVER["REQUEST_URI"];
  if ($url == $page)
    gwo_variation($exp);
}

function gwo_control($exp) {
  global $gwo_write_control;

  $gwo_write_control[] = $exp;
}
function gwo_variation($exp) {
  global $gwo_variation;

  $gwo_variation[] = $exp;
}
function gwo_conversion($exp) {
  global $gwo_conversion;

  $gwo_conversion[] = $exp;
}
function gwo_head() {
  global $gwo_write_control;

  $ua = "UA-7014490-1";
  for ($i = 0; $i < count($gwo_write_control); $i++)
    gwo_write_control($ua, $gwo_write_control[$i]);
}
function gwo_footer() {
  global $gwo_write_control, $gwo_variation, $gwo_conversion;

  $ua = "UA-7014490-1";
  for ($i = 0; $i < count($gwo_write_control); $i++)
    gwo_write_tracking($ua, $gwo_write_control[$i]);
  for ($i = 0; $i < count($gwo_variation); $i++)
    gwo_write_tracking($ua, $gwo_variation[$i]);
  for ($i = 0; $i < count($gwo_conversion); $i++)
    gwo_write_tracking($ua, $gwo_conversion[$i], "goal");
}
add_action('wp_head', 'gwo_head', 100);
add_action('wp_footer', 'gwo_footer', 100);

/* ADD TESTS HERE -- LATER TO BE MADE DYNAMIC */
gwo_ab_test('4088764788', "/", "/gift-browser/");
gwo_ab_variation('4088764788', "/?v1=1");
gwo_ab_variation('4088764788', "/?v2=1");


// ADD OUR JQUERY SCRIPTS TO THE END OF THE PAGE
function jquery_scripts() {
  if (has_action('jquery_scripts')) { 
  ?><script type="text/javascript">
  $(function() {
  <? do_action('jquery_scripts'); ?>
  });
  </script><?
  }
}
add_action('wp_footer', 'jquery_scripts', 50);

////////////////////////////////////////////////////////////////////////////////

/*
add_action('init','init_stories');
function init_stories() {
  $labels = array(
    'name' => _x('Stories','general name'),
    'singular_name' => _x('Story','singular name'),
    'add_new' => _x('Add New', 'story'),
    'add_new_item' => __('Add New Story'),
    'edit_item' => __('Edit Story'),
    'new_item' => __('New Story'),
    'view_item' => __('View Story'),
    'search_items' => __('Search Stories'),
    'not_found' => __('No Stories Found'),
    'not_found_in_trash' => __('No Stories Found in Trash'),
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => true,
    'query_var' => 'stories',
    'supports' => array('title', 'editor', 'author', 'thumbnail',
    'excerpt', 'custom-fields', 'comments', 'revisions')
  );

  register_post_type( 'story' , $args );
}
*/


////////////////////////////////////////////////////////////////////////////////

function core_scripts() {
  global $did_core_scripts;

  if ($did_core_scripts)
    return;
  $did_core_scripts = TRUE;

?>
<!-- WP-Minify CSS -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='<?= get_template_directory_uri(); ?>/jquery.min.js'>\x3C/script>")</script>
<!-- WP-Minify JS -->
<?
}

add_action('admin_enqueue_scripts', 'core_scripts');
add_action('wp_print_scripts', 'core_scripts');
add_action('admin_print_scripts', 'core_scripts');

function get_js_dir($dir) {
  return "/wp-content/themes/syi";
}
function get_css_dir($dir) {
  return "/wp-content/themes/syi";
}
add_filter('get_js_dir', 'get_js_dir');
add_filter('get_css_dir', 'get_css_dir');

// We have a symlink in place to shorten resource URLS
// This adds a substitution layer at the end of the filter chain
function substitute_symlink($dir) {
  return str_replace("wp-content/themes/syi","i/syi", $dir);
}
add_filter('get_js_dir', 'substitute_symlink', 100);
add_filter('get_css_dir', 'substitute_symlink', 100);

function register_syi_scripts() {
  $ver ='2.3'; 
  $js = apply_filters('get_js_dir','');
  $css = apply_filters('get_css_dir','');

  $tools = "$js/jquery.tools.min.js";

  wp_enqueue_script('modernizr', "$js/modernizr.js");
  wp_deregister_script('jquery');
  wp_register_script('jquery', '', '', '', true); // It's hardcoded in the header

  if (!is_admin()) {
    wp_deregister_script('l10n');
  } 

  wp_enqueue_script('jquery.cookies', "$js/jquery.cookies.js", "jquery", $ver);

  global $post;
  if (isset($post) && $post->ID == 12297) {
    // STEVE: Special-case Zaarly code.  Later could turn this into behavior for all landing-style pages
    wp_enqueue_style('style-frame', "$css/style-frame.css");
    wp_enqueue_script('syi-animation', "$js/animation.js", "jquery", $ver);
    wp_enqueue_script('syi-behavior', "$js/behavior.js", null, $ver);
    return;
  }

  wp_enqueue_script('jquery-tools', $tools, 'jquery');
  wp_deregister_script('swfobject');
  wp_dequeue_script('swfobject');
  wp_register_script('swfobject', 'https://ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js');

  wp_enqueue_script('syi-bbq', "$js/jquery.ba-bbq.min.js", "jquery", $ver);
  wp_enqueue_script('syi-blockui', "$js/jquery.blockUI.js", "jquery", $ver);

  //cloudsponge
  wp_enqueue_script('csimport', "$js/csimport.js");

  wp_enqueue_script('rangeinput', "$js/rangeinput.js");
  wp_enqueue_script('pjax', "$js/jquery.pjax.js");
  wp_enqueue_script('plupload-all');

  wp_register_style('syi-article', "$css/article.css", null, $ver);
  wp_register_style('syi-buttons', "$css/buttons.css", null, $ver);
  wp_register_style('syi-colorbox', "$css/colorbox.css", null, $ver);
  wp_register_style('syi-style', "$css/style.css", null, $ver);

  if (!is_admin()) {
    wp_enqueue_style('syi-article');
    wp_enqueue_style('syi-buttons');
    wp_enqueue_style('syi-colorbox');
    wp_enqueue_style('syi-placeholder', "$css/placeholder_polyfill.min.css", null, $ver);

    wp_enqueue_style('select2', "$css/select2/select2.css", "jquery");
    wp_enqueue_script('select2', "$js/select2/select2.js", "jquery");

    wp_enqueue_style('syi-standard-page', "$css/standard-page.css", null, $ver);
    wp_enqueue_style('syi-quotes', "$css/quotes.css", null, $ver);
    wp_enqueue_style('syi-partner', "$css/partner.css", null, $ver);
    wp_enqueue_style('syi-fundraiser', "$css/fundraiser.css", null, $ver);
    wp_enqueue_style('style-frame', "$css/style-frame.css");
    wp_enqueue_style('syi-style');

    wp_enqueue_script('syi-template', "$js/template.js", "jquery", $ver);
    wp_enqueue_script('syi-easing', "$js/jquery.easing.1.3.js", "jquery", $ver);
    wp_enqueue_script('syi-scrollTo', "$js/jquery.scrollTo-1.4.2-min.js", "jquery", $ver);
    wp_enqueue_script('syi-colorbox', "$js/jquery.colorbox.js", "jquery", $ver);
    wp_enqueue_script('syi-color', "$js/jquery.color.js", "jquery", $ver);
    wp_enqueue_script('syi-resize', "$js/jquery.resizeOnApproach.1.0.js", "jquery", $ver);
    wp_enqueue_script('syi-animation', "$js/animation.js", "jquery", $ver);
    wp_enqueue_script('syi-placeholder', "$js/placeholder_polyfill.jquery.min.combo.js", "jquery", $ver);
    wp_enqueue_script('syi-giftbrowser', "$js/gift-browser.js", null, $ver);
    wp_enqueue_script('syi-fitvids', "$js/jquery.fitvids.js", "jquery", $ver);

    // Always last since it depends on above
    wp_enqueue_script('syi-behavior', "$js/behavior.js", null, $ver);
  } 
}   
add_action('wp', 'register_syi_scripts',0);

function register_syi_scripts2() {
  $ver ='2.3';
  $js = apply_filters('get_js_dir','');
  $css = apply_filters('get_css_dir','');

  wp_enqueue_style('syi-widgets', "$css/widgets.css", null, $ver);
}
add_action('wp_enqueue_scripts', 'register_syi_scripts2', 100);

function truncate($str, $len) {
  if (strlen($str) <= $len + 2)
    return $str;
  return substr($str, 0, $len) . '...';
}

function crumb_it() {
}

function draw_the_crumbs() {
  if ($blog_id == 1)
    return;

  global $NO_SIDEBAR;

  global $blog_id;
  global $siteinfo;
  global $post;
  global $wp_query;

  $crumb = '<a href="' . $siteinfo->siteurl . '" class="home">home</a> &gt; ';

  if (!has_action('get_crumbs')) {
  if ($blog_id == 1) {
    if (is_front_page() || is_page('cloud'))
      return;

    if (get_post_type() == 'article') {
      $crumb = $crumb . ' <a href="/explore/need/">Find a Need</a> &gt; ';
    } else if (get_post_type() == 'event') {
      $crumb = $crumb . ' events &gt; ';
    } else if (is_home() || is_single() || is_archive()) {
      $crumb = $crumb . ' <a href="/blog/">our blog</a> &gt; ';
    }

  } else {
    $crumb = $crumb . ' <a href="/">' . htmlspecialchars(get_bloginfo('name')) . '</a> &gt; ';

    if (is_home() || is_single() || is_archive()) {
      if (get_post_type() == 'gift') {
      } else {
        $crumb = $crumb . ' <a href="/stories/">Stories</a> &gt; ';
      }
    }
  }

  if (is_singular()) {
    $p = $post;

    $title = (isset($_REQUEST['gift']) && $_REQUEST['gift'] == 1?
      GIFTCERT_PAGE_CRUMB : $post->post_title);

    while ($p->post_parent != null) {
      $p = get_post($p->post_parent);
      $url = get_post_permalink($p->ID);
      $parents = '<a href="'.$url.'">'. $p->post_title . '</a> &gt; ' . $parents;
    }

    if (!is_single()) {
      $crumb = $crumb . $parents . ' <span class="current">' . htmlspecialchars($title) . '</span>';
    }
  } else if (is_category()) {
    $tax_obj = $wp_query->get_queried_object();
    $crumb = $crumb . '<span class="current">' . htmlspecialchars($tax_obj->name) . '</span>';
  } else if (is_tag()) {
    $tax_obj = $wp_query->get_queried_object();
    $crumb = $crumb . '<span class="current">tagged "' . htmlspecialchars($tax_obj->name) . '"</span>';
  } else if (is_home()) {
  }
  }
?> 
  <? if (has_action('get_crumbs')) { ?>
    <div class="crumbs crumbs-<?=$post->post_name?>">
      <? do_action('get_crumbs', $crumb); ?>
    </div>
  <? } else if (!$NO_SIDEBAR) { ?>
    <div class="crumbs crumbs-<?=$post->post_name?>">
      <? echo $crumb; ?>
    </div>
  <? } ?>
<?
}
add_action('syi_pagetop', 'draw_the_crumbs', 0);





////////////////////////////////////////////////////////////////////////////////

function custom_login() {
echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/custom-login.css" />';
}
add_action('login_head', 'custom_login');

function the_title_trim($title)
{
  $pattern[0] = '/Protected:/';
  $pattern[1] = '/Private:/';
  $replacement[0] = ''; // Enter some text to put in place of Protected:
  $replacement[1] = ''; // Enter some text to put in place of Private:

  return preg_replace($pattern, $replacement, $title);
}
add_filter('the_title', 'the_title_trim');

function event_widgets() {
  dynamic_sidebar('event-widgets');
}

function profile_scripts() { ?>
  tilt(".items .slide", 18);

  // 7: 92. 9: 78. 13: 62. 30: 52
  // or at 78, 7: reduce 0, 9: reduce 3, 13 reduce 6, 30 reduce 7

  $(".story-slideshow .items").each(function() {
    var s = $(this).children(".slide");
    s.resizeOnApproach({
      elementDefault: 60,
      elementClosest: 120,
      triggerDistance: 120,
      leftToRight: false,
      reduce: 0
    });
  });

  var xhr;
  $(".items .slide").click(function() {
    var ref = this.id.replace('story-','');
    if(xhr !== undefined) { xhr.abort(); }
    $(".slide").removeClass("selected");
    $(this).addClass("selected");

    var panel = $("#" + ref);
    if (panel.length > 0) {
      switch_panel(panel);
      return false;
    }

    xhr = $.ajax({
      url: '/ajax-story.php?full=true&id=' + ref,
      success: function(data) {
        panel = $("<div id=\"show-" + ref + "\" class=\"panel\" />").html(innerShiv(data,false)).appendTo("#show_panels");
        clip_captions(panel);
        switch_panel(panel);
      }
    });
    return false;
  });
  $(".items .slide").eq(0).click();

<? }

////////////////////////////////////////////////////////////////////////////////

add_action('init','init_templates');
function init_templates() {
  // Create a "template" type for e-mail notifications
  register_post_type( 'templates' , array(
    'labels' => array(
      'name' => _x('Templates','general name'),
      'singular_name' => _x('Template','singular name'),
      'add_new' => _x('Add New', 'template'),
      'add_new_item' => __('Add New Template'),
      'edit_item' => __('Edit Template'),
      'new_item' => __('New Template'),
      'view_item' => __('View Template'),
      'search_items' => __('Search Templates'),
      'not_found' => __('No Templates Found'),
      'not_found_in_trash' => __('No Templates Found in Trash'),
      'parent_item_colon' => ''
    ),
    'public' => false,
    'menu_position' => 6,
    'publicly_queryable' => true,
    'show_ui' => true,
    'capability_type' => 'page',
    'hierarchical' => true,
    'rewrite' => true,
    'query_var' => 'template',
    'register_meta_box_cb'=>'init_template_metabox',
    'supports' => array('title', 'editor', 'custom-fields', 'page-attributes')
  ));
}

////////////////////////////////////////////////////////////////////////////////

function init_template_metabox() {
  global $post;
  global $current_user;
  
  get_currentuserinfo();
  add_meta_box ('subject', 'Subject', 'draw_metabox_field', 'templates', 'normal', 'high', array('name'=>'template_subject','type'=>'text'));
  add_meta_box ('note', 'Note', 'draw_metabox_field', 'templates', 'normal', 'high', array('name'=>'template_note','type'=>'textarea'));
  add_meta_box ('css', 'CSS', 'draw_metabox_field', 'templates', 'normal', 'high', array('name'=>'template_css','type'=>'text'));
  add_meta_box ('featured_post', 'Featured Post', 'draw_metabox_field', 'templates', 'normal', 'high', array('name'=>'template_featured_post','type'=>'text'));
  add_meta_box ('featured_gifts', 'Featured Gifts', 'draw_metabox_field', 'templates', 'normal', 'high', array('name'=>'template_featured_gifts','type'=>'text'));
  add_meta_box ('gifts_count', 'Gifts Count', 'draw_metabox_field', 'templates', 'normal', 'high', array('name'=>'template_gifts_count','type'=>'text'));
  
  if(strpos($post->post_name,'invite')!==FALSE
    || strpos($post->post_name,'update')!==FALSE) { // sample email currently working on invite templates only
	$context='invite=';  
	if(strpos($post->post_name,'profile')!==FALSE) {
	  $context.='profile/';
	  if(strpos($post->post_name,'my')!==FALSE)
		$context.=1;
	  else if(strpos($post->post_name,'any')!==FALSE)
		$context.=4;
	} else if(strpos($post->post_name,'update')!==FALSE) {
	  $context.='update/';
	  $context.='clubcorp';
	} else if(strpos($post->post_name,'campaign')!==FALSE) {
	  $context.='campaign/';
	  if(strpos($post->post_name,'my')!==FALSE)
		$context.=get_campaign_for_user($current_user->ID);
	  else if(strpos($post->post_name,'any')!==FALSE)
		$context.='clubcorp';
	} else if(strpos($post->post_name,'thankyou')!==FALSE) {
	  $context.='thankyou/1';
	}

    add_meta_box ('sample', 'Sample', 'draw_metabox_field', 'templates', 'normal', 'high', array('name'=>$context,'type'=>'email_sample'));
  } 
}

function draw_metabox_field($post, $metabox) {
  global $post_type_template_nonce;
  if(empty($post_type_template_nonce)) {
    echo '<input type="hidden" name="syi_metabox_nonce" value="'.
      wp_create_nonce('syi_metabox').'" />';
    $post_type_template_nonce = 1;
  }
  $name = $metabox['args']['name'];
  $value = stripslashes(htmlentities(strval(get_post_meta($post->ID, $name, 1))));
  echo draw_post_field($metabox['args']['type'], $name, $value, '', $metabox['args']['label']);
}

function draw_post_field($type, $name, $value='', $title='', $label='') {
  $return = "";

  if ($type=='checkbox') {
    $return .= '<input type="checkbox" name="'.$name.'" value="1" '.($value==1?'checked="checked"':'').' />'.$label;
  } else if ($type=='text') {
    $return .= '<input style="width:270px;" type="text" name="'.$name.'" value="'.$value.'" />';
  } else if ($type=='textarea') {
    $return .= '<textarea rows="5" cols="50" name="'.$name.'">'.$value.'</textarea>';
  } else if ($type=='email_sample') {
    $return .= '<p><iframe style="width:800px; height:750px;" src="/payments/sample.php?'.$name.'"></iframe>';
  }
  if (!empty($title)) {
    $return = '<p style="clear:both;"><label style="height:23px;margin:3px 0;float:left; width:150px;">'.$title .'</label> '. $return . '</p>';
  }  
  return $return;
}

add_action('save_post', 'save_template');
add_action('publish_post', 'save_template');

function save_template($post_ID) {
  $post = get_post($post_ID);
  if ($post->post_type == 'templates') {
    if (wp_verify_nonce($_POST['syi_metabox_nonce'], 'syi_metabox')) {
      update_post_meta($post_ID,'template_gifts_count',$_POST['template_gifts_count']);
      update_post_meta($post_ID,'template_featured_gifts',$_POST['template_featured_gifts']);
      update_post_meta($post_ID,'template_featured_post',$_POST['template_featured_post']);
      update_post_meta($post_ID,'template_css',$_POST['template_css']);
      update_post_meta($post_ID,'template_note',$_POST['template_note']); 
      update_post_meta($post_ID,'template_subject',$_POST['template_subject']);
    }
  }
}

add_filter( 'wp_default_editor', 'template_default_editor' );
function template_default_editor( $type ) {
  global $post_type;
  if('templates' == $post_type)
    return 'html';
  return $type;
}

add_filter('bp_core_pre_avatar_handle_upload','template_pre_avatar_handle_upload',10,3);

function template_pre_avatar_handle_upload($val, $file, $upload_dir_filter) {
	global $bp;
	
	require_once( ABSPATH . '/wp-admin/includes/image.php' );
	require_once( ABSPATH . '/wp-admin/includes/file.php' );

	$uploadErrors = array(
		0 => __("There is no error, the file uploaded with success", 'buddypress'),
		1 => __("Your image was bigger than the maximum allowed file size of: ", 'buddypress') . size_format(BP_AVATAR_ORIGINAL_MAX_FILESIZE),
		2 => __("Your image was bigger than the maximum allowed file size of: ", 'buddypress') . size_format(BP_AVATAR_ORIGINAL_MAX_FILESIZE),
		3 => __("The uploaded file was only partially uploaded", 'buddypress'),
		4 => __("No file was uploaded", 'buddypress'),
		6 => __("Missing a temporary folder", 'buddypress')
	);

	if ( !bp_core_check_avatar_upload( $file ) ) {
		bp_core_add_message( sprintf( __( 'Your upload failed, please try again. Error was: %s', 'buddypress' ), $uploadErrors[$file['file']['error']] ), 'error' );
		return false;
	}

	if ( !bp_core_check_avatar_size( $file ) ) {
		bp_core_add_message( sprintf( __( 'The file you uploaded is too big. Please upload a file under %s', 'buddypress'), size_format(BP_AVATAR_ORIGINAL_MAX_FILESIZE) ), 'error' );
		return false;
	}

	if ( !template_check_avatar_type( $file ) ) {
		bp_core_add_message( __( 'Please upload only JPG, GIF or PNG photos.', 'buddypress' ), 'error' );
		return false;
	}

	/* Filter the upload location */
	add_filter( 'upload_dir', $upload_dir_filter, 10, 0 );

	$bp->avatar_admin->original = wp_handle_upload( $file['file'], array( 'action'=> 'bp_avatar_upload' ) );

	/* Move the file to the correct upload location. */
	if ( !empty( $bp->avatar_admin->original['error'] ) ) {
		bp_core_add_message( sprintf( __( 'Upload Failed! Error was: %s', 'buddypress' ), $bp->avatar_admin->original['error'] ), 'error' );
		return false;
	}

	/* Get image size */
	$size = @getimagesize( $bp->avatar_admin->original['file'] );

	/* Check image size and shrink if too large */
	if ( $size[0] > BP_AVATAR_ORIGINAL_MAX_WIDTH ) {
		$thumb = wp_get_image_editor( $bp->avatar_admin->original['file'] );

		/* Check for thumbnail creation errors */
		if ( is_wp_error( $thumb ) ) {
			bp_core_add_message( sprintf( __( 'Upload Failed! Error was: %s', 'buddypress' ), $thumb->get_error_message() ), 'error' );
			return false;
		}

		/* Thumbnail is good so proceed */
    $resize = $thumb->resize(BP_AVATAR_ORIGINAL_MAX_WIDTH, null);
    if ($resize !== false) {
      $thumb->save($bp->avatar_admin->original['file']);
    }
    else {
      bp_core_add_message( "failed to resize thumbnail" );
    }
	}

  $bp->avatar_admin->image->dir = str_replace( BP_AVATAR_UPLOAD_PATH, '', $bp->avatar_admin->original['file'] );
	$bp->avatar_admin->image->url = BP_AVATAR_URL . $bp->avatar_admin->image->dir;

	return false;	
}

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


function template_check_avatar_type ($file) {
	if ( ( !empty( $file['file']['type'] ) 
	  && !preg_match('/(jpe?g|gif|png)$/i', $file['file']['type'] ) ) 
	  || !preg_match( '/(jpe?g|gif|png)$/i', $file['file']['name'] ) )
		return false;
	return true;	
}


add_shortcode('campaign_img','draw_campaign_img');

function draw_campaign_img($atts) {

  $p = get_post($atts['id']);
  
  return '<a href="'.$p->guid.'">'.get_the_post_thumbnail($atts['id'], array(150,150)).'</a>';
  //return print_r($p,true);
//  return print_r($atts,true);
}

function post_admin_bar() {
  if (current_user_can('level_10')) {
   ?><section class="entry-utility"><?
     if (!is_page()) {
       ?><p><strong>Donors: </strong><?=show_donation_details();?></p><?
     }
     ?><strong>Admin:</strong><?
     if ( count( get_the_category() ) > 0 ) {
       ?>
         <span class="cat-links">
           <?php printf( __( '<span class="%1$s">Posted in</span> %2$s', $theme_name ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
         </span>
         <span class="meta-sep">|</span>
       <?
     }
     $tags_list = get_the_tag_list( '', ', ' );
     if ( $tags_list ) {
       ?>
         <span class="tag-links">
           <?php printf( __( '<span class="%1$s">Tagged</span> %2$s', $theme_name ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
         </span>
         <span class="meta-sep">|</span>
       <?
     }

     if (empty($theme_name))
       $theme_name = '';

     ?><span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', $theme_name ), __( '1 Comment', $theme_name ), __( '% Comments', $theme_name ) ); ?></span><?
     edit_post_link( __( 'Edit', $theme_name ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' );
  ?></section><?
  }
}

function plugin_mce_css( $mce_css ) {
  if ( ! empty( $mce_css ) )
    $mce_css .= ',';

  $mce_css .= content_url( 'themes/syi/article.css' );

  return $mce_css;
}

add_filter( 'mce_css', 'plugin_mce_css' );
remove_filter('wp_title', 'bp_modify_page_title');

function cart_body_class($classes) {
  $classes[] = "page-cart";
  return $classes;
}


/* Hook to the 'all' action
add_action( 'all', 'backtrace_filters_and_actions');
function backtrace_filters_and_actions() {
  // The arguments are not truncated, so we get everything
  $arguments = func_get_args();
  $tag = array_shift( $arguments ); // Shift the tag

  // Get the hook type by backtracing
  $backtrace = debug_backtrace();
  $hook_type = $backtrace[3]['function'];

if ($hook_type != 'do_action')
  return;

  echo "<pre>";
  echo "<i>$hook_type</i> <b>$tag</b>\n";
  foreach ( $arguments as $argument )
    echo "\t\t" . htmlentities(var_export( $argument, true )) . "\n";

    echo "\n";
    echo "</pre>";
}
*/
// error_log the current function tagged with "alex:"
function trace_this() {
  $frames = debug_backtrace();
  $f = $frames[1];
  error_log(sprintf("alex: trace_this: %s %s", $f['function'], print_r($f['args'],1)));
}
  
// error_log a stack backtrace
// (this is meant to be a soft "throw new Exception($message)" mechanism)
function trace_up($message=null) {
  if ($message) {
    error_log('trace_up: ' . $message);
  }
  $frames = debug_backtrace();
  $i = 0;
  foreach ($frames as $frame) {
    if (array_key_exists('file', $frame) && array_key_exists('line', $frame)) {# && $frame['file'] && $frame['line']) {
      error_log(sprintf("$i: %s (%s:%d)", $frame['function'], $frame['file'], $frame['line']));
    }
    else {
      error_log(sprintf("$i: %s (hooked)", $frame['function']));
    }
    $i++;
  }
}


// Fix image captions so that a blank, dash, etc. will cause the picture
// to be rendered with a caption frame but no caption text
add_filter( 'img_caption_shortcode', 'syi_captions', 10, 3 );
function syi_captions($a, $attr, $content = null) {
  extract(shortcode_atts(array(
    'id'  => '',
    'align' => 'alignnone',
    'width' => '',
    'caption' => ''
  ), $attr));

  $caption = trim($caption);
  if (empty($caption) || $caption == '.' || $caption == '-' || $caption == '_') {
    // Copied from default code
    if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
    return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . (10 + (int) $width) . 'px">'
    . do_shortcode( $content ) . '</div>';
  }

  return ''; // This will cause default processing to continue
}

/*
Plugin Name: R Debug
Description: Set of helper dump functions for debug.
Author: Andrey "Rarst" Savchenko
Author URI: http://www.rarst.net/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// https://raw.github.com/gist/1739714/47facceaeaef73a28ce50945cce80a9e00947c58/r-debug.php
class R_Debug {

  static function out($msg = '') {
    if (0) {
      echo $msg;
    }
    else {
      $plain = preg_replace('/<[^>]+>/', '', $msg);
      if ($plain) {
        $plain = html_entity_decode($plain);
        error_log($plain);
      }
    }
  }

  /**
   * List basic performance stats
   *
   * @param bool $visible display or only include in source
   */
  static function list_performance( $visible = false ) {

    $stat = sprintf( '%d queries in %.3f seconds, using %.2fMB memory',
      get_num_queries(),
      timer_stop( 0, 3 ),
      memory_get_peak_usage() / 1024 / 1024
    );

    self::out( $visible ? $stat : "<!-- {$stat} -->" );
  }

  /**
   * List defined constants
   *
   * @param bool|string $filter limit to matching names or values
   */
  static function list_constants( $filter = false ) {

    $constants   = get_defined_constants();

    if ( false !== $filter ) {

      $temp = array();

      foreach ( $constants as $key => $constant ) {

        if ( false !== stripos( $key, $filter ) || false !== stripos( $constant, $filter ) )
          $temp[$key] = $constant;
      }

      $constants = $temp;
    }

    ksort( $constants );
    var_dump( $constants );
  }

  /**
   * List cron entries with time remaining till next run
   */
  static function list_cron() {

    $cron  = _get_cron_array();

    self::out( '<pre>' );

    foreach ( $cron as $time => $entry ) {

      $when ='<strong>In ' . human_time_diff( $time ) . '</strong> (' .  date( DATE_RSS, $time ) . ')';
      self::out( "<br />&gt;&gt;&gt;&gt;&gt;\t{$when}<br />" );

      foreach ( array_keys( $entry ) as $function ) {

        self::out( "\t{$function}<br />" );
      }
    }

    self::out( '</pre>' );
  }

  /**
   * Output hook info
   *
   * @param string $tag hook name
   * @param array $hook hook data
   */
  static function dump_hook( $tag, $hook ) {

    ksort( $hook );

    self::out( "<pre>&gt;&gt;&gt;&gt;&gt;\t<strong>{$tag}</strong><br />" );

    foreach ( $hook as $priority => $functions ) {

      self::out( $priority );

      foreach ( $functions as $function ) {

        self::out( "\t" );

        $callback = $function['function'];

        if ( is_string( $callback ) )
          self::out( $callback );

        elseif ( is_string( $callback[0] ) )
          self::out( $callback[0] . '::' . $callback[1] );

        elseif ( is_object( $callback[0] ) )
          self::out( get_class( $callback[0] ) . '->' . $callback[1] );

        self::out( (1 == $function['accepted_args']) ? '<br />' : " ({$function['accepted_args']}) <br />" );
      }
    }

    self::out( '</pre>' );
  }

  /**
   * List hooks as currently defined
   *
   * @param bool|string $filter limit to matching names
   */
  static function list_hooks( $filter = false ) {

    global $wp_filter;

    $skip_filter = empty($filter);
    $hooks       = $wp_filter;
    ksort( $hooks );

    foreach ( $hooks as $tag => $hook ) {

      if ( $skip_filter || false !== strpos( $tag, $filter ) )
        self::dump_hook( $tag, $hook );
    }
  }

  /**
   * Enable live listing of hooks as they run
   *
   * @param bool|string $hook limit to matching names
   */
  static function list_live_hooks( $hook = false ) {

    if ( false === $hook )
      $hook = 'all';

    add_action( $hook, array( __CLASS__, 'list_hook_details' ), - 1 );
  }

  /**
   * Handler for live hooks output
   *
   * @param mixed $input
   * @return mixed
   */
  static function list_hook_details( $input = NULL ) {

    global $wp_filter;

    $tag = current_filter();

    if ( isset($wp_filter[$tag]) )
      self::dump_hook( $tag, $wp_filter[$tag] );

    return $input;
  }

  /**
   * List active plugins
   */
  static function list_plugins() {

    var_dump( get_option( 'active_plugins' ) );
  }

  /**
   * List performed MySQL queries
   */
  static function list_queries() {

    global $wpdb;

    if ( ! defined( 'SAVEQUERIES' ) || ! SAVEQUERIES ) {

      trigger_error( 'SAVEQUERIES needs to be defined', E_USER_NOTICE );
      return;
    }

    $queries = $wpdb->queries;

    self::out( '<pre>' );

    foreach ( $queries as $query ) {

      list($request, $duration, $backtrace) = $query;
      $duration  = sprintf( '%f', $duration );
      $backtrace = trim( array_pop( explode( ',', $backtrace ) ) );

      if ( 'get_option' == $backtrace ) {

        preg_match_all( '/\option_name.*?=.*?\'(.+?)\'/', $request, $matches );
        $backtrace .= "({$matches[1][0]})";
      }

      self::out( "<br /><strong>{$request}</strong><br />{$backtrace} in {$duration}s<br />" );
    }

    self::out( '</pre>' );
  }
}
