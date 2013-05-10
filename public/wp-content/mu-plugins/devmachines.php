<?

/* This code runs on all dev machines to turn off things that only apply
to live site */

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// There are three kinds of sites right now
//   IS_LIVE_SITE
//   IS_STAGING_SITE (charity.seeyourimpact.com only for now)
//   IS_DEV_SITE
// A particular machine will only be one of thse at a time.

// all logging is written to /home/digvijay/tmp, and logfiles are of the
// format "php-<environment>.log".
function enable_php_logging($log_file = '') {
  error_reporting(E_ALL);
  ini_set('log_errors', true);
  ini_set('display_errors', false);
  ini_set('error_log', SyiLog::php_error_logfile());
}

function set_machine_configuration() {
  // Only LIVE should be HTTPS
  if (!IS_LIVE_SITE) {
    deactivate_plugins(array(
      'wordpress-https/wordpress-https.php'
    ));
  }

  if (IS_DEV_SITE) {
    deactivate_plugins(array(
      'wp-minify/wp-minify.php',
      'wp-super-cache/wp-cache.php',
      'cloudflare/cloudflare.php',
      'hide-update-reminder/hide-update-reminder.php'
    ));
    enable_php_logging();
  }

  if (IS_STAGING_SITE) {
    enable_php_logging('php-charity.log');
  }

  if (IS_LIVE_SITE) {
    enable_php_logging('php-live.log');
  }
}
add_action('init', 'set_machine_configuration', 100);
add_action('admin_init', 'set_machine_configuration', 100);
