<?
// http://wordpress.stackexchange.com/questions/5896/outputting-canonical-resource-urls-across-a-multisite-network

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

function normalize_resource_url($url = NULL) {
  global $blog_id;

  if ($blog_id == 1)
    return $url;

  if (is_multisite()) {
    $url = str_replace(SUBSITE_URL, SITE_URL, $url);
    return $url;

    $norm_url = preg_replace('/(http|https):/','', SITE_URL);
    $site_url = SUBSITE_URL;
    $protocol = ( 'on' == strtolower( $_SERVER['HTTPS' ] ) ) ? 'https:' : 'http:';

    if (empty($url))
      return $norm_url;

    if (startsWith($url, '//') || (!startsWith($url, 'htt') && !startsWith($url, '/')))
      return $url;

    $url = str_replace($site_url, '', $url);
    return $url;
  }
}

function normalize_base_urls() {
  global $blog_id;

  $base_url = get_site_url(1); //normalize_resource_url();

  $GLOBALS['wp_scripts']->base_url = $base_url;
  $GLOBALS['wp_styles']->base_url = $base_url;
}
function yes_die($arg = NULL) {
  die();
}

// When not minifying, try to reduce the number of hits to same scripts across partners
if (!is_plugin_active('wp-minify/wp-minify.php')) {
  add_filter('plugins_url','normalize_resource_url');
  add_filter('style_loader_src','normalize_resource_url');
  add_filter('init','normalize_base_urls',100); // Late in hook
  add_filter('stylesheet_directory_uri', 'normalize_resource_url');
  add_filter('template_directory_uri', 'normalize_resource_url');
  /* add_filter('bloginfo_url','normalize_resource_url'); */
}

add_action( 'admin_init', 'remove_admin_pages');
function remove_admin_pages() {
}


// REMOVE "ver" param string & let the CDN handle it
function rssv_scripts() {
  global $wp_scripts;
  if ( !is_a( $wp_scripts, 'WP_Scripts' ) )
    return;
  foreach ( $wp_scripts->registered as $handle => $script )
    $wp_scripts->registered[$handle]->ver = null;
}

function rssv_styles() {
  global $wp_styles;
  if ( !is_a( $wp_styles, 'WP_Styles' ) )
    return;
  foreach ( $wp_styles->registered as $handle => $style )
    $wp_styles->registered[$handle]->ver = null;
}

add_action( 'wp_print_scripts', 'rssv_scripts', 100 );
add_action( 'wp_print_footer_scripts', 'rssv_scripts', 100 );

add_action( 'admin_print_styles', 'rssv_styles', 100 );
add_action( 'wp_print_styles', 'rssv_styles', 100 );

function add_chrome_nocache($headers) {
  $headers['Vary'] = '*';
  $headers['Cache-Control'] = 'no-store, ' . $headers['Cache-Control'];
  return $headers;
}

add_filter( 'nocache_headers', 'add_chrome_nocache' );



function get_blog_domain($blog_id) {
  $info = get_blog_details( (int) $blog_id, false );
  $domain = explode('.', $info->domain);
  return $domain[0];
}

function syi_multisite_init() {
  if (defined('SITE_URL'))
    return;

  global $blog_id;

  $url1 = get_site_url(1);
  $url2 = get_site_url($blog_id);

  define('SITE_URL', $url1);
  define('SUBSITE_URL', $url1);
  define('IS_LIVE_SITE', $url1 == "http://seeyourimpact.org" || $url1 == "https://seeyourimpact.org");
  define('IS_STAGING_SITE', $url1 == "http://charity.seeyourimpact.com" || $url1 == "https://charity.seeyourimpact.com");
  define('IS_DEV_SITE', !IS_LIVE_SITE && !IS_STAGING_SITE);
  define('THUMBNAILER_URL', $url1 . '/wp-content/images/');
  define('STATIC_URL', $url1);
}
add_action('init', 'syi_multisite_init', -10);

function is_live_site() {
  return IS_LIVE_SITE;
}

/*
global $blog_id;
// PATCH the ossdl filter that comes with WP-SuperCache
if ($blog_id > 1)
  add_filter( 'wp_cache_ob_callback_filter', 'scossdl_off_filter2', 10);
function scossdl_off_filter2($content) {
  global $ossdl_off_blog_url;

  // It has already done a subsite replacement.
  // Now do a mainsite replacement.
  $old = $ossdl_off_blog_url;
  $ossdl_off_blog_url = get_blog_option(1, 'siteurl');
  // TODO: call the WP-SuperCache filer again
  $ossdl_off_blog_url = $old;

  return $content;
}
*/


function config_wp_less($wpless) {
  $wpless->getConfiguration()->setUploadDir(WP_CONTENT_DIR . '/cache/wp-less');
  $wpless->getConfiguration()->setUploadUrl(WP_CONTENT_URL . '/cache/wp-less');
}
add_action('wp-less_init', 'config_wp_less');


