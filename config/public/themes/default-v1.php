<?

function after_appeal($campaign = NULL) {
  global $TEMPLATE;

  try_draw_promo("$TEMPLATE->theme-about", $TEMPLATE->about, "$TEMPLATE->post_title - About");
}

function draw_template_sidebar($campaign = NULL) {
  draw_pledge_box(array('campaign' => $campaign, 'closed' => TRUE, 'button_only' => true, 'give_any' => TRUE));

  $name = get_firstname($campaign->owner);
  syi_progress_widget(array(
    'title' => "Thanks to...",
    'see_all' => true,
    'empty_message' => bp_is_my_profile() ? "Find your first sponsors by inviting people to this page!" : "Be the first to support $name!",
    'limit' => 100,
    'avatars' => FALSE
  ));
}

function draw_template_content($campaign = NULL) {
  global $event_id;
  global $TEMPLATE;
  
  if ($TEMPLATE->gifts === TRUE || isset($_REQUEST['gifts'])) {
    $tags = $campaign->tags;
    if (empty($tags) || isset($_REQUEST['NOHDR'])) {
      $tags = eor($TEMPLATE->tag, $TEMPLATE->theme);
    }
    if (empty($tags))
      $tags = $campaign->theme;

    ?><div id="gift-section" class="based"><?
    try_draw_promo("$TEMPLATE->theme-gifts", $TEMPLATE->gifts_content, "$TEMPLATE->post_title - Gifts"); 
    gift_browser_widget(array(
      'page_title' => ' ',
      'header' => FALSE,
      'preload' => true,
      'regions' => $tags,
      'limit' => 6, 
      'show_private' => TRUE
    ));
    ?></div><?
  }

  $name = eor(get_firstname($campaign->owner), "us");
  ?>
  <div class="campaign-comments">
    <?
    if (empty($name)) $name = "us";
    $cmt = eor(xml_entities($TEMPLATE->comments), "Tell [name] why <b>you love</b> this cause!");
    $cmt = str_replace("[name]", xml_entities($name), $cmt);
    $cmt = str_replace("love", '<img src="http://seeyourimpact.org/wp-content/images/heart.png" style="vertical-align: middle;">', $cmt);
    ?>
    <h2 class="section-header heading"><?= $cmt ?></h2>
    <? syi_fb_comments(array(
      'width' => 800,
      'no_header' => true,
      'compat' => false
    )); ?>
  </div>
  <?
}

function get_template_stats($campaign) {
  global $TEMPLATE;

  if (!empty($TEMPLATE->please))
    $campaign->please = $TEMPLATE->please;
  return $campaign;
}

function template_defaults($args) {
  global $TEMPLATE;

  if (!empty($TEMPLATE->post_title))
    $args['post_title'] = $TEMPLATE->post_title;
  if (empty($args['post_content']))
    $args['post_content'] = $TEMPLATE->post_content;
  if (!empty($TEMPLATE->goal))
    $args['goal'] = $TEMPLATE->goal;
  if (!empty($TEMPLATE->theme))
    $args['theme'] = $TEMPLATE->theme;
  return $args;
}

function draw_template_title($campaign = NULL) {
  global $TEMPLATE;

  if (empty($TEMPLATE->title))
    return;

  ?><h1 class="heading"><?
  echo $TEMPLATE->title;
  ?></h1><?
}

function template_editor_top($campaign = NULL) {
  global $TEMPLATE;

  $t = eor("fundraiser", $TEMPLATE->post_title);
  if ($campaign->id > 0) {
    echo sidebar_widget(array('id' => 'edit-fundraiser-sidebar'));
    draw_page_title("Edit your $t");
  } else {
    echo sidebar_widget(array('id' => 'start-fundraiser-sidebar'));
    if (!try_draw_promo("$TEMPLATE->theme-start-banner", $TEMPLATE->start_banner, "$TEMPLATE->post_title - Start Banner")) {
      draw_page_title("Start a $t");
    }
  }
}
replace_action('campaign_editor_top', 'template_editor_top', 0 );

function template_fields($fields) {
  global $TEMPLATE;
  if ($TEMPLATE->fields != NULL)
    return $TEMPLATE->fields;
  return $fields;
}

function template_labels($labels) {
  global $TEMPLATE;
  $labels['body'] = eor($TEMPLATE->body_label, 'Share why you <img src="http://seeyourimpact.org/wp-content/images/heart.png" style="vertical-align: middle;"> this cause. Feel free to use this text, or create your own:');
  return $labels;
}

function template_scripts() {
?>
<script type="text/javascript" src="http://use.typekit.com/nbw4bxb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<script type="text/javascript">
$(function() {
  $(".campaign-sidebar .progress-widget").live('click', function() {
    $(this).toggleClass('pledge-table-full');
    if ($(this).hasClass('pledge-table-full')) {
      $(this).find('.see-all').html('close');
      $(this).stop().animate({
        top: 10,
        left: -13,
        right: -13,
        paddingRight: 25
      }, 400, 'easeInOutQuad', function() {
        var w = $(this).innerWidth();
        $(this).css('overflow-y', 'auto');
        w -= $(this).innerWidth();
        $(this).css('padding-right', 25 - w);
      });
    } else {
      $(this).find('.see-all').html('see all');
      $(this).css('overflow-y', 'hidden');
      $(this).css('padding-right', 25);
      $(this).stop().animate({
        left: 0,
        right: 0,
        top: 250,
        paddingRight: 20
      }, 400);
    }
  });

});
</script>
<?
}

function template_custom_css() {
  $file = ABSPATH . "themes/" . basename(__FILE__, '.php') . ".css";
  if (file_exists($file)) {
    ?><style><?
    include_once($file);
    ?></style><?
  }
}

add_action('campaign_custom_css', 'template_custom_css');

add_action('wp_head', 'template_scripts', 100);

add_filter('get_campaign_stats', 'get_template_stats');
remove_action('draw_campaign_stats', 'draw_campaign_stats');
remove_action('draw_campaign_content', 'draw_campaign_content');
add_action('draw_campaign_content', 'draw_template_content');
remove_action('draw_campaign_title', 'draw_campaign_title');
add_action('draw_campaign_title', 'draw_template_title');
add_action('draw_campaign_sidebar', 'draw_template_sidebar');

add_action('after_campaign_appeal_message', 'after_appeal');

add_filter('campaign_editor_fields', 'template_fields');
add_filter('campaign_editor_labels', 'template_labels');
add_filter('campaign_editor_defaults', 'template_defaults');
