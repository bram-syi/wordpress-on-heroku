<?

// Load SYI custom functionality first

function stopwatch() {
  global $timestart; // Wordpress global timer
  return microtime(TRUE) - $timestart;
}
function stopwatch_comment($cmt = "") {
  $cmt = str_replace(WP_CONTENT_DIR.'/','', $cmt);

  if (function_exists('get_num_queries'))
    $q = get_num_queries();
  $m = round(memory_get_usage()/1000000, 2);
  echo "<!-- [[" . round(stopwatch(),3) . "s {$q}q {$m}m]]  $cmt -->\n";
}
function stopwatch_debug_hook($hook, $fn) {
  $hook = current_filter();
  if (!has_action($hook, 'stopwatch_hook'))
    return;

  if (is_array($fn)) {
    $fn = "::{$fn[1]}";
  }

  stopwatch_comment("hook $hook $fn");
}

function stopwatch_hook() {
  $hook = current_filter();

  global $wp_filter;
  $hooks = $wp_filter[$hook];

  $hooked = array();
  foreach ($hooks as $pri=>$v) {
    foreach ($v as $h => $fn) {
      if ($h == 'stopwatch_hook')
        continue;
      $hooked[] = "{$h}:{$pri}";
    }
  }

  $hooked = implode(' ', $hooked);
  if (!empty($hooked))
    $hooked = "($hooked)";

  stopwatch_comment("hook $hook $hooked");
}

function syi_siteurl_filter($siteurl) {
  if( defined("SITEURL_PATTERN") && defined("SITEURL_REPLACE")) {
    $new = preg_replace(constant("SITEURL_PATTERN", constant("SITEURL_REPLACE"),$siteurl));
    echo "<!-- [[filtering siteurl $siteurl to $new]] -->";
    return $new;
  }
  else {
    return $siteurl;
  }
}

stopwatch_comment('init');
add_action('wp', 'stopwatch_hook');
add_action('wp_loaded', 'stopwatch_hook');
add_action('init', 'stopwatch_hook');
add_action('setup_theme', 'stopwatch_hook');
add_action('plugins_loaded', 'stopwatch_hook');
add_action('muplugins_loaded', 'stopwatch_hook');
add_action('sanitize_comment_cookies', 'stopwatch_hook');
add_action('head', 'stopwatch_hook');
add_filter("option_siteurl","syi_siteurl_filter");


define('SYI_PATH', ABSPATH . 'syi/');