// Add the network menu so we don't need to load the admin bar
add_action('admin_menu', 'add_network_menu', 101);
function add_network_menu(){
  global $blog_id;

  if ($blog_id != 1)
    return;

  add_submenu_page('index.php', 'Network', 'Network', 'manage_network',
    'network');
}
remove_action('init', '_wp_admin_bar_init');
remove_action('wp_head', '_admin_bar_bump_cb');
remove_action( 'wp_head', 'wp_admin_bar_header' );
remove_action( 'admin_head', 'wp_admin_bar_header' );

//removing viper video junk
add_action('init','remove_viper_junk');

function remove_viper_junk() {
  global $VipersVideoQuicktags;
  if($VipersVideoQuicktags->version == '6.3.1') {
  remove_action('wp_head',array(&$VipersVideoQuicktags,'Head'));
  remove_action('admin_head',array(&$VipersVideoQuicktags,'Head'));
  add_action('wp_head','viper_replacement');
  add_action('admin_head','viper_replacement');
  }
}

function viper_replacement() {
  global $VipersVideoQuicktags;
  $VipersVideoQuicktags->wpheadrun = TRUE;
  echo "\n<style type=\"text/css\">\n";
  $aligncss = str_replace( '\n', ' ', $VipersVideoQuicktags->cssalignments[$VipersVideoQuicktags->settings['alignment']] );
  $standardcss = $VipersVideoQuicktags->StringShrink( $VipersVideoQuicktags->standardcss );
  echo strip_tags( str_replace( '/* alignment CSS placeholder */', $aligncss, $standardcss ) );
  // WPMU can't use this to avoid them messing with the theme
  if ( empty($wpmu_version) )
    echo ' ' . strip_tags( $VipersVideoQuicktags->StringShrink( $VipersVideoQuicktags->settings['customcss'] ) );
  echo "\n</style>\n";
  ?>
  <script type="text/javascript">
  // <![CDATA[
      var vvqflashvars = {};
      var vvqparams = { wmode: "opaque", allowfullscreen: "true", allowscriptaccess: "always" };
      var vvqattributes = {};
      var vvqexpressinstall = "<? echo plugins_url('/vipers-video-quicktags/resources/expressinstall.swf'); ?>";
  // ]]>
  </script>
  <?
}

// Cause OSSDLCDN, CEVHERSHARE, WP-MINIFY, IMAGEMAGICK-ENGINE
//  to get options from site 1
add_filter('pre_option_ossdl_off_cdn_url', 'get_main_blog_option');
add_filter('pre_option_ossdl_off_include_dirs', 'get_main_blog_option');
add_filter('pre_option_ossdl_off_exclude', 'get_main_blog_option');
add_filter('pre_option_ossdl_cname', 'get_main_blog_option');
add_filter('pre_option_wp_minify', 'get_main_blog_option');
add_filter('pre_option_ime_options', 'get_main_blog_option');
function get_main_blog_option() {
  global $blog_id;
  if ($blog_id == 1)
    return FALSE;

  $opt = str_replace('pre_option_', '', current_filter());
  return get_blog_option(1, $opt);
}

add_action('init', 'update_ossdl_off_blog_url', -20);
function update_ossdl_off_blog_url() {
  global $ossdl_off_blog_url;
  $ossdl_off_blog_url = SITE_URL;
}

// Update WP-MINIFY so that it uses main site URLs
add_filter('wp_minify_content', 'update_wp_minify_content');
add_filter('wp_minify_change_url', 'update_wp_minify_content');
function update_wp_minify_content($buffer) {
  global $blog_id;

  if ($blog_id > 1) {
    $site = get_option('siteurl');
    $buffer = str_replace(get_option('siteurl') . "/wp-content/plugins/wp-minify", SITE_URL . "/wp-content/plugins/wp-minify", $buffer);
  }

  return $buffer;
}


define('COPY_PERMISSIONS', TRUE);
if (COPY_PERMISSIONS) {

  // Users who have some sort of admin access on site 1 have it 
  // on any site that is checking capabilities
  function multiuser_capability_filter($x, $id, $key, $single) {

    // Don't check for administrator
    if ($id <= 1 || !$single)
      return;

    // This only runs for wp_{X}_capabilities
    $matches = array();
    if (!preg_match("/wp_(\d+)_capabilities/", $key, $matches))
      return;

    // Don't do this for blog 1 (no need plus it would be a loop)
    $bid = $matches[1];
    if ($bid <= 1)
      return;

    // Get the site 1 capabilities
    $caps = get_metadata('user', $id, "wp_1_capabilities", TRUE);
    if (empty($caps) || count($caps) == 0)
      return;

    // Just a subscriber?  Let the subsite override
    if (isset($caps['subscriber']))
      return;

    // Otherwise site 1 overrides subsite
    return array($caps);
  }
  add_filter('get_user_metadata', 'multiuser_capability_filter', 0, 4);


  // Users who have some sort of admin access on site 1 have it 
  // across all sites
  function multiuser_blogs_filter($blogs, $user_id, $all) {

    // Don't do this for admin, it would be a loop
    if ($user_id <= 1)
      return $blogs;

    // Don't do this for anyone but the current user
    if ($user_id != get_current_user_id())
      return $blogs;

    if (current_user_can_for_blog(1, 'level_2'))
      return get_blogs_of_user(1, $all);
  }
  add_filter('get_blogs_of_user', 'multiuser_blogs_filter', 0, 3);

}
