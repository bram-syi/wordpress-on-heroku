<?php
/*
Plugin Name: WPMU Director
Plugin URI: http://realestatetomato.com/wpmu-director
Description: Adds an extra folder for mu admin-plugins
Version: 1.0
Author: Jason Benesch
Author URI: http://realestatetomato.com/
*/

if(is_admin()) {
	if( defined( 'ADMINPLUGINDIR' ) == false ) 
		define( 'ADMINPLUGINDIR', 'wp-content/admin-plugins' );
	
	if( is_dir( ABSPATH . ADMINPLUGINDIR ) ) {
		if( $dh2 = opendir( ABSPATH . ADMINPLUGINDIR ) ) {
			while( ( $plugin = readdir( $dh2 ) ) !== false ) {
				if( substr( $plugin, -4 ) == '.php' ) {
					include_once( ABSPATH . ADMINPLUGINDIR . '/' . $plugin );
				}
			}
		}
	}
}
?>