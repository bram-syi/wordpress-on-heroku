<?

function draw_mlkday_content() {
  ?><div class="campaign-content">
  <div class="based" style="padding: 20px 45px 20px 30px;">
    <? draw_promo_c2('mlkday-about'); ?>
  </div>
  <?
  syi_stories_section();
  syi_social_section();
  ?></div><?
}

remove_action('draw_campaign_content', 'draw_campaign_content');
add_action('draw_campaign_content', 'draw_mlkday_content');

function draw_mlkday_stats($campaign = NULL) {
  ?><div id="stats-bar"><?
  syi_stat_section();
  ?></div><?
}

remove_action('draw_campaign_stats', 'draw_campaign_stats');
add_action('draw_campaign_stats', 'draw_mlkday_stats');

function after_appeal()
{
  ?><div id="giveany"><?
  syi_giveany_widget(array( 'title' => '', 'message' => '' ));
  ?></div><?
}

add_action('after_campaign_appeal_message', 'after_appeal');
