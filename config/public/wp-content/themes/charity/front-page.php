<?  
include_once(ABSPATH . 'a/api/partner.php');

global $blog_id;
if ($blog_id == 118 && !is_super_admin()) {
  wp_redirect("/staff");die;
}

$template = get_page_template();
if (!empty($template)) {
  include($template);
  exit;
}

global $NO_SIDEBAR;
global $blog_id;

$eid = get_campaign_for_charity($blog_id);
if ($eid > 0 && !($_GET['_bare'] == 'yes')) {
  init_events(); // Initialize the Event type - it's normally only available on home site

  global $BLOG_ID, $blog_id;
  $BLOG_ID = $blog_id;

  global $event_id, $post;
  $event_id = $eid;

  // Switch to act as main site - restore blog after footer
  switch_to_blog(1);
  add_action('wp_footer', 'restore_current_blog', 0);

  wp_reset_query();
  query_posts("post_type=event&p=$eid");
  $post = get_post($eid);
  setup_postdata($post);

  include(ABSPATH . "/wp-content/themes/syi/campaign.php");
  die;
}


$partner = PartnerApi::getOne(array(
  'id' => $blog_id,
  'view' => 'gallery'
));



$NO_SIDEBAR = true;
global $typekit;
$typekit = "nbw4bxb";

$css = apply_filters('get_css_dir','');
$js = apply_filters('get_js_dir','');
wp_enqueue_style('qtip', "$css/jquery.qtip.css");
wp_enqueue_style('campaign', "$css/campaign.css");
wp_enqueue_style('syi-default', "/themes/default.css");
wp_enqueue_script('qtip', "$js/jquery.qtip.pack.js");
wp_enqueue_script('cgw', "$js/campaign-gallery.js");
wp_enqueue_script('youtube-api', 'http://www.youtube.com/player_api');
//add_action('facebook_meta', 'campaign_facebook_meta');
add_filter('body_class','charity_skin_body_class');
//add_action('syi_meta_tags', 'campaign_meta_tags');
add_action('wp_head', 'draw_custom_charity_skin');
//add_filter('wp_title', 'campaign_title');

get_header(); 
?>
<style>
.campaign-page { 
  margin: 0;
}
.campaign-content {
  width: 720px;
}
.campaign-sidebar .promo {
  font-size: 90%;
  color: #666;
  padding: 20px 30px;
}
.gift-section {
  margin: 30px 0 0;
}
#gallery .gallery-left {
  width: 660px;
  margin: 0 25px;
  padding: 0;
  float: none;
}
.gallery .items .selected {
  box-shadow: none;
  margin: 0;
  background: white;
  border-radius: 0;
}
#gift-details {
  border-width: 1px 0;
}
.gift-paging {
  top: auto;
}

/* TODO: move these */

.charity-page h2 {
  font-family: "freight-sans-pro-1","freight-sans-pro-2",'Gill Sans / Gill Sans MT','Trebuchet MS',Helvetica,sans-serif;
  font-size: 20pt;
  color: #F47C20;
}
.campaign-content h2.section-header {
  margin: 0 0 20px 30px;
  padding: 0;
}
.charity-page .stories-section h2 {
  margin-left: 30px;
}
.gallery-widget .gallery-right {
  padding: 0;
}
.campaign-comments {
  margin-top: 30px;
  padding-top: 30px;
}

/*
.charity-page .give-gift .actions {
  margin-left: 20px;
}
.charity-page .give-gift .images {
  position: absolute;
  top: 60px;
  right: 120px;
}
.charity-page .give-gift .big-pic {
  -webkit-transform: rotate(0deg);
}
.charity-page .give-gift .big-pic img {
  width: 160px;
  height: 120px;
}
.charity-page .give-gift .promo {
  margin: 10px 0 0;
}
*/

</style>
<?
?>
<section class="profile-panel">
  <? // draw_sharing_horizontal(); ?>
  <section id="appeal" class="appeal page-content">
    <div class="based" style="clear:both; position:relative;">
      <? draw_if_showing($partner->partner_page, "header", $partner->gallery, "header"); ?>
    </div>
    <? draw_if_showing($partner->partner_page, "quickfacts", $partner->gallery, "quickfacts"); ?>
  </section>
</section>
<div class="campaign-page">
  <div class="campaign-sidebar">
    <? 
    draw_if_showing($partner->partner_page, "certifiedorg", $partner->gallery, "certifiedorg");
    if (is_showing($partner->partner_page, "activity", TRUE)) {
      syi_progress_widget(array(
        'blog_id' => $partner->blog_id,
        'title' => "Latest activity",
        'avatars' => TRUE,
        'limit' => 100
      ));
    }
    ?>
  </div>
  <div class="campaign-content">
    <? 
    if (is_showing($partner->partner_page, "gifts", TRUE)) {
      ?><div class="gift-section"><?
      Widget::gift_browser(array(
        'title' => ($blog_id == 88) ? "Give a gift.  Change a girl's life." : 'Give a gift, get a story of the life you change!',
        'blog_id' => $blog_id
      ));
      ?></div><?
    } 

    if (is_showing($partner->partner_page, 'stories', TRUE))
      syi_stories_section();

    if (is_showing($partner->partner_page, 'comments', TRUE)) {
      ?><div class="campaign-comments"><?

      // Load the comments title with fallback to legacy values
      $cmt = section_value($partner->partner_page, 'comments', 'title', "Tell [name] why you love this cause!");

      $name = "us"; // Could be customized
      $cmt = str_replace("[name]", xml_entities($name), $cmt);
      $cmt = str_replace("you love ", "<b>you love</b> ", $cmt);
      $cmt = str_replace("love", '<img src="http://seeyourimpact.org/wp-content/images/heart.png" style="vertical-align: middle;">', $cmt);

      ?><h2><?= $cmt ?></h2><?
      syi_fb_comments(array(
        'width' => 600,
        'no_header' => true,
        'compat' => false
      )); 
      ?></div><?
    }
    ?>
  </div>
</div>
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
get_footer(); 

function draw_custom_charity_skin() {
  global $charity_theme;

  if (empty($charity_theme))
    return;

  $file = ABSPATH . "themes/$charity_theme.css";
  if (!file_exists($file))
    return;

  ?><style><?
  echo file_get_contents($file);
  ?></style><?
}

function charity_skin_body_class($classes) {
  global $charity_theme;

  if (!empty($charity_theme))
    $classes[] = "theme-$charity_theme";
  return $classes;
}
