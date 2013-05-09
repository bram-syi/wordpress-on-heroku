<?php

/*
Template Name: Chapter
*/

include_once(ABSPATH . 'a/api/campaign.php');
include_once(ABSPATH . 'a/api/fundraiser.php');

global $header_file;
$header_file = 'campaign-page';

standard_page();

add_filter('get_campaign_context', 'get_campaign_context');
add_filter('modify_team_context', 'campaign_modify_context');
add_action('campaign_page_sidebar', 'campaign_page_sidebar');
add_action('draw_campaign_stats_section', 'draw_campaign_stats_section');
add_action('campaign_page_header', 'campaign_page_header');
add_action('facebook_meta', 'campaign_meta_tags');

// For now, remove the page crumbs - could reintroduce these later
remove_action('syi_pagetop', 'draw_the_crumbs', 0);

$js = apply_filters('get_js_dir', '');
wp_enqueue_style('qtip', "$js/jquery.qtip.css");
wp_enqueue_script('qtip', "$js/jquery.qtip.pack.js");
wp_enqueue_script('cgw', "$js/campaign-gallery.js");

global $context;
$context = (object)apply_filters('get_campaign_context', new stdClass);
$context = apply_filters('modify_team_context', $context);

$is_a_team = !empty($context->team);

get_header();
?>
<style>
.gallery {
  margin: 20px -20px 0;
  width: 660px;
}
#gallery .gallery-left {
  width: 660px;
  margin: 0; padding: 0;
  float: none;
}
.gallery .gallery-right {
  width: 660px;
}
.gallery .post {
  padding: 0 0 30px 5px;
}
.gallery .vertical {
  width: 660px;
  height: 380px;
}
.gallery .items .selected {
  box-shadow: none;
  margin: 0;
  background: white;
  border-radius: 0;
}
.gallery-widget .item-image {
  margin-right: 17px;
}
.gallery-widget .selected .pick-me {
  bottom: -15px;
}
.gallery-widget .item-buttons {
  bottom: 15px;
}

.leaderboard {
  display: block;
  width: 450px;
  border-left: 2px solid #2B4E64;
  padding-top: 10px;
}
.no-bars {
  border-left: 0px none;
}
.leaderboard .team {
  display: block;
  padding-bottom: 10px;
  width: 100%;
  color: black;
}
.no-bars .team {
}
.leaderboard .progress {
  display: block;
  height: 40px;
  background: #003760;  /* #2B4E64; */
  float: left;
  border-radius: 0 6px 6px 0;
  box-shadow: 3px 3px 8px #ccc;
}
.leaderboard label {
  width: 180px;
  padding-left: 10px;
  margin-right: -190px;
  display: block;
  float: left;
  height: 40px;
}
.no-bars .progress {
  display: none;
}
.leaderboard .name {
  display: block;
  font-size: 12pt;
  font-weight: bold;
}
.no-bars .name {
  display: inline;
  float: left;
}
.leaderboard .raised {
  margin-top: 3px;
  display: block;
  font-size: 9pt;
}
.leaderboard .raised .amount { font-weight: bold; font-size: 110%; }
.no-bars .raised {
  display: inline;
  float: right;
}
.leaderboard .total {
  display: none;
}
.appeal .no-bars .total {
  padding-left: 0;
}

.leaderboard .team:hover .progress {
  background: #07C;
  box-shadow: 3px 3px 10px #888;
}
.leaderboard .team:hover .name {
  color: #07C;
  text-decoration: underline;
}
</style>

