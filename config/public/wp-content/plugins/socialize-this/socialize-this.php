<?php

/**
    * @package Socialize This
*/
/*
Plugin Name: Socialize This
Plugin URI: https://github.com/MikeRogers0/Socialize-This
Description: Adds social widgets to your blog posts. It also can update your twitter status when you publish a post. Uses Twitter API version 1.1.
Version: 2.3.1
Author: Mike Rogers
Author URI: http://www.fullondesign.co.uk/
Text Domain: st_plugin
License: GPLv2
*/

/*
Copyright 2011 Mike Rogers - http://www.fullondesign.co.uk/

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Define some constants
define('ST_FILE', plugin_basename(__FILE__));
define('ST_VERSION', '2.3.1');
$st_folder = explode('socialize-this.php', __FILE__);
define('ST_FOLER', $st_folder[0]);
unset($st_folder);

load_theme_textdomain('st_plugin', ST_FOLER . '/locale');

// Start the plugin
require('inc/twitter.class.php');
require('inc/bitly.class.php');
include ('inc/socializeapis.class.php');
require('inc/socialize-this-php5-enviroment.php');
require('inc/st.func.php'); // Uncomment this to add some fun ST functions.
$ql = new socialize_this();

// Add the show_social_widgets function.
if (!function_exists('show_social_widgets')) {
    function show_social_widgets($widgets=NULL, $post_id=NULL, $permalink=NULL, $title=NULL) { // Use this to show custom widgets.
        global $ql;
        $ql->show_social_widgets($widgets, $post_id, $permalink, $title);
    }
}
if (!function_exists('st_show_widget')) {
    function st_show_widget($widgets) { // Use this to show a custom widget.
        global $ql;
        $ql->show_social_widgets($widgets, FALSE);
    }
}

// Add the cron function.
if (!function_exists('updateSocialized')) {
    function updateSocialized() {
        global $ql;
        $ql->updateSocialized();
    }
    add_action('updateSocialized', 'updateSocialized');
}

// The install/uninstall directorys.
register_activation_hook(ST_FILE, array($ql, 'st_install'));
register_deactivation_hook(ST_FILE, array($ql, 'st_uninstall'));
