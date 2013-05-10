<?

/* REPLACE THE STATS BAR */

global $TEMPLATE, $event_theme;
if ($TEMPLATE === NULL)
  $TEMPLATE = new stdClass;
$TEMPLATE->theme = eor($TEMPLATE->theme, $event_theme);

add_action('wp_head', 'add_typekit', 100);

function draw_template_stats($campaign = NULL) {
?>
<?
}
replace_action('draw_campaign_stats', 'draw_template_stats');

function campaign_setup($campaign) {
  global $more, $event_id, $current_user, $event_theme;
  get_currentuserinfo();
  $more = 0;

  global $post;
  setup_postdata($post);
}

function draw_campaign_sharing($campaign) {
  draw_sharing_vertical();
}

add_action('draw_campaign_appeal', 'campaign_setup');
replace_action('draw_campaign_appeal', 'draw_campaign_sharing');

// this sets the link up in the page header to team/campaign/org
add_filter('member_cause', 'get_fundraiser_cause');

/* REPLACE THE DEFAULT CAMPAIGN TEMPLATE */

function draw_template_givebox($campaign = NULL) {
  global $TEMPLATE;

/*
  if (!empty($TEMPLATE->please)) {
    draw_pledge_box(array('campaign' => $campaign, 'button_only' => true, 'give_any' => !$TEMPLATE->can_pledge, 'no_stats' => !$TEMPLATE->can_pledge));
  } else {
*/
    ?><div id="giveany"><? syi_giveany_widget(array( 'title' => '', 'message' => '' )); ?></div><?
/*
  }
*/
}

function draw_campaign_sidebar($campaign = NULL) {
  global $bp, $TEMPLATE;

  if (is_showing($TEMPLATE->fundraisers, 'give', TRUE))
    do_action('draw_template_givebox', $campaign);

  draw_if_showing($TEMPLATE->fundraisers, 'note', $TEMPLATE->gallery, 'campaign_note');

  if ($bp->current_component == 'campaign' && is_showing($TEMPLATE->fundraisers, 'thankyou', TRUE))
    syi_progress_widget(array(
      'campaign' => $campaign,
      'title' => "Thanks to...",
      'empty_message' => bp_is_my_profile() ? "Find your first supporters by inviting people to this page!" : "Be the first to give!",
      'avatars' => TRUE,
      'limit' => 100
    ));
}
add_action('draw_campaign_sidebar', 'draw_campaign_sidebar');

function draw_fundraiser_stats_section($campaign = NULL) {
  global $TEMPLATE;

  if (!$TEMPLATE->please && is_showing($TEMPLATE->fundraisers, 'progress', TRUE)) {
    ?><div id="stats-bar"><? 
    syi_stat_section(); 
    ?></div><?
   } 
}
add_action('draw_fundraiser_stats_section', 'draw_fundraiser_stats_section');