<? draw_sharing_vertical(); ?>
<div class="team-page <?= $is_a_team ? "" : "no-team-page" ?> standard-page">

  <?
  if (isset($_GET['msg'])) {
    draw_campaign_help_message($_GET['msg'], $campaign);
  } 
  ?>

  <div class="page-main <?= $is_a_team ? "page-main-team" : "page-main-no-team" ?>">
    <div id="page-sidebar" class="right page-sidebar based">
      <?
      draw_if_showing($context->campaign_page, "note", $context->gallery, "campaign_note");

      // team-specific sidebar
      if ($is_a_team)
        draw_promo_c2("sidebar-{$context->team}", FALSE);

      do_action('campaign_page_sidebar', $context); 
      ?>
    </div>
    <? 
    do_action('campaign_page_header', $context);

    if (is_showing($context->campaign_page, "progress"))
      do_action('draw_campaign_stats_section', $context);
    draw_sharing_horizontal();
    ?>

    <div class="based">
      <?
      if (!$is_a_team && is_showing($context->campaign_page, "give"))
        add_action('team_page_before_content', 'draw_team_give_any');

      // Appeal is always showing
      do_action('team_page_before_content', $context);
      $title = trim(section_value($context->campaign_page, 'appeal', 'title', ''));
      if (!empty($title)) {
        ?><h1><?= xml_entities($title) ?></h1><?
      }

      if ($context->_team && $context->_team->team_body)
        echo $context->_team->team_body; // It's HTML
      else
        draw_gallery_part($context->gallery['campaign_appeal'], 'team-content page-content');
      do_action('team_page_after_content', $context);

      if (empty($context->team) && is_showing($context->campaign_page, "leaderboard")) {
        echo draw_team_leaderboard($context->teams, array(
          'donors' => TRUE,
          'me' => $context->team
        ));
      }

      /* ?><div id="_mpact_widget" class="mpact_widgets" data-format="banner"></div><? */

      if (is_showing($context->campaign_page, "champions")) {
        do_action('team_page_before_fundraisers', $context);
        draw_team_fundraisers($context);
        do_action('team_page_after_fundraisers', $context);
      }

      if (!$is_a_team && is_showing($context->campaign_page, "gifts")) {
        do_action('team_page_before_gifts', $context);
        draw_team_gifts($context);
        do_action('team_page_after_gifts', $context);
      }

      if (is_showing($context->campaign_page, "stories")) {
        do_action('team_page_before_stories', $context);
        draw_team_stories($context);
        do_action('team_page_after_stories', $context);
      }

      if (is_showing($context->campaign_page, "about")) {
        ?><div class="page-content"><?
        $title = trim(section_value($context->campaign_page, 'about', 'title', ''));
        if (!empty($title)) {
          ?><h2><?= xml_entities($title) ?></h2><?
        }
        draw_gallery_part($context->gallery['campaign_about'], 'campaign_about');
        ?></div><?
      }
      ?>
    </div>

    <? if (current_user_can("editor")) { ?>
      <form method="POST" class="full-wide admin-actions">
        <b>Admin</b>:
        <a target=_new"  href="<?= SITE_URL ?>/admin/campaign/<?= $context->campaign ?>"><u>edit campaign</u></a>
        <?= draw_context($context) ?>
      </form>
    <? } ?>

  </div>
</div>
<?

get_footer();

function draw_campaign_stats_section($context = NULL) {
  if ($context->fr_id > 0) {
    // STEVE: temporary -- refresh stats in case of direct-DB changes
    // but it's a bit expensive
    update_campaign_stats($context->fr_id);
  }

  if (!empty($context->team))
    $stats = $context->_team;
  else
    $stats = $context->_campaign;

  $stats->donors_count = $stats->donors;
  draw_campaign_stat_bar($stats);
}

