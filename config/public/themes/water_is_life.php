<?php

function our_team_context($result) {
  $result->campaign_url = "http://medrix.seeyourimpact.org/washthosehands";
  $result->blog_id = 28;
  $result->team_page_give_any = TRUE;
  $result->team_page_gifts = TRUE;
  $result->default_fundraiser = 12550;
  $result->gifts_label = " ";
  $result->campaign_header = "http://medrix.seeyourimpact.org/files/2013/02/SYI-Water-Campaign-Banner-990-Pixels.jpg";
  // $result->teams_title = 
  $result->og_image = "http://res.cloudinary.com/seeyourimpact/image/fetch/x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  $result->order = "donors";
  return $result;
}
add_filter('modify_team_context', 'our_team_context');

require('default.php');
