<?php

/*
Plugin Name: Recent Blog Posts
Plugin URI: http://atypicalhomeschool.net/wordpress-plugins/
Description: Retrieves a list of the most recent posts in a WordPress MU installation. Based on (Andrea - fill this in)
Author: Ron Rennick
Author URI: http://atypicalhomeschool.net/
*/

/*
Version: 0.31
Update Author: Sven Laqua
Update Author URI: http://www.sl-works.de/
*/

/*
Version: 0.32
Update Author: G. Morehouse
Update Author URI: http://wiki.evernex.com/index.php?title=Wordpress_MU_sitewide_recent_posts_plugin
*/

/*
Parameter explanations
$how_many: how many recent posts are being displayed
$how_long: time frame to choose recent posts from (in days)
$titleOnly: true (only title of post is displayed) OR false (title of post and name of blog are displayed)
$begin_wrap: customise the start html code to adapt to different themes
$end_wrap: customise the end html code to adapt to different themes

Sample call: ah_recent_posts_mu(5, 30, true, '<li>', '</li>'); >> 5 most recent entries over the past 30 days, displaying titles only
*/
function ah_recent_posts_mu($how_many, $how_long, $titleOnly, $begin_wrap, $end_wrap) {
	global $wpdb;
	$counter = 0;
	
	// get a list of blogs in order of most recent update. show only public and nonarchived/spam/mature/deleted
	$blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE
		public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND
		last_updated >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
		ORDER BY last_updated DESC");
	$blog = $blog_id;
	if ($blog) {
//		foreach ($blogs as $blog) {
			// we need _posts and _options tables for this to work
			$blogOptionsTable = "wp_".$blog."_options";
		    	$blogPostsTable = "wp_".$blog."_posts";
			$options = $wpdb->get_results("SELECT option_value FROM
				$blogOptionsTable WHERE option_name IN ('siteurl','blogname') 
				ORDER BY option_name DESC");
		        // we fetch the title and ID for the latest post
			$thispost = $wpdb->get_results("SELECT ID, post_title 
				FROM $blogPostsTable WHERE post_status = 'publish' 
				AND post_type = 'post' AND post_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
				ORDER BY id DESC LIMIT 0,1");
			// if it is found put it to the output
			if($thispost) {
				// get permalink by ID.  check wp-includes/wpmu-functions.php
				$thispermalink = get_blog_permalink($blog, $thispost[0]->ID);
				if ($titleOnly == false) {
					echo $begin_wrap.'<a href="'.$thispermalink
					.'">'.$thispost[0]->post_title.'</a> <br/> by <a href="'
					.$options[0]->option_value.'">'
					.$options[1]->option_value.'</a>'.$end_wrap;
					$counter++;
					} else {
						echo $begin_wrap.'<a href="'.$thispermalink
						.'">'.$thispost[0]->post_title.'</a>'.$end_wrap;
						$counter++;
					}
			}
			// don't go over the limit
			if($counter >= $how_many) { 
				break; 
			}
		//}
	}
}
?>