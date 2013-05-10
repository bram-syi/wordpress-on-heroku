<?

function before_appeal()
{
  global $event_id;

?>
<div class="right" style="margin: -10px 8px 0 0; width: 200px;">
<div class="wp-caption alignright"><? draw_campaign_photo($event_id, 'mikehilton'); ?></div>
<div class="wp-caption alignright"><img src="http://seeyourimpact.org/files/2012/01/megan_girls.jpg" width="200" height="250"/></div>
</div>
<? 
}

function after_appeal()
{
  global $post;

  ?><div id="giveany"><?
  syi_giveany_widget(array( 'title' => '', 'message' => '' ));
  ?></div><?
  return;
}

function after_sidebar() {
?>
  <h2 class="section-header" style="margin-top:25px; padding: 8px 0 15px 20px;">Want to help?</h2>
<ol id="how-to-help">
<li>1. <b>Donate $10</b> or more on this page.</li>
<li>2. <b>Email this page</b> to your friends.
  <? // draw_invite_link ("campaign/".encrypt($post->ID), '', FALSE, "invite" ); ?>
</li>
<li>3. <b>Share this page</b> on Facebook and Twitter.</li>
<li>4. <b>Leave a message</b> in the comments below.</li>
</ol>
<?
}

function campaign_content() {
?>
  <section class="quickfacts">
  <ul>
  <li>80% of women in rural Uttar Pradesh are illiterate, and&nbsp;40% of the elderly have chronic illnesses requiring long-term or lifelong treatment</li>
  <li>JSM Trust provides quality middle and high school education to rural girls, and treats poor patients for asthma, hypertension, diabetes and TB</li>
  <li>Since its inception in 2005, JSM Trust has treated more than 7,000 patients, free of charge.</li>
  </ul>
  </section>
<?
}

function browser_args($args) {
  // $args['give_any'] = TRUE;
  return $args;
}

function appeal_top() {
  // cevhershare();
}

function my_stats($campaign = NULL) {
  ?><div id="stats-bar"><?
  syi_stat_section();
  ?></div><?
}
function my_campaign_stats($stats) {
  $stats->total += 100 * $stats->donors;
  return $stats;
}

global $matches;
$matches = array();
function my_progress_row($d) {
  global $matches;
  if ($matches[$d->user_id])
    return;

  $matches[$d->user_id] = TRUE;
  ?><div style="font-size:90%; color: #666;">an additional $100 was added!</div><?
}

add_filter('campaign_stats', 'my_campaign_stats');
add_filter('gift_browser_args', 'browser_args');
add_action('progress_widget_row', 'my_progress_row');
add_action('draw_campaign_stats', 'my_stats');
remove_action('draw_campaign_stats', 'draw_campaign_stats');
add_action('before_campaign_appeal', 'appeal_top');
add_action('before_campaign_appeal_message', 'before_appeal');
add_action('after_campaign_appeal_message', 'after_appeal');
add_action('after_give_sidebar', 'after_sidebar');
add_action('draw_campaign_content', 'campaign_content', -1);
