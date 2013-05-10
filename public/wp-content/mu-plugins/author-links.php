<?php
/*
Plugin Name: BP Blog Author Profile Link 
Plugin URI: http://wpmututorials.com/
Description: If buddypress is enabled the author link on a post/page links to the author's buddypress profile.
Version: 2.8.1
Author: Ron Rennick
Author URI: http://ronandandrea.com/
 
*/
/* Copyright: (C) 2009 Ron Rennick, All rights reserved.  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function ra_add_author_filter() {
  add_filter( 'author_link', 'ra_bp_filter_author' );
} 
add_action( 'wp_head', 'ra_add_author_filter' );

function ra_bp_filter_author( $content ) {
  if( defined( 'BP_MEMBERS_SLUG' ) ) {
    if( is_multisite() ) {
      $member_url = network_home_url( BP_MEMBERS_SLUG );
      if( !is_subdomain_install() && is_main_site() )
        $extra = '/blog';
      else
        $extra = '';

      $blog_url = get_option( 'siteurl' ) . $extra . '/author';
      return str_replace( $blog_url, $member_url, $content );
    }
    return preg_replace( '|/author(/[^/]+/)|', '/' . BP_MEMBERS_SLUG . '$1' . 'profile/', $content );
  }
  return $content;
}
?>
