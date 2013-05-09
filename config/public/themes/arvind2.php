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

function my_campaign_stats($stats) {
  $stats->total += 100 * $stats->donors;
  $stats->raised += 100 * $stats->donors;
  return $stats;
}

global $matches;
$matches = array();
function my_progress_row($d) {
  global $matches;
  if ($matches[$d->user_id])
    return;

  $matches[$d->user_id] = TRUE;
  ?><div style="font-size:90%; color: #666;">TeachPC matched $100!</div><?
}

add_filter('campaign_stats', 'my_campaign_stats');
add_action('progress_widget_row', 'my_progress_row');

