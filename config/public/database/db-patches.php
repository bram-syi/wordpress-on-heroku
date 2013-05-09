<?php

require_once( dirname(__FILE__) . '/../wp-load.php' );
require_once( dirname(__FILE__) . '/../wp-admin/includes/taxonomy.php' );
require_once('db-functions.php');

//nocache_header();

if (! db_get_version()) 
{
   require_once('db_incremental.php');
   $wpdb->query("INSERT into version (id, version, datestamp) VALUES (null, 1, now())");
   $wpdb->query("INSERT into version_log (id, version, datestamp) VALUES (null, 1, now())");
}

function db_increment($version, $func)
{
  if (db_get_version() < $version) 
  {
     echo "Running #$version:$func on ID 1<br/>";
     $success = call_user_func($func);
     if ($success === false) {
       echo "$func stopped the upgrade";
       die();
     }
     db_set_version($version);
  }
}
function db_increment_charities($version, $func)
{
  if (db_get_version() < $version) 
  {
      global $wpdb;

      $blog_ids = $wpdb->get_results("SELECT blog_id FROM wp_blogs WHERE blog_id <> site_id");
      foreach ($blog_ids as $blog) {
          switch_to_blog($blog->blog_id);
          echo "Running #$version:$func on ID" . $blog->blog_id . "<br/>";

          call_user_func($func);
      }
      switch_to_blog(1);
      db_set_version($version);
  }
}
function db_increment_all($version, $func)
{
  if (db_get_version() < $version) 
  {
      global $wpdb;

      $blog_ids = $wpdb->get_results("SELECT blog_id FROM wp_blogs");
      foreach ($blog_ids as $blog) {
          switch_to_blog($blog->blog_id);
          echo "Running #$version:$func on ID" . $blog->blog_id . "<br/>";

          call_user_func($func);
      }
      switch_to_blog(1);
      db_set_version($version);
  }
}

db_increment(2, 'disable_comments');
db_increment(3, 'reset_tweet_options');
db_increment(4, 'create_faq_page');
//db_increment(5, 'update_thankyou_mail');  NOT NECESSARY
db_increment(6, 'add_gift_description_links');
db_increment(7, 'add_towards_gift_id');
db_increment(8, 'add_current_amount');
db_increment_charities(9, 'create_homepage_category');
db_increment(10, 'add_donation_tip');
db_increment_all(13, 'reset_tweet_options2');
db_increment_all(14, 'create_homepage_category');  // Re-do to create if it doesn't already exist
// changed 15 and removed it before prop.
db_increment(16, 'createAggregatedThankYouMail');  
//db_increment_all(18, 'create_thank_you_page');
//db_increment_charities(19, 'rename_gifts_to_projects');   NO LONGER NECESSARY
db_increment(20, 'add_donation_tip');
db_increment(21, 'add_donation_notifications');
db_increment_charities(22, 'rename_projects_to_gifts');
// At this point realized that previous "_all" function had been broken.
db_increment_all(24, 'create_thank_you_page');
db_increment_all(25, 'reset_tweet_options2');
db_increment(26, 'create_gift_campaigns');
db_increment(27, 'denormalize_donations_table');
db_increment(28, 'delete_inactive_gifts');
db_increment(29, 'add_donation_vars');
db_increment(30, 'rename_bpa');
db_increment_all(31, 'kill_sharethis_mail');
db_increment(32, 'update_donationGifts_table');
db_increment(33, 'update_donationGifts_table2');
db_increment(34, 'rename_impact_status');
db_increment_charities(35, 'import_impacts');
db_increment_charities(36, 'convert_donation_meta');
db_increment(37, 'move_donation_status_columns');
db_increment(38, 'fix_user_notifications_preference');
db_increment(39, 'update_donation_table');

////

