<?php
/**
 * Plugin Name: Technorati Full Feeds
 *
 * Description: Overrides the Summary/Full Text option when Technorati fetches your feeds, and forces Full Text mode.
 *
 * This plugin adds a row to the options table that is removed when deactivated.
 *
 * Plugin URI: http://www.miqrogroove.com/pro/software/
 * Author URI: http://www.miqrogroove.com/
 *
 * @author: Robert Chapin (miqrogroove)
 * @version: 1.6
 * @copyright Copyright © 2009-2012 by Robert Chapin
 * @license GPL
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/* Plugin Bootup */

if (!function_exists('get_bloginfo')) {
    header('HTTP/1.0 403 Forbidden');
    exit("Not allowed to run this file directly.");
}

add_filter('option_rss_use_excerpt', 'technorati_full_feeds'); // See wp-includes/feed-rss2.php and wp-includes/option.php
add_filter('plugin_row_meta', 'technorati_show_lastvisit', 10, 2); // See wp-admin/includes/class-wp-plugins-list-table.php
register_activation_hook(__FILE__, 'technorati_ff_enable');
register_deactivation_hook(__FILE__, 'technorati_ff_disable');


/* Plugin Functions */

/**
 * Determine if the current feed fetcher is Technorati.
 *
 * @param string $use_excerpt The value of the rss_use_excerpt option.
 * @returns string '0' when Technorati, otherwise returns param unmodified.
 */
function technorati_full_feeds($use_excerpt) {

    if (isset($_SERVER['REMOTE_ADDR'])) {
        // Technorati's official bot address is 208.66.64.0/22
        $min  = ip2long('208.66.64.0');
        $max  = ip2long('208.66.67.255');
        $host = ip2long($_SERVER['REMOTE_ADDR']);
        if ($host >= $min and $host <= $max) {
            if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', TRUE);
            technorati_update_lastvisit();
            $use_excerpt = '0';
        }
    }

    return $use_excerpt;
}

/**
 * Save the Technorati time stamp.
 *
 * @since 1.3
 */
function technorati_update_lastvisit() {
    $lastvisit = get_option('technorati_lastvisit');
    if (FALSE === $lastvisit) {
        add_option('technorati_lastvisit', time(), '', 'no');
    } elseif ($lastvisit < time() - 300) {
        update_option('technorati_lastvisit', time());
    } // else save a query.
}

/**
 * Display the Technorati time stamp.
 *
 * @since 1.3
 * @param array $plugin_meta The strings prepared for display before the filter.
 * @param string $plugin_file The internal name of the plugin being filtered.
 * @returns array of strings to be displayed, with the time stamp added.
 */
function technorati_show_lastvisit($plugin_meta, $plugin_file) {
    if (!current_user_can('activate_plugins')) wp_die('Unexpected permissions fault in the Technorati Full Feeds Plugin.');
    if ($plugin_file == plugin_basename(__FILE__)) {
        $lastvisit = get_option('technorati_lastvisit');
        if (FALSE !== $lastvisit) {
            $diff = time() - $lastvisit;
            $mins = ceil($diff / 60);
            if ($mins < 360) {
                $time = (1 == $mins) ? 'one minute' : "$mins minutes";
            } elseif ($mins < 1440) {
                $hours = ceil($mins / 60);
                $time = "$hours hours";
            } else {
                $days = ceil($mins / 1440);
                $time = "$days days";
            }
            $plugin_meta[] = "Technorati last seen $time ago.";
        } else {
            $plugin_meta[] = 'Technorati has not visited since activation.';
        }
    }
    return $plugin_meta;
}

/**
 * Delete the Technorati time stamp and WP Super Cache sub-plugin.
 *
 * @since 1.3
 */
function technorati_ff_disable() {
    global $wp_cache_plugins_dir;

    if (!current_user_can('activate_plugins')) wp_die('Unexpected permissions fault in the Technorati Full Feeds Plugin.');

    delete_option('technorati_lastvisit');

    if (!empty($wp_cache_plugins_dir)) {
        $plug = 'technorati-no-super-cache.php';
        $dest = $wp_cache_plugins_dir . '/' . $plug;
        @unlink($dest);
    }
}

/**
 * Attempt to install the WP Super Cache sub-plugin
 *
 * @since 1.6
 */
function technorati_ff_enable() {
    global $wp_cache_plugins_dir;

    if (!current_user_can('activate_plugins')) wp_die('Unexpected permissions fault in the Technorati Full Feeds Plugin.');

    if (!empty($wp_cache_plugins_dir)) {
        $plug = 'technorati-no-super-cache.php';
        $source = dirname(__FILE__) . '/' . $plug;
        $dest = $wp_cache_plugins_dir . '/' . $plug;
        @copy($source, $dest);
    }
}
?>