function draw_team_join($context, $col = 0, $already = TRUE) {
  if ($context->can_join === FALSE)
    return;

  if ($already) {
    $col = ($col % 4);
    // Close off the last row
    while ($col++ < 4) {
      ?>
      <div class="fundraiser-card fundraiser-slot box-model">
        <a href="<?=$context->start_link?>" class="fundraiser-photo">join this fundraising team!</a>
      </div>
      <?
    }
    return;
  }

  if ($col < 4) {
    ?>
    <div class="fundraiser-card fundraiser-slot box-model">
      <a href="<?=$context->start_link?>" class="fundraiser-photo">join this fundraising team!</a>
    </div>
    <?
  }

  $col = (++$col % 4);

  ?>
    <div class="team-join team-join-<?=$col?> box-model">
      <span class="msg3">
      <span class="msg1"><?= apply_filters('team_join_message', 'Want to fundraise for this cause?') ?></span>
      <span class="msg2">Add your photo here!</span>
      </span>
      <a href="<?= $context->start_link ?>" class="button medium-button green-button"><?= apply_filters('team_join_label', 'Join the team') ?></a>
    </div>
  <?

  return 4 - $col;
}

function draw_team_fundraisers($context) {
  // Number of active champs?
  if ($context->active <= 0)
    return;

  $raisers = FundraiserApi::get(array(
    'campaign' => $context->theme,
    'team' => $context->team,
    'view' => 'gallery',
    'is_fund' => FALSE,
    'order' => !empty($context->team) ? 'display_name' : ($context->downplay_money ? 'donors' : 'raised')
  ));

  ?>
  <div class="team-content page-content">
    <div class="team-fundraisers" style="padding:0;">
      <? if (apply_filters('draw_findraisers', TRUE)) { ?>
        <div class="right w200 findraisers" style="padding-top: 5px; margin-right: -10px;">
          <? draw_findraiser($context, $raisers); ?>
        </div>
      <? } ?>
      <h2><?= fundraisers_heading($context->team_name); ?></h2>
      <? fundraisers_widget($context, (count($context->teams) > 0 && !$context->_team) ? 9 : 80, $raisers); ?>
    </div>
  </div>
  <?
}

function fundraisers_heading($team) {
  global $context;

  if (has_filter('fundraisers_heading')) {
    $heading = apply_filters('fundraisers_heading', $team);
  } else if (!empty($context->teams_title)) {
    $heading = $context->teams_title;
  } else if ($team) {
    $heading = "Support our $team fundraisers";
  } else {
    $heading = section_value($context->campaign_page, 'champions', 'title', "Our fundraising team");
  }

  return $heading;
}

function compare_fundraisers_name($fr1, $fr2) {
  return strcasecmp($fr1->display_name, $fr2->display_name);
}

function draw_findraiser($context, $frs) {
  $teams = array();
  foreach ($frs as $fr) {
    $team = eor(ucfirst($fr->fundraiser_team), "(other)");
    $teams[$team][] = $fr;
  }
  ksort($teams);

  ?><select id="findraiser" class="chzn-select" data-placeholder="Jump to a fundraiser" style="width:210px;"><?
  ?><option value=""></option><?
  foreach ($teams as $title=>$frs) {
    if (count($teams) > 1) {
      ?><optgroup label="<?=xml_entities($title)?>"><?
    }
    uasort($frs, 'compare_fundraisers_name');
    foreach ($frs as $fr) { 
      $name = eor($fr->display_name, "Anonymous");
      ?><option value="<?=esc_attr($fr->url)?>"><?= xml_entities($name) ?></option><?
    }
    if (count($teams) > 1) {
      ?></optgroup><?
    }
  }
  ?></select><script>$(".chzn-select").val("").select2({
      formatNoMatches: function(term) { return "Can't find " + term; }
    }).change(function(ev) {
    var val = $(this).val();
    if (val)
      window.location = val;
  });</script><?
}

function draw_context($context) {
  $fields = array('type', 'tags', 'archived', 'can_join');

  foreach ($fields as $k) {
    $v = $context->$k;
    if (empty($v) || is_object($v) || is_array($v))
      continue;
    echo " | " . xml_entities($k) . ": " . xml_entities($v);
  }
}

