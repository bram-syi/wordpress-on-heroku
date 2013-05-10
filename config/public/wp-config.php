<?php

/** Enable W3 Total Cache **/
define('WP_CACHE', false); //Added by WP-Cache Manager

define( 'WPCACHEHOME', '/home/digvijay/SeeYourImpact.org/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('CONCATENATE_SCRIPTS', false );

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define("DB_NAME", trim($url["path"], "/"));
define("DB_USER", trim($url["user"]));
define("DB_PASSWORD", trim($url["pass"]));
define("DB_HOST", trim($url["host"]));
define("DB_CHARSET", "utf8");
define("DB_COLLATE","");
define('VHOST', 'yes'); 
$base = '/';

define('DISABLE_WP_CRON',true);

// Change each KEY to a different unique phrase.  You won't have to remember the phrases later,
// so make them long and complicated.  You can visit http://api.wordpress.org/secret-key/1.1/
// to get keys generated for you, or just make something up.  Each key should have a different phrase.
define('AUTH_KEY', 'ce4f45c95c76b3d546ad8ad591a02c1609f0362e3419fee5aeae2599d0f771ff'); // Change this to a unique phrase.
define('SECURE_AUTH_KEY', '966d56b5e50d5038d29c3744c062105df12966719c33ea05fc48896c798a410f'); // Change this to a unique phrase.
define('SECURE_AUTH_SALT', '16e611c6411b3c167579c2c2a691a6f888b7a523114aa0e9b0d50334db4b181a'); // Change this to a unique phrase.
define('LOGGED_IN_KEY', '0e003c4d4c0fbf12e3a84a946c31bdc0b8d7ee61da63b123688b01f4a5788fe8'); // Change this to a unique phrase.
define('SECRET_KEY', 'f68edb199ccf9f62741d867f95af5918703cb42d85ea6c844f74ca39c063ec0d'); // Change these to unique phrases.
define('SECRET_SALT', 'fff7869dc6d7559a8c5a17221d487b772a2a11d038d67ee763e0926f46f624f3');
define('LOGGED_IN_SALT', '6329e6bbcae89e9b3206c3134c92c0dfe4a086f3b09bd38099ce4df37a34ac3a');

// Uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
//define( 'NOBLOGREDIRECT', 'http://seeyourimpact.org/' );

// double check $base
if( $base == 'BASE' )
	die( 'Problem in wp-config.php - $base is set to BASE when it should be the path like "/" or "/blogs/"! Please fix it!' );
// You can have multiple installations in one database if you give each a unique prefix
$table_prefix  = 'wp_';   // Only numbers, letters, and underscores please!

// Change this to localize WordPress.  A corresponding MO file for the
// chosen language must be installed to wp-content/languages.
// For example, install de.mo to wp-content/languages and set WPLANG to 'de'
// to enable German language support.
define ('WPLANG', '');

// Temporary fix while I figure out why it's not auto-prepending the dot, and just using the domain
define ('COOKIE_DOMAIN', '.dev-syi.herokuapp.com');

// uncomment this to enable wp-content/sunrise.php support
// define( 'SUNRISE', 'on' );

// Uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
//define( 'NOBLOGREDIRECT', 'http://seeyourimpact.org/' );

define( "WP_USE_MULTIPLE_DB", false );
define( 'NONCE_KEY', 'MiWYYWT8an4WA2hvQLv)mSMW' );
define( 'AUTH_SALT', 'tOSPDb)Nw3YZ*6FhomYeH%UE' );
define( 'BP_ENABLE_USERNAME_COMPATIBILITY_MODE', true );
define( 'BP_DISABLE_ADMIN_BAR', true );
define( 'BP_SILENCE_THEME_NOTICE', true );
define( 'NONCE_SALT', 'gfzD?2Lxt)3txA)@}[_cx[-;z/0BrnC.CVGU|u`yo||R#if/eoD8&WlwzK#nHWY*' );
define( 'FORCE_SSL_LOGIN', false );

define('WP_ALLOW_MULTISITE', true);

// Uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
//define( 'NOBLOGREDIRECT', 'http://seeyourimpact.org/' );

/* That's all, stop editing! Happy blogging. */

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
?>