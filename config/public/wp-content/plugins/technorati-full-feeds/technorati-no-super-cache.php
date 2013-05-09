<?php
/**
 * Technorati Full Feeds Sub-Plugin for WP Super Cache
 *
 * @copyright Copyright © 2012 by Robert Chapin
 * @license GPL
 */

add_cacheaction('cache_init', 'technorati_no_wpsc');

function technorati_no_wpsc() {
    if (empty($_SERVER['REMOTE_ADDR'])) return;

    // Technorati's official bot address is 208.66.64.0/22
    $min  = ip2long('208.66.64.0');
    $max  = ip2long('208.66.67.255');
    $host = ip2long($_SERVER['REMOTE_ADDR']);
    if ($host >= $min and $host <= $max) {
        $GLOBALS['cache_enabled'] = FALSE;
        if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', TRUE);
    }
}
?>
