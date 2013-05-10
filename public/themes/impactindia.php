<?

remove_action('syi_pagetop', 'draw_the_crumbs', 0);
add_action('syi_pagetop', 'draw_impact_crumbs', 0);

remove_action('draw_fundraiser_ad','draw_fundraiser_ad');

function template_scripts() {
?>
<script type="text/javascript" src="http://use.typekit.com/nbw4bxb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?
}
add_action('wp_head', 'template_scripts', 100);

sharing_init(TRUE);

function impact_body_class($classes) {
  $classes[] = "charity-page";
  return $classes;
}
add_filter('body_class','impact_body_class');

function draw_impact_crumbs() {
  global $event_id;
  ?><div class="crumbs"><?
  switch_to_blog(get_campaign_charity($event_id));
  charity_crumbs(NULL, FALSE);
  restore_current_blog();
  ?></div><?
}

function draw_impact_content() {
  ?>
  <div class="campaign-content">
    <? syi_stories_section(); ?>
  </div>
  <?
}

remove_action('draw_campaign_content', 'draw_campaign_content');
add_action('draw_campaign_content', 'draw_impact_content');

function draw_impact_stats($campaign = NULL) {
?>
<div id="stats-bar">
  <div id="giveany"><? syi_giveany_widget(array( 'title' => '', 'message' => '' )); ?></div>
  <? syi_stat_section(); ?>
</div>
<?
}

remove_action('draw_campaign_stats', 'draw_campaign_stats');
add_action('draw_campaign_stats', 'draw_impact_stats');


function draw_impact_appeal($campaign = NULL) {
  ?>
  <? draw_sharing_vertical(); ?>
  <section id="appeal" class="appeal page-content based">
  <div style="width:983px; height: 265px; background: url(http://impactindia.seeyourimpact.org/files/2012/04/impact-india-banner.jpg) no-repeat -9px -4px; margin: 4px;"></div>
  </section>
  <?
} 
remove_action('draw_campaign_appeal', 'draw_campaign_appeal');
add_action('draw_campaign_appeal', 'draw_impact_appeal');


function draw_impact_comments($campaign = NULL) {
  ?><div class="impact-about based">
  <h1 class="campaign-title"><? the_title() ?></h1>
  <?  the_content();
  ?><div><?draw_sharing_horizontal(); ?></div><?
  ?></div><?

  ?>
  <div class="campaign-comments">
     <h2 class="section-header heading">Tell us why <b>you</b>
<img src="http://seeyourimpact.org/wp-content/images/heart.png" style="vertical-align: middle;">
Impact India's cause!</h2>
    <? syi_fb_comments(array(
      'width' => 800,
      'no_header' => true,
      'compat' => false
    )); ?>
  </div>
  <?
}
remove_action('draw_campaign_content', 'draw_campaign_content');
add_action('draw_campaign_content', 'draw_impact_comments');