function fundraisers_widget($context, $limit = 4, $frs) {
  ?><div class="fundraiser-cards"><?

  $can_join = $context->can_join !== FALSE;
  $already = FALSE;
  $q = 0;
  for ($i = 0; $i < $limit && $i < count($frs); $i++) {
    $c = $frs[$i];
    $c->id = $c->post_id;
    if ($context != NULL && $c->id == $context->fr_id)
      continue;

    if ($q == 5 && $can_join) {
      $q += draw_team_join($context, $q, FALSE);
      $already = TRUE;
    }
    $q++;

    if (empty($c->display_name))
      $c->display_name = get_campaign_appear_as($c->post_id);

    $pct = pct($c->raised, $c->goal);
    $raised = as_money($c->raised, '$%.0n');
    $photo = $c->image;

    if ($context->downplay_money)
      $raised = '&nbsp;';

    ?>
    <div class="fundraiser-card box-model" id="fundraiser-<?=$c->post_id?>">
      <a href="<?=eor($c->guid, $c->url)?>" class="fundraiser-photo">
        <div class="photo-wrapper">
          <img src="<?= $photo ?>" width="140" height="140">
        </div>
        <span class="name"><?=xml_entities($c->display_name)?><span class="team"><?= $c->team ?></span></span>
      </a>
      <span class="progress">
        <span class="progress-bar" style="width: <?=$pct?>%;" class="progress2"></span>
      </span>
      <span class="stats" style="font-size:75%; color: #666;">
        <span class="left raised"><?= ($raised == '$0' ? '' : $raised) ?></span>
        <? if ($c->donors > 0) { ?>
          <span class="right donors"><?= plural($c->donors, 'donor') ?></span>
        <? } ?>
      </span>
    </div>
    <?

  }

  if ($can_join)
    draw_team_join($context, $q, $already);

  stopwatch_comment();
  ?></div><?
}

function draw_team_stories($context) {
  global $wpdb;

  $tag = eor($context->tags, $context->tag, $context->theme, $context->org);
  $tagged = build_tag_query("g.tags", $tag, '','');
  $tagged = "($tagged OR " . build_tag_query("g2.tags", $tag, '','') . $wpdb->prepare(" OR c.theme = %s)", $context->theme);

  $sql = 
    "SELECT DISTINCT
      ds.blog_id as blog_id, ds.post_id as post_id, ds.post_title as title, 'story' as type,
      ds.guid as guid, ds.post_modified as modified, ds.post_image as image, ds.post_excerpt as excerpt,
      ds.post_excerpt as content, dg.event_id as parent, ds.featured as featured
    FROM donationStory ds
      JOIN wp_blogs b ON (ds.blog_id = b.blog_id)
      JOIN donationGifts dg ON (dg.blog_id=ds.blog_id AND dg.story=ds.post_id)
      JOIN donation d on d.donationID=dg.donationID
      LEFT JOIN gift g on g.id=dg.giftID
      LEFT JOIN gift g2 on g.towards_gift_id > 0 AND g2.id=g.towards_gift_id
      LEFT JOIN campaigns c on c.post_id = dg.event_id
    WHERE NOT(ds.post_image = '')
      AND ((b.public = '1' AND b.archived = '0' AND b.mature = '0' AND b.spam = '0' AND b.deleted ='0'))
      AND $tagged
    GROUP BY ds.blog_id, ds.post_id
    ORDER BY ds.featured DESC, d.donationDate DESC
    LIMIT 16";
  if ($_REQUEST['sql'] == "yes")
    pre_dump($sql);

  $posts = $wpdb->get_results($sql);
  if (count($posts) == 0)
    return;

  $default = 0;

  $title = trim(section_value($context->campaign_page, 'stories', 'title', 'Read stories of real lives changed'));
  if (!empty($title)) {
    ?><h2 style="margin-bottom:0;"><?= xml_entities($title) ?></h2><?
  }

  ?>
  <section id="gallery" class="posts gallery section gallery-widget collapsed-section evs" >
    <div class="left gallery-left">
      <div id="thumbs" class="ev"><? draw_gallery_thumbs($posts, $default, FALSE);?></div>
    </div>

    <div class="right gallery-right">
      <div class="scrollable vertical">
        <div id="gallery-items" class="items evs">
          <? $i=0; foreach($posts as $post) { draw_gallery_item($post, $i++ == 0); } ?>
        </div>
      </div>
    </div>
  </section>
  <?
}

