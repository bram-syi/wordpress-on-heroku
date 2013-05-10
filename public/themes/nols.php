<?

function draw_nols_content() {
  ?><div class="campaign-content">
  <div class="based" style="padding: 0 45px 20px 30px;">
    <? draw_promo_c2('nols-about'); ?>
  </div>
  <?
  syi_social_section();
  ?></div><?
}

remove_action('draw_campaign_content', 'draw_campaign_content');
add_action('draw_campaign_content', 'draw_nols_content');

function draw_nols_stats($campaign = NULL) {
}

remove_action('draw_campaign_stats', 'draw_campaign_stats');
add_action('draw_campaign_stats', 'draw_nols_stats');

function before_appeal() {
  ?>
  <div id="video" style="width: 180px;">
    <iframe frameborder="0" id="vimeo-31565073" src="http://player.vimeo.com/video/31565073?title=0&amp;byline=0&amp;portrait=0&amp;api=1&amp;rel=0" width="170" height="285"></iframe>
    <label>Eagle Scout and former Governor and Senator, Dan Evans, urges everyone to contribute to the NOLS scholarship!</label>
  </div>


  <?
}
function after_appeal()
{
  ?>
  <div class="team-box">
  <div class="title"><img src="http://chickasawbsa.org/assets/1401/6_scoutreach_logo.jpg" width="80" style="vertical-align:middle;">Raising for ScoutReach!</div>
  <? echo leaderboard_shortcode(array(
    'mode' => 'team',
    'teams' => 'charles, davej'
  )); ?>
  </div>
  <div id="giveany">
    <? syi_giveany_widget(array( 'title' => '', 'message' => '' )); ?>
  </div>
  <?
}

add_action('before_campaign_appeal_content', 'before_appeal');
add_action('after_campaign_appeal_message', 'after_appeal');
