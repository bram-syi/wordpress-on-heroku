<?php

function our_team_context($result) {
  $result->campaign_url = "http://roosevelt.seeyourimpact.org";
  $result->blog_id = 128;
  $result->team_page_give_any = TRUE;
  $result->team_page_gifts = TRUE;
  $result->default_fundraiser = 12491;
  $result->gifts_label = " ";
  $result->order = "donors";
  $result->donor_goal = 200;
  return $result;
}
add_filter('modify_team_context', 'our_team_context');

require('default.php');