function draw_template_content($campaign = NULL) {
  global $TEMPLATE;

  $name = get_firstname($campaign->owner);

  ?><div class="campaign-page fundraiser-page"><div class="campaign-sidebar page-sidebar fundraiser-sidebar"><?
    do_action('draw_campaign_sidebar', $campaign);
  ?></div><div class="campaign-content fundraiser-content">
    <? draw_if_showing($TEMPLATE->fundraisers, 'header', $TEMPLATE->gallery, 'campaign_header'); ?>
    <? do_action('draw_fundraiser_stats_section', $campaign); ?>
    <div class="page-content template-about based" style="margin-bottom:0;">
      <div class="right campaign-photo">
        <? draw_campaign_photo($campaign); ?>
        <? draw_sharing_horizontal(); ?>
      </div>
      <div class="paperclip"></div>
      <?
      global $post;
      $my_headline = get_post_meta($post->ID, 'syi_my_headline', 1);

      // If we're asking users for a title, show that. 
      // Otherwise use the template defaults.
      if (empty($TEMPLATE->fields->post_title))
        $t = eor($TEMPLATE->heading, $TEMPLATE->post_title, $TEMPLATE->title);

      if (has_action('draw_fundraiser_title')) {
        do_action('draw_fundraiser_title', $campaign);
      } else if ($my_headline) {
        ?><h1 class="campaign-title"><?= $my_headline ?></h1><?
      } else if ($TEMPLATE->short_form) {
        ?><h1 class="campaign-title"><?= xml_entities($t) ?></h1><?
        $content = apply_filters('campaign_content', $content);
        try_draw_promo("$TEMPLATE->theme-before", $TEMPLATE->before, "$TEMPLATE->post_title - Before the content");
      } else if (empty($t)) {
        ?><h1 class="campaign-title"><?= the_title() ?></h1><?
      } else {
        ?><h1 class="campaign-title"><?= xml_entities($t) ?></h1><?
      }

      $content = get_the_content();
      $content = apply_filters('the_content', $content);
      echo str_replace(']]>', ']]&gt;', $content);

      do_action('after_campaign_appeal_message', $campaign);
      ?></div> <?

      $tags = get_fr_tags($campaign);
      if (empty($tags) || isset($_REQUEST['NOHDR'])) {
        $tags = eor($TEMPLATE->tag, $TEMPLATE->theme);
      }

      if (is_showing($TEMPLATE->fundraisers, 'gifts', $TEMPLATE->gifts)) {
        ?><div id="gift-section"><?

        Widget::gift_browser(array(
          'title' => section_value($TEMPLATE->fundraisers, 'gifts', 'title', NULL),
          'tags' => $tags,
          'exclude' => $TEMPLATE->exclude_hack,
          'show_private' => $TEMPLATE->show_private // needed?
        ));

        ?></div><?
      }

      if (is_showing($TEMPLATE->fundraisers, 'stories', $TEMPLATE->gifts)) {

        $stories = stories_shortcode(array(
         'tag' => $tags,
         'limit' => 4
        ));
        if (!empty($stories)) {
          ?><div class="sample-stories indented"><?
          $title = trim(section_value($TEMPLATE->fundraisers, 'stories', 'title', "You'll see the impact of your donation on the actual recipient.")); 
          if (!empty($title)) {
            ?><h2><?= xml_entities($title) ?></h2><?
          }

          echo $stories;
          ?></div><?
        }
      }

    if (is_showing($TEMPLATE->fundraisers, "about")) {
      ?><div class="template-about page-content based" style="clear:both;"><?
      $title = trim(section_value($TEMPLATE->campaign_page /* YES */, 'about', 'title', ''));
      if (!empty($title)) {
        ?><h2><?= xml_entities($title) ?></h2><?
      }
      draw_gallery_part($TEMPLATE->gallery['campaign_about'], 'campaign_about');
      ?></div><?
    }

    if (is_showing($TEMPLATE->fundraisers, 'comments', TRUE)) { ?>
      <div class="campaign-comments">
        <?
        if (empty($name)) $name = "us";

        // Load the comments title with fallback to legacy values
        $cmt = xml_entities(section_value($TEMPLATE->fundraisers, 'comments', 'title', eor($TEMPLATE->comments, "Tell [name] why you love this cause!")));

        $cmt = str_replace("[name]", xml_entities($name), $cmt);
        $cmt = str_replace("you love ", "<b>you love</b> ", $cmt);
        $cmt = str_replace("love", '<img src="http://seeyourimpact.org/wp-content/images/heart.png" style="vertical-align: middle;">', $cmt);

        ?>
        <h2><?= $cmt ?></h2>
        <? syi_fb_comments(array(
          'width' => 600,
          'no_header' => true,
          'compat' => false
        )); ?>
      </div>
    <? } ?>

  </div></div>
<?
}
replace_action('draw_campaign_content', 'draw_template_content');
add_action('draw_template_givebox', 'draw_template_givebox');


/* UPDATED "Please" IN GIVE BOX */

function get_template_stats($campaign) {
  global $TEMPLATE;

  if (!empty($TEMPLATE->please) && empty($campaign->please))
    $campaign->please = $TEMPLATE->please;
  return $campaign;
}
add_filter('get_campaign_stats', 'get_template_stats');
remove_action('draw_campaign_stats', 'draw_campaign_stats');


/* THEMED EDITOR */

function template_thumbnail($page) {
  global $TEMPLATE;

  return eor($TEMPLATE->thumbnail, $page);
}
add_filter('fundraiser_thumbnail', 'template_thumbnail');