db_increment(40, 'install_donation_account');
db_increment(41, 'install_donation_account2');
db_increment(42, 'install_donation_account3');
db_increment(43, 'upgrade_sitewide');
db_increment(44, 'upgrade_sitewide2');
db_increment(45, 'upgrade_sitewide3');
db_increment(46, 'upgrade_sitewide4');
db_increment(47, 'upgrade_sitewide5');
db_increment(48, 'create_blog_charity_info');
db_increment(49, 'upgrade_sitewide6');
db_increment(50, 'create_payment_table');
db_increment_all(51, 'activate_akismet');
db_increment(52, 'move_payments_to_table');
db_increment(53, 'upgrade_sitewide_gc');
db_increment(54, 'upgrade_payment_table');
db_increment(55, 'upgrade_donation_acct');
db_increment(56, 'add_donation_giver_notes');
db_increment(57, 'link_payment_to_acct_trans');
db_increment(58, 'upgrade_payment_table2');
db_increment(59, 'adding_littledrops_info');
db_increment(60, 'upgrade_sitewide_mg');
db_increment(61, 'upgrade_donation_acct_mg');
db_increment(62, 'upgrade_donation_gifts_mg');
db_increment(63, 'remove_donationDonors_table');
db_increment(64, 'update_notifications');
db_increment(65, 'add_test_donations');
db_increment(66, 'fix_gc_notes_length');
db_increment(67, 'fix_bug_200');
db_increment(68, 'fix_bug_200_again');
db_increment(69, 'fix_bug_200_again2');
db_increment(70, 'change_email_template');
db_increment(71, 'add_donationAcct_params');
db_increment(72, 'add_payment_txnid');
db_increment(73, 'add_donation_blogid_giftid');
db_increment(74, 'add_cartdata_emailverify_fbconnect');
db_increment(75, 'add_bloginfo_tip');
db_increment(76, 'add_thankyou_page_shortcode');
db_increment(77, 'add_donationGiver_validated');
db_increment(78, 'validate_current_user');
db_increment(79, 'upgrade_sitewide_fbconnect');
db_increment(80, 'change_thankyoupage_template');
db_increment(81, 'update_gift_variable');
db_increment(82, 'add_donationGift_tip');
db_increment(83, 'create_donationContact');
db_increment(84, 'add_notificationHistory_donationContact');
db_increment(85, 'create_featuredContent');
db_increment(86, 'add_gift_image_region');
db_increment(87, 'create_featuredGiftSet');
db_increment_all(88, 'switch_to_new_theme');
db_increment(90, 'create_donation_stories');
db_increment(91, 'copy_post_to_donation_stories');
db_increment_all(93, 'migrate_promotions');
db_increment_charities(94, 'remove_unecessary_pages');
db_increment_all(96, 'rename_updates_to_stories');
db_increment_all(97, 'set_default_gift_tags');
db_increment_all(98, 'update_page_structure');
db_increment_charities(99, 'fix_front_pages');
db_increment(100, 'fix_donation_story_guid');
db_increment(101, 'copy_post_to_donation_stories');
db_increment(102, 'create_default_articles');
db_increment(103, 'create_tab_pages');
db_increment(105, 'update_gift_tags_default_final');
db_increment(106, 'add_featured_gift_sets');
db_increment(107, 'create_default_articles2');
db_increment(108, 'default_insert_featuredContent');
db_increment(109, 'create_payment_page');
db_increment(110, 'create_payment_page_sidebar');
db_increment(111, 'create_payment_page_tipnote');
db_increment(112, 'alter_donation_story');
db_increment_all(113, 'import_gifts');
db_increment(114,'insert_default_featured_posts');
db_increment_all(115, 'insert_story_tag');
db_increment(116, 'create_impact_page');
db_increment(117, 'copy_post_to_donation_stories');
db_increment(118, 'insert_default_featured_posts');
db_increment(119, 'reset_donation_story');
db_increment_charities(120, 'reformat_charity_text');
db_increment(121, 'copy_post_to_donation_stories');
db_increment(123, 'add_donor_user_id');
db_increment(124, 'add_spreedly_sitewide_settings');
db_increment(125, 'update_txnid_forty_varchar');
db_increment(126, 'add_fbconnect_publish_template');
db_increment(127, 'untest_donations_091410');
db_increment_charities(128, 'turn_off_post_comments');
db_increment(129, 'add_recurly_sitewide_settings');
db_increment(130, 'create_donorUsername_table');
db_increment(131, 'fix_sendUpdate_after_august2010');
db_increment(132, 'create_cart_structures');
db_increment(133, 'update_cartitem_pk');
db_increment(134, 'create_cart_page');
db_increment(135, 'create_cartitemdetails');
db_increment(136, 'set_payments_email');
db_increment(137, 'update_thanks_page');
db_increment(138, 'track_error_mails');
db_increment(139, 'create_cart_debug');
db_increment(140, 'fix_zero_total_donation');
db_increment(141, 'create_signin_promos');
db_increment(143, 'add_donation_campaigns');
db_increment(144, 'add_cart_test');
db_increment(145, 'create_facebook_promos');
db_increment(147, 'add_gift_stats');
db_increment(148, 'add_gift_stats2');
db_increment(149, 'new_profile_promo');
db_increment(150, 'new_signin');
db_increment(151, 'add_dg_main_field');
db_increment(152, 'add_cash_gift');
db_increment(153, 'add_merged_thankyou_email_default_values');
db_increment(154, 'add_give_any_accounts');
db_increment(155, 'update_acct_table');
db_increment(156, 'create_donation_gift_details');
db_increment(157, 'create_donor_info_table');
db_increment(158, 'create_donor_info_table2');
db_increment(159, 'more_donor_info');
db_increment(160, 'more_donor_info2');
db_increment_all(161, 'add_smtp_settings');
db_increment(162, 'create_invite_table');
db_increment(163, 'add_invite_page_and_promos');
db_increment(164, 'add_cloudsponge_settings');
db_increment(165, 'add_invite_name');
db_increment(166, 'create_invitation_table');
db_increment(167, 'add_invite_email_templates');
db_increment(168, 'fix_invite_context');
db_increment(169, 'add_invite_email_templates'); //redo
db_increment(170, 'add_invite_email_templates'); //redo
db_increment(171, 'convert_campaign_goals');
db_increment(172, 'add_matching_to_cart');
db_increment(173, 'fix_no_story_email_notifications');
db_increment(174, 'add_invite_processing_time');
db_increment(175, 'add_thanks_message');
db_increment(176, 'add_thanks_invite');
db_increment(177, 'add_notification_blog_id');
db_increment(178, 'remove_cc_numbers_from_cart_debug');
db_increment(179, 'add_blogid_to_notificationhistory');
db_increment(180, 'fix_syi_goal_postmeta');
db_increment(181, 'add_event_id_to_donation_acct');
db_increment(182, 'update_campaign_invite_template');
db_increment(183, 'create_unsubscribed_list');
db_increment(184, 'add_cart_referrer');
db_increment(185, 'insert_campaign_update_template');
db_increment(186, 'add_invite_visited');
db_increment(187, 'fix_story_delays_aug2011');
db_increment(188, 'create_campaign_stats_table');
db_increment(189, 'create_donor_demographics');
db_increment(190, 'add_inviter_name_on_invitation');
db_increment(191, 'create_donor_info_table3');
db_increment(192, 'create_donor_info_table4');
db_increment(193, 'add_cart_item_blog_id');
db_increment(194, 'add_cart_data');
db_increment(195, 'add_cart_txn_data');
db_increment(196, 'add_tipout_gift');
db_increment(197, 'fix_merged_thankyou_email_typeid');
db_increment(198, 'fix_single_main_user_donor');
db_increment(199, 'blog_charity_info_location');
db_increment(200, 'add_campaign_public_flag');
db_increment(201, 'more_campaign_metas');
db_increment(202, 'copy_campaign_public_tag');
db_increment(203, 'copy_campaign_public_tag');
db_increment(204, 'create_pledges_table');
db_increment(205, 'more_pledge_fields');
db_increment(206, 'rename_pledge_amount');
db_increment(207, 'more_pledge_fields2');
db_increment(208, 'add_pledge_count');
db_increment(209, 'more_pledge_fields3');
db_increment(210, 'add_gc_delivery');
db_increment(211, 'create_500_donors');
db_increment(212, 'add_donor_demotag');
db_increment(213, 'insert_default_impactcard_values');
db_increment(214, 'add_donation_gift_onbehalf');
db_increment(215, 'add_tip_info_promo');
db_increment(216, 'add_dg_notes_table');
db_increment(217, 'add_recurlycc_sitewide_settings');
db_increment(218, 'add_more_campaign_columns');
db_increment(219, 'add_cart_tip_field');
db_increment(220, 'add_donor_share_email');
db_increment(221, 'add_more_campaign_columns2');
db_increment(222, 'better_dat_tracking');
db_increment(223, 'add_new_start_pages');
db_increment(224, 'add_account_expiration');
db_increment(225, 'add_campaign_first_donated');
db_increment(226, 'add_campaign_archived');
db_increment(227, 'add_default_theme_data');
db_increment(229, 'add_campaign_updates');
db_increment(230, 'create_syi_mailer_queue');
db_increment(231, 'create_campaign_teams');
db_increment_charities(232, 'switch_to_charity_theme');
db_increment(233, 'create_pratham_marathon_campaign');
db_increment(234, 'upgrade_charity_table');
db_increment_charities(235, 'upgrade_charity_table2');
db_increment(236, 'upgrade_charity_table3');
db_increment(237, 'add_reason_to_syi_mailer_queue');
db_increment(238, 'create_teton_science_school_campaign');
db_increment(239, 'add_h2o_for_readathon');
db_increment(240, 'add_offline_raised');
db_increment(241, 'create_kidsco_bbq_teams');
db_increment(242, 'add_html_body_column');
db_increment(243, 'add_charity_fundraiser');
db_increment(244, 'add_donation_anon');
db_increment(245, 'add_campaign_photos');
db_increment(246, 'migrate_campaign_owners');
db_increment(248, 'add_donor_addresses');
db_increment(249, 'create_redirect_table');
db_increment(250, 'extract_access_token_from_sessions');
db_increment(251, 'collapse_fb_permissions');
db_increment(253, 'add_transaction_tables');
db_increment(254, 'add_transaction_tables2');
db_increment(255, 'add_donor_data_column');
db_increment(257, 'add_campaign_blog_id');
db_increment(258, 'add_campaign_fr_id');
db_increment(260, 'add_fullcontact');
db_increment(261, 'add_gift_images');
db_increment(262, 'db_set_default_fundraisers');
db_increment(263, 'import_custom_skins');
db_increment(264, 'add_payment_gc_amount');
db_increment(265, 'update_fundraiser_tags');
db_increment(266, 'add_fundraiser_custom');
db_increment(267, 'add_gift_levels');

echo "Database is at #" . db_get_version() . "<br/>";

if ( function_exists('wp_cache_clear_cache')) {
  wp_cache_clear_cache();
  echo "Cache cleared.<br/>";
}
