<?php

/** Enable W3 Total Cache **/
define('WP_CACHE', false); //Added by WP-Cache Manager

define( 'WPCACHEHOME', '/home/digvijay/SeeYourImpact.org/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('CONCATENATE_SCRIPTS', false );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define("DB_NAME", trim($url["path"], "/"));
// define("DB_NAME", "heroku_4eed1f5663e035f");

/** MySQL database username */
define("DB_USER", trim($url["user"]));
// define("DB_USER", "b7cfc877a785ae");

/** MySQL database password */
define("DB_PASSWORD", trim($url["pass"]));
// define("DB_PASSWORD", "db3717c2");

/** MySQL hostname */
define("DB_HOST", trim($url["host"]));
// define("DB_HOST", "us-cdbr-east-03.cleardb.com");

/** Database Charset to use in creating database tables. */
define("DB_CHARSET", "utf8");

/** Allows both foobar.com and foobar.herokuapp.com to load media assets correctly. */
define("WP_SITEURL", "http://" . $_SERVER["HTTP_HOST"]);

define("FORCE_SSL_LOGIN", getenv("FORCE_SSL_LOGIN") == "true");
define("FORCE_SSL_ADMIN", getenv("FORCE_SSL_ADMIN") == "true");
if ($_SERVER["HTTP_X_FORWARDED_PROTO"] == "https")
  $_SERVER["HTTPS"] = "on";

// Uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
//define( 'NOBLOGREDIRECT', 'http://seeyourimpact.org/' );

define( "WP_USE_MULTIPLE_DB", false );
define( 'NONCE_KEY', 'MiWYYWT8an4WA2hvQLv)mSMW' );
define( 'AUTH_SALT', 'tOSPDb)Nw3YZ*6FhomYeH%UE' );
define( 'BP_ENABLE_USERNAME_COMPATIBILITY_MODE', true );
define ( 'BP_DISABLE_ADMIN_BAR', true );
define( 'BP_SILENCE_THEME_NOTICE', true );
define( 'NONCE_SALT', 'gfzD?2Lxt)3txA)@}[_cx[-;z/0BrnC.CVGU|u`yo||R#if/eoD8&WlwzK#nHWY*' );
define( 'FORCE_SSL_LOGIN', false );

/* That's all, stop editing! Happy blogging. */

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
?>