function template_defaults($args) {
  global $TEMPLATE;

  load_theme_contents();

  if (empty($args['post_title']) && !empty($TEMPLATE->post_title))
    $args['post_title'] = $TEMPLATE->post_title;
  if (empty($args['post_content']))
    $args['post_content'] = $TEMPLATE->post_content;
  if (empty($args['goal']) && !empty($TEMPLATE->fundraisers))
    $args['goal'] = eor($TEMPLATE->fundraisers->goal, 250);
  if (empty($args['start_date']) && !empty($TEMPLATE->start_date))
    $args['start_date'] = $TEMPLATE->start_date;
  if (empty($args['end_date']) && !empty($TEMPLATE->end_date))
    $args['end_date'] = $TEMPLATE->end_date;
  if (empty($args['public']) && !empty($TEMPLATE->public))
    $args['public'] = $TEMPLATE->public;
  if (!empty($TEMPLATE->theme))
    $args['theme'] = $TEMPLATE->theme;
  if ($TEMPLATE->organization)
    $args['organization'] = $TEMPLATE->organization;

  return $args;
}
add_filter('campaign_editor_defaults', 'template_defaults');





function template_editor_top($campaign = NULL) {
  global $TEMPLATE;

  $t = eor($TEMPLATE->fundraisers->type, "fundraiser");
  if ($campaign != null && $campaign->id > 0) {
    echo sidebar_widget(array('id' => 'edit-fundraiser-sidebar'));
    draw_page_title("Edit your $t");
  } else {
    echo sidebar_widget(array('id' => eor($TEMPLATE->start_sidebar, 'start-fundraiser-sidebar')));
    if (!try_draw_promo("$TEMPLATE->theme-start-banner", $TEMPLATE->start_banner, "$TEMPLATE->post_title - Start Banner")) {
      draw_page_title("Set up your $t");
    }
  }
}
replace_action('campaign_editor_top', 'template_editor_top', 0 );


function template_fields($fields) {
  global $TEMPLATE;

  $f = array();
  if (isset($TEMPLATE->required_fields) && isset($TEMPLATE->required_fields->post_title)) {
    if (is_true($TEMPLATE->required_fields->post_title))
      $f[] = "title";
    if (is_true($TEMPLATE->required_fields->post_content))
      $f[] = "body";
    if (is_true($TEMPLATE->required_fields->goal))
      $f[] = "goal";
    if (is_true($TEMPLATE->required_fields->team))
      $f[] = "team";
    return $f;
  }

  global $TEMPLATE;
  if ($fields == NULL) {
    if ($TEMPLATE->required_fields == NULL)
      return eor($TEMPLATE->fields, array('tags', 'title', 'body', 'team', 'goal'));
    return $TEMPLATE->required_fields;
  }
  if ($TEMPLATE->fields != NULL)
    return eor($TEMPLATE->fields, array('tags', 'title', 'body', 'team', 'goal'));
  return $fields;
}
add_filter('campaign_editor_fields', 'template_fields');

function template_check_errors($errors, $campaign, $post) {
  // TODO - break down into individual fields?
  return $errors;
}
add_filter('campaign_editor_form_errors', 'template_check_errors',10,3);


function template_labels($labels) {
  global $TEMPLATE;
  $labels['body'] = eor($TEMPLATE->body_label, 'Share why you <img src="http://seeyourimpact.org/wp-content/images/heart.png" style="vertical-align: middle;"> this cause. Feel free to use this text, or create your own:');
  return $labels;
}
add_filter('campaign_editor_labels', 'template_labels');

function template_show_donor_last_names($val) {
  global $TEMPLATE;
  return $TEMPLATE->show_admins_last_names;
}
add_filter('show_donor_last_names', 'template_show_donor_last_names');

function template_scripts() {

  $http = $_SERVER['HTTPS'] ? 'https' : 'http';
?>
<script>
$(function() {
  var p = $('.campaign-sidebar .progress-widget, .page-sidebar .progress-widget');

  function rszpw() {
    p.css({ position: 'relative', top:'auto', height: 'auto' });
    var h = $('.page-content').height();
    var i = p.find('>.interior');
    var pos = p.position();
    if (pos == null)
      return;
    var b = pos.top + i.height();
    if (b > h) {
      p.css({ position: 'absolute', top: pos.top, bottom: 0, height: 'auto'});
    } else {
      var h2 = i.find('.invite-button').length > 0 ? 40 : 10;
      p.css({ position: 'relative', top:'auto', height: i.height() + h2 } );
    }
  }
  p.on('rszpw', rszpw).trigger('rszpw');
  $(window).on('resize', function() { p.trigger('rszpw'); });
});
</script>
<?
}
add_action('wp_head', 'template_scripts', 100);

wp_enqueue_style('syi-default', "/themes/" . basename(__FILE__, '.php') . ".css");
