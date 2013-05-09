<?php
/*
Plugin Name: New Charity
Description: Automate the creation of new charity blogs
Version: 1.0
Author: Steve Eisner

Instructions: copy into mu-plugins
*/

require_once( ABSPATH . 'database/db-functions.php' );

add_action('wpmu_new_blog','nc_new_charity', 10, 2);

function nc_new_charity($blog_id, $user_id) {
	global $wpdb, $wp_rewrite, $table_prefix;
	if( !isset($wpdb->siteid) ) $wpdb->siteid = 1;

  global $NEW_CHARITY_CREATION;

	switch_to_blog($blog_id);
	$wpdb->suppress_errors();

	// Update existing home to the "Charity Home" page
	$slug = "home";
	$title = 'Welcome!';
	$body = 'Please update this page with information about SITE_NAME...';
	$homePage = db_update_page(1, $blog_id, $user_id, $title, $body, $slug, 0);

	// Update extisting about page to the "About Us" page
	$slug = "about";
	$title = 'About Us';
	$body = 'About SITE_NAME';
	$aboutPage  = db_update_page(2, $blog_id, $user_id, $title, $body, $slug, 1);

	// Create the "Stories" page
	$slug = "stories";
	$title = 'Latest Stories';
	$body = 'Here are the latest stories from SITE_NAME';
	$updatesPage = db_new_page($blog_id, $user_id, $title, $body, $slug, 4);

  if (!$NEW_CHARITY_CREATION) {
    // Create the "header" promo
    $slug = "header";
    $title = 'Charity banner';
    $body = '<div style="height:60px; padding: 20px;">(Charity Banner)</div>';
    $updatesPage = db_new_page($blog_id, $user_id, $title, $body, $slug, 0, 0, 'promo');

    // Create the "Certified Org" promo
    $slug = "certified";
    $title = 'Certified Organization';
    $body = '(Certified Organization text)';
    $updatesPage = db_new_page($blog_id, $user_id, $title, $body, $slug, 0, 0, 'promo');
  }

	// Create the "QuickFacts" promo
	$slug = "cause";
	$title = 'Quick Facts';
	$body = '<li>Quickfact 1</li><li>Quickfact 2</li><li>Quickfact 3</li>';
	$updatesPage = db_new_page($blog_id, $user_id, $title, $body, $slug, 0, 0, 'promo');

	// Turn off comments
	update_option( 'default_comment_status', false);
	update_option( 'default_ping_status', false);

	// Set up front page to show home page instead of posts
	update_option( "show_on_front", 'page' );
	update_option( "page_on_front", $homePage );
	update_option( "page_for_posts", $updatesPage );
	
	// For auto thumbnailing
	update_option( "medium_size_w", 480);
	update_option( "medium_size_h", 480);

	// Set up the admin email automatically
	$wpdb->query("UPDATE $wpdb->options SET option_value = 'admins@seeyourimpact.org' WHERE option_name = 'admin_email'");

	// copy wp_1_cevhershare -> wp_$blog_id_cevhershare
	$wpdb->query("create table wp_${blog_id}_cevhershare like wp_1_cevhershare");
    $wpdb->query("insert wp_${blog_id}_cevhershare select * from wp_1_cevhershare");

    // copy over any cevhershare options from wp_1_options
    $wpdb->query("insert wp_${blog_id}_options(option_value, autoload, option_name) select option_value, autoload, option_name from wp_1_options where option_name like 'cevhershare%'");

    // activate the cevhershare plugin for $blog_id
    $active_plugins = get_option( 'active_plugins' );
    $active_plugins[] = 'cevhershare/cevhershare.php';
    update_option( 'active_plugins', $active_plugins );

  switch_to_charity_theme();
	$wpdb->query("UPDATE $wpdb->options SET option_value = 'charity' WHERE option_name = 'stylesheet'");
	$wpdb->query("UPDATE $wpdb->options SET option_value = 'syi' WHERE option_name = 'template'");

  $details = get_blog_details($blog_id);
  $domain = explode('.', $details->domain);
  $wpdb->insert('charity', array(
    'blog_id' => $blog_id,
    'domain' => $domain[0],
    'name' => get_bloginfo('name', 'raw'),
    'url' => site_url() . "/",
    'description' => get_bloginfo('description', 'raw')
  ));

	wp_insert_category(array("cat_name"=>"Featured", "category_description"=>"Featured on home page"));
	wp_insert_category(array("cat_name"=>"Projects", "category_description"=>"Stories about the available donation options"));
	wp_insert_category(array("cat_name"=>"Success stories", "category_description"=>"Featured stories - looking back on success"));
	wp_insert_category(array("cat_name"=>"Impact stories", "category_description"=>"Watch the impact of a particular donation"));
	wp_insert_category(array("cat_name"=>"About", "category_description"=>"About the charity itself"));
	wp_insert_category(array("cat_name"=>"Community", "category_description"=>"Community stories"));

    create_homepage_category();
    disable_comments();
    reset_tweet_options();
    reset_tweet_options2();
    activate_akismet();

	kill_sharethis_mail();
    add_syi_roles();
	$wpdb->suppress_errors(false);
	restore_current_blog();
}

function remove_home_page($pages)
{
  //$pages[] = '3'; // Charity home page is always page 3
  $pages[] = '1';
  return $pages;
}

//add_filter('wp_list_pages_excludes', 'remove_home_page');
