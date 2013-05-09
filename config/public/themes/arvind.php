<?
global $header_file;
$header_file = 'pratham';

include("default.php");

function draw_arvind_campaign_sidebar($campaign = NULL) {
  do_action('draw_template_givebox', $campaign);

  try_draw_promo("$campaign->theme-sidebar", '&nbsp;', "$campaign->sidebar - Sidebar");

  syi_progress_widget(array(
    'campaign' => $campaign,
    'title' => "Thanks to...",
    'empty_message' => bp_is_my_profile() ? "Find your first supporters by inviting people to this page!" : "Be the first to give!",
    'avatars' => FALSE,
    'limit' => 100
  ));
}

replace_action('draw_campaign_sidebar', 'draw_arvind_campaign_sidebar');
