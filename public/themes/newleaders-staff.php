<?php

require('default.php');

// New Leaders: replace title with nice HTML logo
function our_fundraiser_title($campaign = NULL) {
  ?><a href="<?= esc_url($campaign->context->campaign_url) ?>"><img src="http://newleaders.seeyourimpact.org/files/2012/11/New_Leaders_Giving_Tuesday.png" alt="" width="380" /></a><?
}
add_action('draw_fundraiser_title', 'our_fundraiser_title');

// New Leaders: only wants to show stats to admins
function our_fundraiser_stats_section($campaign) {
  if (!can_manage_campaign($campaign->id))
    return;
  draw_fundraiser_stats_section($campaign);
}
replace_action('draw_fundraiser_stats_section', 'our_fundraiser_stats_section');