function get_campaign_context($result) {
  global $post, $blog_id;
 
  if ($result == NULL)
    $result = new stdClass;

  if ($post->post_parent > 0) { // This is a team
    $campaign_id = $post->post_parent;
  } else { // not a team
    $campaign_id = $post->ID;
  }

  $c = CampaignApi::getOne(array(
    'partner_id' => $blog_id,
    'page_id' => $campaign_id
  ));
  if ($c == NULL)
    throw new Exception("Not a campaign page.");

  $result->_campaign = $c;
  $result->campaign = $c->name;
  $result->tags = $c->tag;
  $result->goal = $c->goal;
  $result->theme = $c->name;
  $result->org = $c->partner_name;

  $result->teams = TeamApi::get(array(
    'campaign' => $c->name,
    'order' => 'raised' // TODO: maybe change sort order based on type of campaign?
  ));

  if ($post->post_parent > 0) {
    for ($i = 0; $i < count($result->teams); $i++ ) {
      $t = $result->teams[$i];
      if ($t->team_name == $post->post_name) {
        $result->_team = $t;
        $result->team = $t->team_name;
        $result->team_name = $t->team_title;
      }
      if ($t->indepdendent)
        $result->other_team = $t->team_title;
    }
  }

  // Steve: Bad form to do this in a filter but I need to hook in here
  // before team calls 
  setup_custom_skin($result->theme);

  global $TEMPLATE;
  if ($TEMPLATE != NULL)
    $result = (object)array_merge((array)$TEMPLATE, (array)$result);

  return $result;
}

function campaign_modify_context($context) {
  $context->start_link = SITE_URL . '/start/';
  if (!empty($context->theme))
    $context->start_link = add_query_arg('theme', $context->theme, $context->start_link);
  if (!empty($context->team))
    $context->start_link = add_query_arg('team', $context->team, $context->start_link);
  return $context;
}

function campaign_page_header($context) {
  draw_if_showing($context->campaign_page, "header", $context->gallery, "campaign_header");
}

function campaign_page_sidebar($context) {
   if (is_showing($context->campaign_page, "thankyou")) {
     syi_progress_widget(array(
      'campaign' => $context->theme,
      'title' => section_value($context->campaign_page, 'thankyou', 'title', "Thanks to..."),
      'empty_message' => bp_is_my_profile() ? "Find your first supporters by inviting people to this page!" : "Be the first to give!",
      'avatars' => TRUE,
      'hide_default_avatar' => TRUE,
      'limit' => 200
     ));
  }
}

function draw_team_give_any($context) {
  if ($context->fr_id <= 0)
    return;

  ?><div class="right give-box"><div class="give-box2"><?
  echo donate_shortcode(array(
    'color' => 'orange',
    'w1' => '',
    'w2' => 'donate',
    'eid' => $context->fr_id
  ));
  ?></div></div><?
}

function draw_team_gifts($context) {
  if ($context->fr_id <= 0)
    return;

  global $GIFTS_EVENT;
  $GIFTS_EVENT = $context->fr_id;

  ?><div style="margin: 0 -40px;"><?
  Widget::gift_browser(array(
    'title' => section_value($context->campaign_page, 'gifts', 'title', ' '),
    'tag' => eor($context->tags, $context->tag),
    'show_private' => TRUE,
    'exclude' => $context->exclude_hack
  ));
  ?></div><?
}

function campaign_meta_tags() {
  global $context;

  if (!empty($context->og_image)) {
    ?><meta property="og:image" content="<?=HTTP_IMAGE_HOST?>image/fetch/<?= $context->og_image ?>" /><?
  }
}
