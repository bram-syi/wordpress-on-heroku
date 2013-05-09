<?php

require('default.php');

// New Leaders: replace title with nice HTML logo
function our_fundraiser_title($campaign = NULL) {
  ?><a href="<?= esc_url($campaign->context->campaign_url) ?>"><img src="http://newleaders.seeyourimpact.org/files/2012/11/New_Leaders_Giving_Tuesday.png" alt="" width="380" /></a><?
}
add_action('draw_fundraiser_title', 'our_fundraiser_title');

// Replace item name in cart
remove_filter('get_fundraiser_donation_item', 'get_fundraiser_donation_item');
add_filter('get_fundraiser_donation_item', 'our_fundraiser_donation_item');
function our_fundraiser_donation_item($name) {
  if (empty($name))
    return "Giving Tuesday fundraiser";
  return "$name's Giving Tuesday fundraiser";
}
