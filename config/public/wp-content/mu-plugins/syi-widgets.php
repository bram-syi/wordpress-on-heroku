<?php
/*
Plugin Name: SeeYourImpact Widgets
Plugin URI: http://seeyourimpact.org
Description: Widgets for SeeYourImpact
Version: 1.0
Author: Steve Eisner
Author URI: http://seeyourimpact.org
*/

define('FB_PROFILE_ID', "61980289979");
define('FB_APP_ID', "188486545854");
define('FB_USE_JS', true);

function syi_widgets_init() 
{
  register_widget('PromoWidget');

  function syi_spacer_widget() {
    ?><div class="widget spacer"></div><?
  }
  wp_register_sidebar_widget('syi-spacer', __('SYI: Spacer'), 'syi_spacer_widget');


  function syi_share_widget($args, $return=false) {
    global $siteinfo;

    extract($args);

    if(!isset($url))
    $url = urlencode($siteinfo->siteurl);
    if(!isset($title))
    $title = urlencode(get_bloginfo('name') . ': ' . get_bloginfo('description'));

    if(!isset($class)) $class = 'button blue-button medium-button share share-fb';
    if(!isset($style)) $style = '';

    if(isset($button_only)) {
      $ret = '<a class="'.$class.'" style="'.$style.'" href="http://www.facebook.com/sharer.php?u='.$url.'&t='.$title.'"><img src="' . __C('images/facebook_24.png') .'" width="24" height="24" title="Share on Facebook"/></a>'.
        '<a class="'.$class.'" style="'.$style.'" href="http://twitter.com/home?status='.$title.'+'.$url.'"><img src="' . __C('images/twitter_24.png') . '" width="24" height="24" title="Share on Twitter"/></a>';
    }else{
      $ret =
        '<div class="widget share-widget"><h3>Spread the word!</h3>
        <p>100% of your donation goes to the charity, and you\'ll see exactly how it was spent.</p>
        <a class="'.$class.'" style="'.$style.'" href="http://www.facebook.com/sharer.php?u='.$url.'&t='.$title.'"><img src="' . __C('images/facebook_24.png') . '" width="24" height="24" title="Share on Facebook"/>Tell your Friends!</a>
        <a class="'.$class.'" style="'.$style.'" href="http://twitter.com/home?status='.$title.'+'.$url.'"><img src="' . __C('images/twitter_24.png') . '" width="24" height="24" title="Share on Twitter"/>Tweet about us!</a>
        </div>';
    }

    if($return) return $ret; else echo $ret;
  }
  wp_register_sidebar_widget('syi-share', __('SYI: Share FB/Twitter'), 'syi_share_widget');


  function syi_fb_activity_widget($args) {
    ?><div class="widget fb-activity-widget"><?
    if (FB_USE_JS) {
      ?><fb:activity width="250" header="false" border_color="white" recommendations="false"></fb:activity><?
    } else {
      $url = "http://seeyourimpact.org";
      ?><iframe src="http://www.facebook.com/plugins/activity.php?site=<?=$url?>&amp;width=300&amp;height=300&amp;header=true&amp;colorscheme=light&amp;recommendations=false" scrolling="no" frameborder="0" allowTransparency="true"></iframe><?
    }
    ?></div><?
  }
  wp_register_sidebar_widget('syi-fb-activity', __('SYI: Facebook Activity'), 'syi_fb_activity_widget');

  function syi_fb_like_box($args) {
    if (!is_page())
      return;

    ?><div class="widget fb-like-widget">
      <fb:like-box href="http://facebook.com/seeyourimpact" width="240" height="340" show_faces="true" stream="false" header="false"></fb:like-box>
    </div><?
  }
  wp_register_sidebar_widget('syi-fb-like-box', __('SYI: Facebook Like Box'), 'syi_fb_like_box');


}

class PromoWidget extends WP_Widget {
  function PromoWidget() {
    $widget_ops = array('classname' => 'promo-widget', 'description' => 'Display a promotion', 'promo-widget' );
    $control_ops = array('id_base' => 'promo-widget');
    $this->WP_Widget('promo-widget', 'SYI: Promo Widget', $widget_ops, $control_ops);
  }

  function form( $instance) {
    $defaults = array(
      'cols' => 3,
      'banner' => false
    );
    $instance = wp_parse_args((array)$instance, $defaults);
      
    // outputs the options form on admin
    $promoslug = esc_attr($instance['promoslug']);
    $cols = esc_attr($instance['cols']);
    $banner = esc_attr($instance['banner']);

    ?>
      <p>
        <label for="<?= $this->get_field_id('promoslug'); ?>"><? _e('Promotion:'); ?> <input class="widefat" id="<?= $this->get_field_id('promoslug'); ?>" name="<?= $this->get_field_name('promoslug'); ?>" type="text" value="<?= esc_attr_e($promoslug); ?>" /></label>
        <label for="<?= $this->get_field_id('cols'); ?>"><? _e('Width:'); ?> <input class="widefat" id="<?= $this->get_field_id('cols'); ?>" name="<?= $this->get_field_name('cols'); ?>" type="text" value="<?= esc_attr_e($cols); ?>" /></label>
      </p>
    <? 
  }

  function drawme($args, $instance) {
    extract($args);

    $promoslug = $instance['promoslug'];

    ?><p><?
    $ret = draw_promo_content($promoslug, 'h3', false);
    ?></p><?

    return $ret;
  }

  function update($new_instance, $old_instance) {
    // processes widget options to be saved
    $instance = $old_instance;
    $instance['promoslug'] = strip_tags($new_instance['promoslug']);
    $instance['cols'] = strip_tags($new_instance['cols']);
    $instance['banner'] = strip_tags($new_instance['banner']);
    return $instance;
  }

  function widget($args, $instance) {
    extract($args);
    $cols = intval($instance['cols']);
    if ($cols == 0) $cols = 2;
    
    $banner = ($instance['banner'] == true) ? "banner-widget" : "";
    echo '<div class="widget promo-widget '. $instance['promoslug'] .'-widget '.$banner.' widget' . $cols . '"><div class="interior">';
    $this->drawme($args, $instance);
    echo '</div></div>';
  }

}

function promo_widget($args)
{
  extract($args);

  draw_promo_content($promo);
}


function stories_widget($args)
{
  extract($args);

  $stories = null;
  if ($limit == 0) {
    $counts = array(0,0,2,4,6,6,8);
    $limit = $counts[$cols];
  }

  if(!isset($featured_only)) 
    $featured_only = false;
  if(!isset($order)) 
    $order = "RAND()";

  if($order == "RAND()" || $featured_first != FALSE)
    $order = "ds.featured = 1 DESC,$order";
  
  // specify blog_id OR tags
  if (!empty($ids)) {
    //manual override, most likely for main site
    $stories = get_stories_by_ids($ids, $limit);
  } else if ($event_id > 0) {
    $stories = get_stories_by_event($event_id, $limit, 'ds.post_modified DESC');
  } else if ($user_id > 0) {
    $stories = get_stories_by_user($user_id, $limit, 'ds.post_modified DESC');
  //} else if ($donor_id > 0) {
  // $stories = get_stories_by_donor($donor_id, $limit, 'ds.post_modified DESC');
  } else if ($blog_id > 0) {
    //for sub charity site, order by date
    $stories = get_stories_by_charity($blog_id, $limit, $order);
  } else {
    //for main site
    $stories = get_stories_by_tag($tag, $limit, $featured_only, $order);
  }

  if (count($stories) == 0)
    return '';

  draw_stories($stories, $large);
}

function posts_widget($args)
{
  extract($args);

  $posts = null;
  if ($limit == 0) {
    $counts = array(0,0,2,2,4,4,6);
    $limit = $counts[$cols];
  }

  // specify blog_id OR tags
  if (!empty($ids)) {
    //manual override, most likely for main site
    $posts = get_posts_by_ids($ids, $limit);
  } else if ($blog_id > 1) {
    //for sub charity site
    switch_to_blog($blog_id);
    $posts = get_posts_by_charity($blog_id, $limit, 'post_modified DESC');
  } else {
    //for the main site and cause article
    switch_to_blog(1);
    if (empty($tag)) 
      $posts = get_posts_by_charity(1, $limit);
    else
      $posts = get_posts_by_tag($tag, $limit);
  }

  if (count($posts) == 0)
    return '';

  draw_posts($posts);
  restore_current_blog();
}

function gifts_widget($args) {
  global $siteinfo, $GIFTS_V2;
 
  extract($args);
  $gifts = null;
  if ($limit == 0) {
    $limit = 20;
  }

  if (!empty($ids)) {
    //manual override, most likely for main site
    $gifts = get_gifts_by_ids($ids, $limit);
  } else if ($event_id > 0) {
    //for event
    $gifts = get_gifts_by_event($event_id, $limit);
  } else if ($blog_id > 0) {
    //for sub charity site
    $gifts = get_gifts_by_charity($blog_id, $limit);
  } else {
    //for main site
    $gifts = get_gifts_by_tag($tag, $limit);
  }

  if (count($gifts) == 0)
    return '';

  ?>
  <p class="expander-label expander if-collapsed">Show gift options &raquo;</p>
  <? if ($show_all == true) { 
    $tag = $tag == 'featured' ? '' : "#tags=$tag";
    ?>
    <a style="float:right;margin-top:-45px;margin-right:20px;" href="<?= $siteinfo->siteurl ?>/give/<?=$tag?>" class="button green-button medium-button">See all gift options &raquo;</a>
  <? } ?>
  <div class="gifts-frame home-frame" style="position: absolute; height: 500px; width: 710px;">
    <div class="next nav notyet ui-x"><span>next</span> &gt;</div>
    <div class="prev nav notyet ui-x">&lt; <span>prev</span></div>
  </div>
  <div class="gifts-v3 scrollable gift-row if-expanded slide <? if($no_ajax) echo 'no-ajax'; ?>">
    <div class="items if-expanded" style="position: relative;">
      <? draw_gifts($gifts); ?>
    </div>
  </div>
  <div class="gift_details"></div>

  <?
}

//
// arguments:
// 'v2' = true --> new-style GIVE buttons
// 'header' = true --> big header
// 'controls' = true --> menu controls
//

function gift_browser_widget($args) {
  global $REGIONS, $CAUSES, $FOCUS, $GIFTS_V2;

  extract($args);
  $preload = $preload || !(empty($causes) && empty($regions) && empty($focus) && empty($cost)); 

  if ($preload) {
    $args['tags1'] = $causes;
    $args['tags2'] = $regions;
    $args['tags3'] = $focus;
    $gifts = list_gifts($args);
    $args['gifts'] = $gifts;

    $c = count($gifts['items']);
    if ($shrink && ($c == 0))
      return FALSE;
  }
?>

<? if ($header == true) { ?>
  <div class="widget widget6 gift_browser_widget headline-widget" style="overflow:hidden;">
    <h2 style="text-align: center; position: relative; z-index: 20">Give one of these life-changing gifts</h2>
  </div>
<? } else if ($custom_header) {
    print $custom_header;
  }
  else if ($custom_header_but_campaign_style) { ?>
<h2 class="page-title" style="margin-left:1em; margin-bottom:-1em;"><?= $custom_header_but_campaign_style ?></h2>
<?
  }

  if ($controls == true) { ?>
  <div id="campaign-gift-tags" class="gift-browser-menu gift-tags evs">
    <div class="left menu" style="width:auto;">Click to:</div>
    <div class="left menu"><label class="top"><u>Choose a cause</u> <span class="arrow">&#9660;</span></label>
      <ol id="causes">
        <? foreach ($CAUSES as $tag=>$name) { ?>
          <a id="tag-<?=$tag?>" class="item gift-tag cause-gift-tag ev"><input id="choose-<?= $tag ?>" type="checkbox" name="tags[]" value="<?= $tag ?>" /><label for="choose-<?= $tag ?>"> <?= $name ?></label></a>
        <? } ?>
     </ol>
    </div>
    <div class="left menu"><label class="top"><u>Change a life</u> <span class="arrow">&#9660;</span></label>
      <ol id="people">
        <? foreach ($FOCUS as $tag=>$name) { ?>
          <a id="tag-<?=$tag?>" class="item gift-tag focus-gift-tag ev"><input id="choose-<?= $tag ?>" type="checkbox" name="tags[]" value="<?= $tag ?>" /><label for="choose-<?= $tag ?>"> <?= $name ?></label></a>
        <? } ?>
     </ol>
    </div>
    <div class="left menu"><label class="top"><u>Help a region</u> <span class="arrow">&#9660;</span></label></label>
      <ol id="regions">
        <? foreach ($REGIONS as $tag=>$name) { ?>
          <a id="tag-<?=$tag?>" class="item gift-tag region-gift-tag ev"><input id="choose-<?= $tag ?>" type="checkbox" name="tags[]" value="<?= $tag ?>" /><label for="choose-<?= $tag ?>"> <?= $name ?></label></a>
        <? } ?>
     </ol>
    </div>
  </div>
<? } ?>

  <div id="gift-browser" class="panel current-panel gift-browser gifts-v3 evs">
    <div id="gift-sets" class="panel current-panel gift-browser-panel">
<? if ($empty_html) { ?>
      <div class="empty-gifts-box"><?= $empty_html ?></div>
<? } ?>
    </div>
    <div id="gifts" class="panel gift-browser-panel gifts ajax v2 <? if ($preload) echo ' preloaded'; if ($shrink && ($c < 4)) echo ' gift-browser-shrunk'; ?>">
      <div class="gift-list scrollable">
        <div class="items">
          <? if ($preload) {
              $p = draw_gift_pages($args);
          } ?>
        </div>
      </div>
      <div id="gift-paging" class="gift-paging">
        <a class="invisible next-gifts right paging button medium-button green-button ev">more gifts &raquo;</a>
        <a class="invisible prev-gifts right paging button medium-button green-button">&laquo; back</a>
        <div class="pages" style="display:none;"></div>
      </div>
    </div>
    <div id="gift-details" class="panel gift-browser-panel"></div>
  </div>

<? if ($header == true) { ?>
</div>
<? } ?>
<script type="text/html" id="story_template">
<?
draw_story(array(
  'blog_id' => '${blog_id}',
  'title' => '${post_title}',
  'ref' => '${ref}',
  'url' => '${guid}',
  'excerpt' => isset($story) ? $story->post_excerpt : '',
  'img' => '${post_image}'
));
?>
</script>
<script type="text/html" id="funded_gift">
  <div class="empty-gifts-box">
    <? draw_promo_content('gift-funded', 'h2'); ?>
  </div>
</script>
<?
  global $GIFTS_LOC;
  $GIFTS_LOC='${itemtag}';
  json_template('draw_gift_details',
    array('imageUrl','headline','description','unitAmount','id','excerpt','siteurl','title','excerpt','image'));
  json_template('draw_var_gift_details',
    array('imageUrl','headline','description','unitAmount','id','excerpt','siteurl','title','excerpt','image'));
  json_template('draw_agg_gift_details',
    array('imageUrl','headline','description','unitAmount','id','excerpt','siteurl','title','excerpt','image',
      'towards_gift_id','master_amount','master_current','master_name','full_count','left_count','displayName',
      'current_percent','left_amount','current_amount'));

}

function profile_link_shortcode($args) {
  extract(shortcode_atts(array(
    'page' => '',
    'action' => '',
  ), $args));

  return get_member_link(0, $page, $action);
}

function shortcode_widget($name, $args, $content = NULL) {
  ob_start();

  if (isset($args['h2']))
    $h2 = $args['h2'];
  $is_widget = as_bool($args['widget']);
  if ($is_widget) { ?><section class="widget widget6"><? }
  call_user_func($name, $args, $content);
  if ($is_widget) { ?></section><? }

  $str = ob_get_contents();
  ob_end_clean();
  return $str;
}

function gift_browser_shortcode($args) {
  $args['v2'] = true;
  $args['header'] = false;
  return shortcode_widget('gift_browser_widget', $args);
}
function stories_shortcode($args) {
  return shortcode_widget('stories_widget', $args);
}
function fb_comments_shortcode($args) {
  return shortcode_widget('syi_fb_comments', $args);
}
function syi_progress_shortcode($args) {
  return shortcode_widget('syi_progress_widget', $args);
}
function syi_share_shortcode($args) {
  return shortcode_widget('syi_share_section', $args);
}

function youtube_shortcode($args) {

  $width = 295;
  $height = 230;
  extract($args);

  return '<iframe class="video" width="'. $width .'" height="'. $height .'" src="http://www.youtube.com/embed/'. $video .'?rel=0&showinfo=0&showsearch=0&modestbranding=1&autohide=1&wmode=transparent" frameborder="0" allowfullscreen=""></iframe>';
}

function draw_stories_section($args = NULL) {
  global $event_id;

  $count = 6;
  if ($args !== NULL)
    extract($args);

  $posts = get_cgw_posts($event_id);

  if (count($posts) == 0) {
    show_sample_stories($event_id, $count, FALSE);
    return;
  }

  $stories = get_stories_by_event($event_id, $count, 'ds.post_modified DESC');
  if (count($stories) == 0)
    return;

  ?><div class="sample-stories"><?
  draw_stories($stories);
  ?></div><?
}
function campaign_stories($args) {
  return shortcode_widget('draw_stories_section', $args);
}
add_shortcode('campaignstories','campaign_stories');





function articles_widget($args){
  extract($args);
  $articles = null;
  if ($limit == 0) {
    $counts = array(0,0,2,2,4,4,6);
    $limit = $counts[$cols];
  }

  if (!empty($ids)) {
    //manual pick, most likely for main site
    $articles = get_posts("post_type=article&orderby=rand&include='".$ids."'&numberposts=".$limit);
  } else if ($blog_id > 0) {
    //pick the articles that relates to the blog
    switch_to_blog($blog_id);

  } else {
    $articles = get_posts("post_type=article&orderby=rand&numberposts=".$limit);
  }

  if (count($articles) == 0)
    return '';

  draw_articles($articles);
  //restore_current_blog();

}

function causes_widget($args) {
  extract($args);
  global $wpdb;

  $labels = ($mode == 'sidebar');

  $sector = intval($wpdb->get_var("SELECT ID from $wpdb->posts WHERE post_name='sector' and post_type='article'"));
  $people = intval($wpdb->get_var("SELECT ID from $wpdb->posts WHERE post_name='people' and post_type='article'"));
  $region = intval($wpdb->get_var("SELECT ID from $wpdb->posts WHERE post_name='region' and post_type='article'"));

?>
  <div class="causes-widget">
  <? if ($labels) { echo '<li class="strong">Choose a cause</li>'; }?>
  <ul class="cause-col1">
    <? wp_list_pages(array( 'depth' => 1, 'sort_column' => 'post_title', 'title_li' => null, 'post_type' => 'article', 'child_of' => $sector, 'link_after' => $link_after )); ?>
  </ul>
  <? if ($labels) { echo '<li class="strong">Change a life</li>'; }?>
  <ul class="cause-col2">
    <? wp_list_pages(array( 'depth' => 1, 'sort_column' => 'post_title', 'title_li' => null, 'post_type' => 'article', 'child_of' => $people, 'link_after' => $link_after )); ?>
  </ul>
  <? if ($labels) { echo '<li class="strong">Support a region</li>'; }?>
  <ul class="cause-col3">
    <? wp_list_pages(array( 'depth' => 1, 'sort_column' => 'post_title', 'title_li' => null, 'post_type' => 'article', 'child_of' => $region, 'link_after' => $link_after )); ?>
  </ul>
  </div>
<?
}

class ExploreWidget extends PromoWidget {
  function PromoWidget() {
    $widget_ops = array('classname' => 'explore-widget', 'description' => 'Explore menu', 'explore-widget' );
    $control_ops = array('id_base' => 'explore-widget');
    $this->WP_Widget('explore-widget', 'SYI: Explore Widget', $widget_ops, $control_ops);
  }

  function drawme($args) {
    extract($args);

    echo 'YEAH!';
  }
}

function syi_widgets_fb_init() {

/*
  if (FB_USE_FBXML) {
? >
<script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US"></script>
<script type="text/javascript">FB.init("1690883eb733618b294e98cb1dfba95a");</script>
<?
} else {
? >
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({appId: '<?= FB_APP_ID ? >', status: true, cookie: true, xfbml: true});
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
<?
  }
*/
}

function share_widget($args){
  extract($args);
  global $post;
  global $wpdb;

  if($title != '' && $link != ''){

?><div class="share-widget-post-<?=$size?>" ><?
  if($size == 'medium'){

  $path = __C('themes/syi/images/SocialMediaBookmarkIcon/32');
echo '<a href="http://www.facebook.com/share.php?t='.$title.'&amp;u='.$link.'" title="Share this on Facebook"><img src="'.$path.'/facebook.png" alt="Share this on Facebook" border="0"/></a>'
.'<a href="http://twitter.com/home/?status='.$title.'+'.$link.'" title="Share this on Twitter"><img src="'.$path.'/twitter.png" alt="Share this on Twitter" border="0"/></a>'
.'<a href="http://google.com/reader/link?title='.$title.'+'.$link.'" title="Share this on Google"><img src="'.$path.'/google.png" alt="Share this on Google" border="0"/></a>';

  }else if($size == 'small'){

  $path = __C('themes/syi/images/SocialMediaBookmarkIcon/16');
echo 'Share this on: <a href="http://www.facebook.com/share.php?t='.$title.'&amp;u='.$link.'" title="Share this on Facebook"><img src="'.$path.'/facebook.png" alt="Share this on Facebook" border="0"/></a>'
.'<a href="http://twitter.com/home/?status='.$title.'+'.$link.'" title="Share this on Twitter"><img src="'.$path.'/twitter.png" alt="Share this on Twitter" border="0"/></a>'
.'<a href="http://google.com/reader/link?title='.$title.'+'.$link.'" title="Share this on Google"><img src="'.$path.'/google.png" alt="Share this on Google" border="0"/></a>';

  }else if($size == 'button'){
    $path = __C('themes/syi/images/SocialMediaBookmarkIcon/buttons');
?>
<p><strong>Feeling inspired, touched, or excited by what you read?</strong> Please don't keep it to yourself!<br/>
Click on any of these button below to <em>donate</em> your social status by sharing the link to this page:
</p>
<a href="http://www.facebook.com/share.php?t=<?=$title?>&amp;u=<?=$link?>" title="Share this on Facebook"><img src="<?=$path?>/facebook.png" alt="Share this on Facebook" border="0"/></a>
<a href="http://twitter.com/home/?status=<?=$title?>+<?=$link?>" title="Share this on Twitter"><img src="<?=$path?>/twitter.png" alt="Share this on Twitter" border="0"/></a>
<?

  }else{

  echo '<a href="http://www.facebook.com/share.php?t='.$title.'&amp;u='.$link.'" title="Share this on Facebook">Facebook</a> '
.'<a href="http://twitter.com/home/?status='.$title.'+'.$link.'" title="Share this on Twitter">Twitter</a> '
.'<a href="http://google.com/reader/link?title='.$title.'+'.$link.'" title="Share this on Google">Google</a> <br/>'
.'<a href="http://facebook.com/SeeYourImpact/" title="Become our fan on Facebook">Become our fan on Facebook</a>'
.'<a href="http://twitter.com/SeeYourImpact/" title="Follow us on Twitter">Follow us on Twitter</a>';
  }
  }

  ?></div><?
}

function charities_widget($args){
  extract($args);
  $blogs = null;
  $blogs = get_charities_by_tag($tag);

  if (count($blogs) == 0)
    return '';

  //print_r($blogs);
  draw_charities($blogs);
}

function draw_charities($blogs){
  foreach($blogs as $c){
    draw_charity($c);
  }
}

function slideshow_widget($args) {
  extract($args);

  $stories = null;
  if ($limit == 0) {
    $counts = array(0,0,2,4,6,6,8);
    $limit = $counts[$cols];
  }

  if(!isset($featured_only)) $featured_only = false;

  if (!isset($event_id))
    $event_id = 0;
  
  // specify blog_id OR tags
  if (!empty($ids)) {
    //manual override, most likely for main site
    $stories = get_stories_by_ids($ids, $limit);
  } else if ($event_id > 0) {
    $stories = get_stories_by_event($event_id, $limit, 'ds.post_modified DESC');
  } else if ($user_id > 0) {
    $stories = get_stories_by_user($user_id, $limit, 'ds.post_modified DESC');
  //} else if ($donor_id > 0) {
  // $stories = get_stories_by_donor($donor_id, $limit, 'ds.post_modified DESC');
  } else if ($blog_id > 0) {
    //for sub charity site, order by date
    $stories = get_stories_by_charity($blog_id, $limit, 'ds.post_modified DESC');
  } else {
    //for main site
    $stories = get_stories_by_tag($tag, $limit, $featured_only);
  }

  if (count($stories) == 0) {
    ?>
    <div class="empty-impact">
      <? if (bp_is_my_profile()) { ?>
        <h2>This is your Impact Page</h2>
        <p>About 2 weeks after you give, we'll email the picture of the real person whose life you changed. We'll also send you a story, detailing exactly the difference your gift made.</p>
        <p>All of your impact stories will be collected here.  Why not <a href="/give/"><u>create a story</u></a> today?</p>
      <? } else { ?>
        <? if ($user_id > 0) { $name = get_displayname($user_id, true); ?>
          <h2>See <?= xml_entities($name) ?>'s Impact</h2>
        <? } ?>
        <p>About 2 weeks after a donation, we email the picture of the real person whose life was changed. 
          We also send a story, detailing exactly the difference the gift made.</p>
        <p>When the first stories arrive, we'll show them on this page!  Please check back soon.</p>
      <? } ?>
    </div>
    <?
    return;
  }

  ?><section class="story-slideshow ui-x"><?
  if (!empty($title)) {
    ?><h3><?=htmlspecialchars($title) ?></h3><?
  }
  $inr = 0;
  foreach ($stories as $story) {
    if ($inr == 10) { $inr = 0; echo '</div></div>'; }
    if ($inr == 0) { ?><div class="scrollable"><div class="items"><? }
    $inr++;

    ?><a class="slide" id="story-<?=$story->ref?>" href="<?=$story->guid ?>"><?
    draw_thumbnail($story->blog_id, $story->post_image, 120,120, null, $story->post_title);
    ?></a><?
  }
  ?></div></div><?
  ?></section><?
  ?><div id="show_panels"><div class="frame-shadow"></div></div><?
}

function give_same_gift($post, $is_single = false, $_blog_id = 0, $button_only = false, $return = false) {
  global $siteinfo;
  global $wpdb;
  global $blog_id;

  if($_blog_id == 0) {$_blog_id = $blog_id;}
  $post_id = isset($post->ID) ? $post->ID : intval($post);

  $gift = $wpdb->get_row($wpdb->prepare(
    " SELECT g.* FROM donationGifts dg "
    ." JOIN gift g ON g.id = dg.giftID AND g.active=1 "
    ." WHERE g.blog_id = %d AND dg.story = %d ",
      $_blog_id, $post_id), ARRAY_A);

  if ($gift == NULL || $gift['unitsWanted'] <=0 || !$gift['active'])
    return;

  //if this is an agg var gift, replace with the parent gift
  $tg = get_avg_tgi($gift['id'],true,true);
  if ($tg != NULL) {
    $gift = $tg;      
  }

  global $GIFTS_LOC;

  if($button_only) {
    $ret = '<a class="button orange-button" href="' . pay_link($gift['id'], $GIFTS_LOC) .'">Give&nbsp;$' . stripslashes($gift['unitAmount']) . '&nbsp;&raquo;</a>';
  } else {
    if ($is_single == true) {

      $tag = get_post_meta($post_id, 'related_tag', true);
      if (!empty($tag)) {
        ?><div class="widget widget6 gift_browser_widget campaign-content"><?
        gift_browser_widget(array(
          'page_title' => 'Give a gift, get a story of the life you change!',
          'header' => false,
          'preload' => true,
          'causes' => array($tag)
        ));
        ?></div><?
        return;
      } 

      $ret = '<h3>Help create more stories like this one!</h3>
        <table class="give-this-gift" width="100%" border="0"><tr><td>
          <a class="button orange-button" href="' . pay_link($gift['id'], $GIFTS_LOC) .'">Give&nbsp;$' . stripslashes($gift['unitAmount']) . '&nbsp;&raquo;</a>
        </td><td width="100%">' . htmlspecialchars($gift['excerpt']) . '</td></tr></table>';
    } else {
      $ret = '<table class="give-this-gift" width="100%" border="0"><tr><td>
        <a class="button orange-button" href="' . details_link($gift['id'], $GIFTS_LOC) . '">Give&nbsp;this&nbsp;gift&nbsp;&raquo;</a>
        </td><td width="100%"><b>' . htmlspecialchars($gift['excerpt']) . '</b></td></tr></table>';
    }
  }

  if($return) return $ret; else echo $ret;
}

function share_this_gift($post, $is_single = false) {
  if($is_single == true){
    share_widget(array(
      'title'=>'Check out this article',
      'link'=>$post->guid,
      'size'=>'button'
    ));
  }
}

function syi_big_share_widget($args) {
  global $post, $siteinfo;
  extract($args);

  $url = urlencode($siteinfo->siteurl);
  if ($post != null) {
    $url = get_post_permalink($post->ID);
    $title = urlencode(get_bloginfo('name') . ': ' . $post->post_title);
  } else {
    $title = urlencode(get_bloginfo('name') . ': ' . get_bloginfo('description'));
  }
?>
  <div class="widget big-share-widget">
    <a href="http://www.facebook.com/sharer.php?u=<?=$url?>&t=<?=$title?>"><img src="<?= __C('templates/facebook.png') ?>" title="Share on Facebook"/></a><a href="http://twitter.com/home?status=<?=$title?>+<?=$url?>"><img src="<?= __C('templates/twitter.png') ?>" title="Share on Twitter"/></a>
  </div>
<?
}
wp_register_sidebar_widget('syi-share-big', __('SYI: Big Share Buttons'), 'syi_big_share_widget');

function syi_progress_widget($args) {
  global $post, $wpdb;
  global $event_id;

  $avatars = TRUE;
  if (is_array($args))
    extract($args);

  if (!isset($donations))
    $donations = NULL;

  if (empty($blog_id))
    $blog_id = 1;
  if ($event_id == 0 && $post->post_type == 'event')
    $event_id = $post->ID;
  if ($limit == 0)
    $limit = 70;
  if ($empty_message === NULL)
    $empty_message = "Be the first to make a donation!";
  if ($donations === NULL) {
    if ($event_id > 0)  {
      $donations = get_campaign_activities($event_id,$limit);
    } else if (!empty($campaign)) {
      $donations = get_campaign_activities($campaign, $limit);
    } else if ($blog_id > 1) {
      $donations = get_donation_activities($blog_id,$story_id,$limit);
    }
  }
  if (!empty($avatars))
    $avatars = as_bool($avatars);

  $tips = should_include_tips($event_id);
  $show_last = apply_filters('show_donor_last_names', false) && can_manage_campaign($event_id);

?>
  <div class="widget progress-widget">
    <? do_action('progress_widget_top', $args); ?>
    <div class="interior">
    <? if (isset($see_all) && $see_all) { ?>
      <u class="see-all right block link">see all</u>
    <? } ?>
    <? if (!empty($title)) { ?>
      <h3><?= xml_entities($title) ?></h3>
    <? } ?>
<? 
  if (count($donations) == 0) {
    echo '<div class="empty-progress">' . xml_entities($empty_message) . '</div>';
  } else {
    ?><table class="donation-history" border="0" width="100%"><?
    $lastDate = "";
    foreach ($donations as $d) { 
      if ($tips)
        $d->raised += $d->tip;

      $thisDate = strtotime($d->date);
      $date = date('M j', $thisDate);
      $id = "msg-" . date('Y-m-d-H', $thisDate);
      if ($date == $lastDate) {
        $date = " ";
      } else {
        $lastDate = $date;
      }
      $profile_url = bp_core_get_user_domain($d->user_id);
      $d->show_avatar = $avatars;
      $d->hide_default_avatar = as_bool($hide_default_avatar);
      $d->date = $date;

      if ($d->anonymous) {
        unset($d->firstName);
        unset($d->lastName);
        unset($d->user_id);
        unset($d->donorID);
      }
      if (!$show_last)
        unset($d->lastName);

      // For "don't show $" feature:
      if (!empty($d->activity) && $d->varAmount && !show_money_amounts())
        unset($d->activity);

      do_action('draw_progress_line', $d);
    }
    ?></table><?
  } 
  if ($event_id > 0) {
    draw_invite_link("campaign/".encrypt($event_id));
  }
?>
  </div></div>
<?

}

function get_matching_message($d) {
  $msg = "made a ";
  if (show_money_amounts()) {
    $msg .= as_money($d->raised);
  }
  $msg .= " matching contribution";

  return $msg;
}
add_filter('get_matching_message', 'get_matching_message');

function draw_progress_line($d) {
  if (!empty($d->activity)) {
    $d->activity = stripslashes($d->activity);
  } else if ($d->matched > 0) {
    $d->activity = apply_filters('get_matching_message', $d);
    $d->date = eor(trim($d->date), '<img src="' . __C('images/starburst.gif') . '">');
  } else if ($d->offline > 0) {
    $d->activity = '<div class="offline-amount">';
    if (show_money_amounts()) $d->activity .= as_money($d->offline, '$%.0n');
    $d->activity .= " in offline donations</div>";
    $d->date = "";
  } else {
    $d->activity = "made a ";
    if (show_money_amounts()) $d->activity .= as_money($d->raised, '$%.0n');
    $d->activity .= " contribution";
  }

  $name = trim("$d->firstName $d->lastName");
?>
  <tr><td class="date"><?= $d->date ?></td><td class="donor" width="100%">
    <? if ($d->offline == 0) {
      $drawn = FALSE;
/* work in progress
      if ($d->data != NULL) {
        try {
          $data = json_decode($d->data);
          if (isset($data->user_image)) {
            ?><a class="avatar-link"><div class="user-tag"><img src="<?= esc_url($data->user_image) ?>" class="avatar"></div></a><?
            $drawn = TRUE;
          }
        } catch (Exception $e) { }
      }
*/
      if (!$drawn)
        draw_avatar_box($d->user_id, TRUE, $name, $d->show_avatar, $d->hide_default_avatar);
    } ?>
    <?= $d->activity ?>
    <? if ($d->qty > 1 && !$d->varAmount) echo " (x".intval($d->qty).")" ?><? do_action('progress_widget_row', $d); ?></td>
  </tr>
<?
}
add_action('draw_progress_line','draw_progress_line');

wp_register_sidebar_widget('syi-progress', __('SYI: Progress'), 'syi_progress_widget');
add_shortcode('share','share_widget');
add_shortcode('posts','posts_widget');
add_shortcode('charities','charities_widget');
add_shortcode('gifts','gift_browser_shortcode');
add_shortcode('stories','stories_shortcode');
add_shortcode('fb-comments','fb_comments_shortcode');
add_shortcode('profile-link','profile_link_shortcode');
add_shortcode('sharing','syi_share_shortcode');
add_shortcode('progress','syi_progress_shortcode');
add_shortcode('youtube-video','youtube_shortcode');
add_action('widgets_init', 'syi_widgets_init');
add_action('wp_footer', 'syi_widgets_fb_init');
add_action('syi_after_post', 'share_this_gift', 4, 2);
add_action('syi_after_post', 'give_same_gift', 1, 2);

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define(CGW_IMG_MAIN_W, 250);
define(CGW_IMG_MAIN_H, 250);
define(CGW_IMG_LIST_W, 75);
define(CGW_IMG_LIST_H, 90);
define(CGW_IMG_THUMB_W, 70);
define(CGW_IMG_THUMB_H, 55);
define(CGW_IMG_FULL_W, 400);
define(CGW_IMG_FULL_H, 350);
define(CGW_ADM_IMG_FULL_W, 300);
define(CGW_ADM_IMG_FULL_H, 300);
define(CGW_MAX_MEDIA, 15);
define(CAMPAIGN_POST_TYPE, 'event');

function count_cgw_posts($campaign_id, $story=false) {
  global $wpdb;


  if ($story) {

    $sql = $wpdb->prepare(
    "SELECT COUNT (*) 
     FROM donationStory ds
      JOIN wp_blogs b ON (ds.blog_id = b.blog_id)
      JOIN donationGifts dg ON (dg.blog_id=ds.blog_id AND dg.story=ds.post_id)
      JOIN donation d on d.donationID=dg.donationID
     WHERE NOT(ds.post_image = '')
      AND ((b.public = '1' AND b.archived = '0' AND b.mature = '0' AND b.spam = '0' AND b.deleted ='0'))
      AND dg.event_id = %d
       ",$campaign_id);

  } else {

    $photo_id =  get_post_thumbnail_id($campaign_id);
    $sql = $wpdb->prepare("SELECT COUNT(*) FROM (SELECT DISTINCT
      1 as blog_id, ID as post_id, p.post_title as title, p.post_type as type,
      p.guid as guid, p.post_modified as modified, p.guid as image, p.post_excerpt as excerpt,
      p.post_content as content, p.post_parent as parent
    FROM wp_1_posts p
    WHERE
      ((p.post_type = 'attachment') OR (p.post_type = 'post' AND p.post_status = 'publish'))
      AND p.post_parent = %d AND ID != %d)  AS cgw",$campaign_id,$photo_id);
      
  }
//    return $sql; 
  return $wpdb->get_var($sql);    
}


function handle_username_change ($user, $old_username) {
  global $wpdb;
  $wpdb->query($wpdb->prepare(
    "UPDATE wp_1_posts SET post_name=%s WHERE post_name=%s AND post_type=%s AND post_author=%d",
      $user->user_login, $old_username, CAMPAIGN_POST_TYPE, $user->ID));

//  debug($user." --- ".$old_username,true);
}

add_action('username_changed','handle_username_change', 10, 2);

function get_cgw_posts($campaign_id, $limit = 18, $exclude = null, $admin_mode = false) {
  global $wpdb;
  global $blog_id;


  $campaign_fs = get_post_meta($campaign_id,'syi_featured_story',1);
  if (!empty($campaign_fs)) {
    $campaign_fs = explode("-",$campaign_fs);
    if (count($campaign_fs)!=2) { $campaign_fs=NULL; }
    foreach ($campaign_fs as $k=>$v) {
      $campaign_fs[$k] = intval($v);
    }
  }
  
  if(empty($campaign_fs)) $campaign_fs = array(0,0);
//print_r($campaign_id);

  $photo_id = intval(get_post_thumbnail_id($campaign_id));

  $sql1 = $wpdb->prepare(
    "SELECT DISTINCT
      1 as blog_id, ID as post_id, p.post_title as title, p.post_type as type,
      p.guid as guid, p.post_modified as modified, p.guid as image, p.post_excerpt as excerpt,
    p.post_content as content, p.post_parent as parent
    FROM wp_1_posts p
    WHERE p.post_parent = %d AND ID != %d AND
    ((p.post_type = 'attachment' AND p.post_name LIKE CONCAT(post_parent,'_syicg_%%'))
      OR (p.post_type = 'post' AND p.post_status = 'publish'))
    ORDER BY type, modified LIMIT %d",
    $campaign_id, $photo_id, $limit);

  $sql2 = $wpdb->prepare(
      "SELECT DISTINCT
        ds.blog_id as blog_id, ds.post_id as post_id, ds.post_title as title, 'story' as type,
        ds.guid as guid, ds.post_modified as modified, ds.post_image as image, ds.post_excerpt as excerpt,
        ds.post_excerpt as content, dg.event_id as parent, IF(ds.blog_id=%d AND ds.post_id=%d,1,0) as featured 
      FROM donationStory ds
        JOIN wp_blogs b ON (ds.blog_id = b.blog_id)
        JOIN donationGifts dg ON (dg.blog_id=ds.blog_id AND dg.story=ds.post_id)
        JOIN donation d on d.donationID=dg.donationID
      WHERE NOT(ds.post_image = '')
        AND ((b.public = '1' AND b.archived = '0' AND b.mature = '0' AND b.spam = '0' AND b.deleted ='0'))
        AND (dg.event_id = %d  OR (ds.blog_id=%d AND ds.post_id=%d))
      ORDER BY ds.featured DESC, d.donationDate DESC
      LIMIT %d",
      $campaign_fs[0], $campaign_fs[1], $campaign_id, $campaign_fs[0], $campaign_fs[1], $limit);
  // Get images that user uploaded

  $results = $wpdb->get_results($sql1);
  if(!is_array($results)) $results = array();

  if (!$admin_mode) {
    // Get stories from gifts to this campaign
    $results2 = $wpdb->get_results($sql2);
    while (count($results) < $limit && count($results2) > 0)
      array_push($results, array_shift($results2));
  }

  return $results;
}

function get_media_post($campaign_id, $media_id) {
  global $wpdb;
  $sql = $wpdb->prepare("(SELECT DISTINCT
    1 as blog_id, ID as post_id, p.post_title as title, p.post_type as type,
    p.guid as guid, p.post_modified as modified, p.guid as image, p.post_excerpt as excerpt,
    p.post_content as content, p.post_parent as parent
  FROM wp_1_posts p
  WHERE
    ((p.post_type = 'attachment') OR (p.post_type = 'post' AND p.post_status = 'publish'))
    AND p.post_parent = %d AND ID = %d) ",$campaign_id,$media_id);

  return $wpdb->get_row($sql);      
}

function load_full_gallery_item(&$post) {
  global $wpdb;

  if($post==NULL) return;

  switch ($post->type) {
    case 'story':
      $sql = $wpdb->prepare("
        SELECT
          ds.gift_id as gift_id, ds.item_id as item_id,
          ds.post_modified as modified, ds.post_image as image,
          ds.post_title as title, ds.guid as guid,
          ds.post_excerpt as excerpt,
          ds.post_excerpt as content,
          dg.event_id as parent,
          GROUP_CONCAT(DISTINCT CONVERT(d.donorID, CHAR) SEPARATOR ',') as donor_ids,
          GROUP_CONCAT(DISTINCT CONVERT(dd.user_id, CHAR) SEPARATOR ',') as user_ids,
          g2.displayName as display_name, g2.pluralName as plural_name, g.unitAmount as unit_amount
        FROM donationStory ds
          JOIN donationGifts dg ON (dg.blog_id=ds.blog_id AND dg.story=ds.post_id)
          JOIN gift g ON (dg.giftID = g.id)
          JOIN gift g2 ON (g2.id = IF(g.towards_gift_id > 0, g.towards_gift_id, g.id))
          JOIN donation d ON (dg.donationID = d.donationID)
          JOIN donationGiver dd ON (dd.ID = d.donorID)
        WHERE ds.blog_id=%d AND ds.post_id=%d
        GROUP BY ds.blog_id, ds.post_id",
        $post->blog_id,$post->post_id);
      break;

    case 'post':
      $sql = $wpdb->prepare("
        SELECT
          0 as gift_id, 0 as item_id,
          p.post_modified as modified, p.guid as image,
          p.post_title as title, p.guid as guid,
          p.post_excerpt as excerpt,
          p.post_content as content,
          p.post_parent as parent,
          '' as donor_ids,
          '' as user_ids,
          '' as display_name, '' as plural_name, '' as unit_amount
          FROM wp_1_posts p
          WHERE p.ID=%d",
        $post->post_id);
      break;

    case 'attachment':
      $sql = $wpdb->prepare("
        SELECT
          0 as gift_id, 0 as item_id,
          p.post_modified as modified, p.guid as image,
          p.post_title as title, p.guid as guid,
          p.post_excerpt as excerpt,
          p.post_content as content,
          p.post_parent as parent,
          '' as donor_ids,
          '' as user_ids,
          '' as display_name, '' as plural_name, '' as unit_amount
        FROM wp_1_posts p
        WHERE p.ID=%d",
        $post->post_id);
      break;
  }

  $full_post = $wpdb->get_row($sql);
  if (!$full_post) {
    SyiLog::log('info', "load_full_gallery_item: no rows returned (blog_id: $post->blog_id, post_type: $post->type, post_id: $post->post_id)");
  }

  $post->title = $full_post->title;
  $post->excerpt = $full_post->excerpt;
  $post->content = $full_post->content;
  $post->image = $full_post->image;
  $post->gift_name = $full_post->display_name;
  $post->guid = $full_post->guid;
  $post->parent = $full_post->parent;
//  cmt_dump($sql);
//  $donor_ids = explode(",",$post->donor_ids);
//  $donor_id = intval($donor_ids[0]);

////////////////////////////////////////////////////////////////////////////////

  //get the author or the first donor
  $post->user_ids = explode(",", $full_post->user_ids);
  $post->user_id = intval($post->user_ids[0]);
  $post->user = get_userdata($post->user_id);

////////////////////////////////////////////////////////////////////////////////

//
  if($post->type == 'story' || $post->type == 'post') {

    switch_to_blog($post->blog_id);
    $the_post = get_post($post->post_id);
    $post->permalink = get_post_permalink($post->ID);
    $post->recipient = get_post_meta($post->ID, 'r_Name', true);
    restore_current_blog();

    $post->content = strip_shortcodes(get_excerpt($the_post));
  }
}

function get_full_image($post, $admin_mode = false) {
  if (!$admin_mode) {
    $w = CGW_IMG_FULL_W; $h = CGW_IMG_FULL_H;
  } else {
    $w = CGW_ADM_IMG_FULL_W; $h = CGW_ADM_IMG_FULL_H;
  }

  $media = get_media_link($post->excerpt,'array',true);
    
  if(!$media) {
    $image = make_img(eor($post->image, $post->post_image), $w, $h);
  } else {
    $image = '<a target="_new" href="'.$media['url'].'" class="videobox">'.
      '<div class="'.$media['site'].'-video" id="'.$media['site'].'-'.$media['id'].'" style="width:'.$w.'px;height:'.$h.'px;"></div></a>';
  }
    
  return $image;
}

function draw_gallery_item($post, $full = false, $admin_mode = false, $return = false) {
  $post->user_id = 0;
  if ($full) load_full_gallery_item($post);
  if (!$admin_mode) {
    $post_buttons = syi_share_widget(array(
      'url' => get_permalink($post->post_id),
      'title' => urlencode($post->title),
      'button_only' => 1,
      'class' => '',
      'style' => ''
    ), true);

    if($post->type == 'post')
      $post_buttons .= give_same_gift($post->post_id, false, $post->blog_id, true, true);
    $post_buttons .= '<span class="move-next button green-button small-button right">next &raquo;</span>';
  }

  if ($full) {
    $cl = 'selected loaded';
  }
  else {
    $cl = '';
  }

  $media = get_media_link($post->excerpt,'array',true);

  ?>
  <div class="post item <?=$cl?>" id="item-<?=$post->blog_id.'-'.$post->post_id.'-'.$post->type?>" >
    <div class="pick-me move-next"><div class="me-too"></div></div>

    <div class="item-image">
      <?= $full ? get_full_image($post,$admin_mode) :
        '<div style="width:'.($admin_mode?CGW_ADM_IMG_FULL_W:CGW_IMG_FULL_W).'px;height:'.($admin_mode?CGW_ADM_IMG_FULL_H:CGW_IMG_FULL_H).'px;"></div>' ?>
      <? if ($post->user_id && !$admin_mode) { ?>
        <div class="gave mask"></div>
        <div class="gave">
          <? 
            draw_avatar_box($post->user_id, TRUE, TRUE);
            $count = count($post->user_ids) - 1;
            if ($count == 1)
              echo "and <b>" . xml_entities(get_user_meta($post->user_ids[1], 'first_name', true)) . '</b>';
            else if ($count > 1)
              echo " and $count others";
          ?>
          gave 
          <? if ($post->recipient) echo "<b>" . xml_entities($post->recipient) . "</b>"; ?>
          <?= xml_entities(stripslashes($post->gift_name)) /* a bit of a hack to stripslashes on retrieval */ ?>
        </div>
      <? } ?>

    </div>

    <? if(!$admin_mode) { ?>
    <h3 class="item-title"><?= xml_entities(stripslashes($post->title)) ?></h3>
    <div class="item-content based">
      <? if ($full) { ?>
        <div class="item-excerpt"><?= xml_entities($post->content) ?>
          <? if (!$media) { ?>
            <br><br>
            <a id="read-more" href="<?= esc_url(get_blog_permalink($post->blog_id, $post->post_id)) ?>" class="story-link ev" rel="<?=$post->blog_id?>/<?=$post->post_id?>">read more</a>
          <? } ?>
        </div>
      <? } ?>
      <div class="item-buttons"><?= $post_buttons ?></div>
    </div>
    <? } else { ?>
      <? if ($post->type == 'story') { ?>
      <? } else { ?>
<form method="post" action="" id="media-updater">
<input type="hidden" name="mid" value="<?=$post->post_id?>" />
<input type="hidden" name="id" value="<?=$post->parent?>" />
<p style="margin-top:0;"><label>Title:</label> <input maxlength="50" size="32" type="text" name="title" value="<?=$post->title?>" /></p>
<? 
$media = get_media_link($post->excerpt,'array',true);
if(!empty($media)) { 
?>
<p>original link: <br/><a class="external" href="<?=$media['url']?>" target="_blank"><?=$media['url']?></a>
<input type="hidden" name="excerpt" value="<?=$media['url']?>"/></p>
<? } ?>
<p><label>Add your comments:</label> <textarea rows="6" cols="35" name="content"><?=xml_entities($post->content)?></textarea></p>
<p>
<input type="button" name="update" value="Update" id="upd-<?=$post->blog_id.'-'.$post->post_id.'-'.$post->type?>" class="button green-button small-button" onclick="return update_media(this);" />
<a href="#" id="del-<?= $post->blog_id.'-'.$post->post_id.'-'.$post->type ?>" class="button gray-button small-button delete-media" onclick="return delete_media(this);">Delete</a>
</p>
<p class="updated" id="media-updater-status"></p>
</form>

      <? } ?>

    <? } ?>
  </div>
  <?
}

function draw_gallery_thumb($post, $default, $i, $admin_mode = FALSE, $return = false) {

  $post->thumb = $post->image;
  $media = isset($post->excerpt) ? get_media_link($post->excerpt) : '';

  if (!empty($media)) $post->thumb = get_media_meta($media,'thumbnail');  
  if (empty($post->thumb) && $post->type == 'attachment') $post->thumb = $post->guid;  
  if (empty($post->thumb)) 
    $post->thumb = get_img_src(get_the_post_thumbnail($post->post_id,'thumbnail'));
  if (!$admin_mode) $sel = ($default == $i) ? " selected" : "";

  $ret = '';
  $ret .= 
  '<a class="item-thumb ev '.$sel.'" id="thumb-'.$post->blog_id.'-'.$post->post_id.'-'.$post->type.'"
      href="'.add_query_arg('default', $i).'" title="'.esc_attr($post->title).'">'; 

  $ret .= make_img($post->thumb, CGW_IMG_THUMB_W, CGW_IMG_THUMB_H, "recip smaller");
  $ret .= '</a>';

  if ($return) return $ret;
  else echo $ret;

}

function draw_gallery_thumbs($posts, $default = 0, $admin_mode = FALSE) {
  $i = 0;
  if(count($posts)>1) {
    foreach ($posts as $post) {
      draw_gallery_thumb($post, $default, $i++, $admin_mode);
    }
    
  }
}


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function profile_ad($name, $link_text, $link_html) {
?>
  <div class="profile-ad">
    <? draw_promo_content($name, 'h3'); ?>
    <a href="<?= $link_html ?>"><?= xml_entities($link_text) ?></a>
  </div>
<?
}

function syi_campaign_gallery_widget($posts, $default = 0, $admin_mode = false) {
?>
<section id="gallery" class="posts gallery section gallery-widget collapsed-section evs" >
  <div class="left gallery-left" >
<? if($admin_mode) { ?>
    <div id="uploader">
      <div id="sortable">
      <? draw_gallery_thumbs($posts, $default, $admin_mode); ?>
      </div>
      <div id="uploader-buttons">
        <a id="addfiles" href="#" class="button gray-button medium-button">Add a photo</a>
        <a id="addlinks" href="#" class="button gray-button medium-button">Add a video</a>
      </div>
      <div id="filelist">Sorry, but you need to enable JavaScript to upload.</div>      
      <div id="linkmodal">
        <p>To add a video, copy and paste a YouTube or Vimeo embed code or URL here:</p>
        <form method="post" action="/ajax-campaign.php?id=<?=$id?>" id="links-uploader">
        <textarea cols="26" rows="4" name="links" id="links"></textarea>
        <a href="#" id="closelinkmodal" class="closemodal button small-button green-button">Add</a>
        <a href="#" class="closemodal button small-button gray-button">Cancel</a>
        </form>
      </div>
      <div id="uploader-error" class="error"></div>

    </div>
<? } else { ?>    
    <div id="thumbs" class="ev"><? draw_gallery_thumbs($posts, $default, $admin_mode);?></div>
<? } ?>
  </div>

  <div class="right gallery-right">
    <? if($admin_mode) { ?>
      <div class="no-media-msg" <? if (count($posts)>0) echo 'style="visibility:hidden"'; ?>>
        A picture is worth a thousand words!  
        Use the buttons to the left to add pictures or movies from YouTube and Vimeo.
      </div>
    <? } ?>

    <div class="scrollable vertical">
    <div id="gallery-items" class="items evs">
      <?
      if(!$admin_mode) { 
        $i = 0; foreach($posts as $post) { draw_gallery_item($post, $i++ == $default); }
      } else { 
        if (!empty($posts[0])) {
          draw_gallery_item($posts[0], 1, $admin_mode); 
        } else {
        }
      }
      ?>
    </div>
  </div>

  </div>
</section>
<?
}

function ga_track_donation($user) {
}

function get_thankyou_user() {
  global $bp;

  if(!isset($_REQUEST['cid'])) 
    return $bp->displayed_user;

  $cartID = decrypt_cart($_REQUEST['cid']);
  if ($cartID == 0)
    return $bp->displayed_user;
  
  $user_id = get_cart_user($cartID);
  $user = get_userdata($user_id);
  
  if (empty($user)) {
    $user = $bp->displayed_user;
  }

  return $user;
}

function thankyou_widget() {
  global $cartID, $current_user, $post, $bp;
  if ($cartID == 0)  return;
  $user = get_thankyou_user();
  if (empty($user)) return;
  get_currentuserinfo();
  $user_id = $user->ID;
  //if ($user_id != $bp->loggedin_user->id && !current_user_can('level10')) return;
  
  $user->cart_id = $cartID;
  process_publish_cart($cartID);

  add_shortcode('update_email', 'update_email_shortcode');

  ?><div class="share-message"><?
  draw_promo_content('thank-you-message');

  //get logged in user object to connect and invite 
  //not using is_my_profile because widget can be called elsewhere
  if (is_user_logged_in() && ($current_user->ID != $user->ID)) { 
    $user = $current_user;
  }

  draw_facebook_settings($user);
  draw_invite_friends("thankyou/".urlencode(encrypt($cartID)));      
  ?></div><?
}

function update_email_shortcode() {
  $user = get_thankyou_user();
  
  $email = $user->user_email;

  return '<b>' . xml_entities($email) . '</b>'
    . ' (<a href="' . get_member_link($user->id, 'settings') . '"><u>change this</u></a>)';
}

function draw_facebook_settings($user) {
  ?>
    <form class="fb-publish" action="<?= add_query_arg('fb_publish',1)?>" method="POST">
      <input type="hidden" name="resend" value="1" />
      <? display_fb_publish_options($user->cart_id); ?>
    </form>
  <?
}

function draw_invite_friends($context) {
  if (empty($context)) return;  
  ?><div class="share-left" style="padding: 14px 0;">
    <b>Send an email</b> to invite your friends:
  </div><div class="share-right">
    <? draw_invite_link($context); ?>
  </div>
  <?
}

function syi_give_section() {
  global $blog_id, $event_id, $REGIONS,$CAUSES,$FOCUS;

  if ($blog_id == 1) {
    $giveany = "left";
    $gifts = "right";
  } else {
    $giveany = "right";
    $gifts = "left";
  }
  if($blog_id > 1) { //is charity home
    $gift_msg = '';          
  } else {
    $tags = get_fr_tags($event_id);
  }

  if ($event_id > 0) {
    $gift_msg = get_post_meta($event_id, 'syi_gift_message', true);    
    if (empty($gift_msg) && !empty($tags))
      $gift_msg = ' ';
  }
  ?>  
  <section id="give" class="give gallery section gifts-v3">
    <a name="give"></a>
    <div id="campaign-gifts" class="<?=$gifts?> gallery-right give-browse evs">
    <?
    $p = get_posts('post_type=page&name=give');
    $gifts_title = 'Give a gift, get a story of the life you change!';
    if ($blog_id == 88)
      $gifts_title = "Give a gift.  Change a girl's life.";
    if($blog_id == 1) {
      $args = array(
        'header' => false, 
        'preload' => false, 
        'controls' => false, 
        'v2' => true, 
        'empty_html' => 'Loading...',
        'event_id' => $event_id,
        'page_title' => $gifts_title,
        'include_small_gifts' => true,
        'exclude' => 'xx' // Override default no_browser exclusion on the featured tab
      );        
      if (!empty($tags)) 
        $args['regions'] = $tags;
      if (!empty($gift_msg)) {
        $args['shrink'] = true;
      }
    } else {
      $args = array(
        'page_title' => $gifts_title,
        'include_small_gifts' => true,
        'preload' => true,
        'shrink' => true,
        'blog_id' => $blog_id,
        'exclude' => 'xno_browser', // SteveE: removed no_browser because I think we do want those gifts here
        'include_small_gifts' => true,
        'show_private' => true
      );
    }
    $args = apply_filters('gift_browser_args', $args);
    $has_gifts = (gift_browser_widget($args) !== FALSE);
    ?>
    </div>
    <? if ($has_gifts) { ?>
    <div id="campaign-tags" class="<?=$giveany?> gallery-left evs">
      <div class="more-options gift-tags">
      <? if (empty($gift_msg) && $event_id > 0) { ?>
        <div class="choose"><b>choose by need</b><div class="rarr"></div></div>
        <ol id="causes">
          <? foreach ($CAUSES as $tag=>$name) { ?>
            <a id="tag-<?=$tag?>" href="?tag=<?= $tag ?>" class="gift-tag cause-gift-tag button tag-button2 ev">
            <input id="choose-<?= $tag ?>" type="checkbox" name="tags[]" value="<?= $tag ?>" /><label for="choose-<?= $tag ?>"><?= $name ?></label></a>
          <? } ?>
        </ol>
        <div class="choose"><b>by age / gender</b><div class="rarr"></div></div>
        <ol id="people">
          <? foreach ($FOCUS as $tag=>$name) { ?>
            <a id="tag-<?=$tag?>" href="?tag=<?= $tag ?>" class="gift-tag focus-gift-tag button tag-button2 ev">
            <input id="choose-<?= $tag ?>" type="checkbox" name="tags2[]" value="<?= $tag ?>" /><label for="choose-<?= $tag ?>"><?= $name ?></label></a>
          <? } ?><div class="clearer"></div>
        </ol>
  
        <div class="choose"><b>or by location</b><div class="rarr"></div></div>
        <ol id="regions">
          <? foreach ($REGIONS as $tag=>$name) { ?>
            <a id="tag-<?=$tag?>" href="?tag=<?= $tag ?>" class="gift-tag region-gift-tag button tag-button2 ev">
            <input id="choose-<?= $tag ?>" type="checkbox" name="tags3[]" value="<?= $tag ?>" /><label for="choose-<?= $tag ?>"><?= $name ?></label></a>
          <? } ?><div class="clearer"></div>
        </ol>
      <? } else if (trim($gift_msg) != "") { ?>
        <div style="padding:0 10px 10px 10px; line-height:1.4;">
          <?= $gift_msg ?>
        </div>
      <? } ?>
  
      <? 
        if ($blog_id == 1) { 
          syi_giveany_widget(); 
        } else {
          draw_promo_content('certified');
        } 
      ?>
      </div>
      <? do_action('after_give_sidebar'); ?>
    </div>
    <? } ?>
  </section>
  <?
}

function syi_giveany_widget($args = NULL) {
  global $blog_id, $GIFTS_EVENT;

  $title = "Can't decide?";
  $message = "No problem. Donate any amount, and we'll invest it where it's needed most.";
  $give_label = "Give $";
  $event_id = $GIFTS_EVENT;
  if (is_array($args))
    extract($args);

  ?><div class="box"><?
  if (!empty($title)) {
    ?><h3><?= $title ?></h3><?
  }
  if (!empty($message)) {
    ?><p><?= $message ?></p><?
  }
  ?>
  <form id="give-any-amount" action="<?= pay_link(CART_GIVE_ANY) ?>" method="POST" style="font-size:12pt;">
  <label for="damt"><b><?= esc_html($give_label) ?></b></label>
  <input type="text" name="amount" size="5" maxlength="5" style="width:55px;padding:3px;" value="" id="damt">
  <input id="give-any" type="submit" class="button medium-button orange-button" name="submit" value="Donate">
  <input type="hidden" name="event_id" value="<?=intval($event_id)?>" />
  <input type="hidden" name="blog_id" value="<?=intval($blog_id)?>" />
  </form>
  </div>
  <?        
}

function syi_stories_section($stories = NULL) {
  global $blog_id, $event_id;

  ?><a name="stories"></a><?

  if($blog_id > 1) 
    $posts = get_stories_by_charity($blog_id,16,'ds.featured DESC, RAND()');
  else 
    $posts = get_cgw_posts($event_id);

  if (count($posts) == 0) {
    show_sample_stories($event_id, 4);
    return;
  }

  ?><div class="stories-section"><?
  ?><h2 class="section-header">See the impact of your donation on the actual recipient</h2><?
  syi_campaign_gallery_widget($posts, $_GET['default']);
  ?></div><?
}

function show_sample_stories($event_id = 0, $limit = 6, $title = NULL) {
  if ($event_id > 0) {
    $args = array(
       'tag' => get_fr_tags($event_id),
       'limit' => $limit
    );
    $stories = stories_shortcode($args);
  }

  if (empty($stories))
    return;

  if ($title !== FALSE && empty($title))
    $title = "You'll see the impact of your donation on the actual recipient.";

  // Show sample stories
  ?>
  <div class="sample-stories">
    <? if ($title !== FALSE) { ?>
    <h2 class="section-header" style="margin-left: -15px; margin-bottom: 10px;"><?= xml_entities($title) ?></h2>
    <? } ?>
    <?= $stories ?>
  </div>
  <?
}

function syi_social_section() {
  global $blog_id, $event_id;

  $limit = $blog_id == 1 ? 80 : 10;

  ?>
  <a name="social"></a>
  <section id="social" class="social evs">
    <div class="left gallery-left">
      <h2 class="section-header">Latest activity</h2>
      <? 
			if($blog_id == 1) {
			  $activities = get_campaign_activities($event_id,$limit);
 			  syi_progress_widget(array('donations'=>$activities, 'show_avatars'=>true)); 
      } else {
			  syi_progress_widget(array('blog_id'=>$blog_id, 'event_id'=>$event_id, 'limit'=>$limit, 'show_avatars'=>true)); 
      }
			?>
    </div>
    <div class="right gallery-right">
      <? syi_fb_comments(); ?>
    </div>
  </section>
  <?    
}

function syi_fb_comments($args = NULL) {
  global $FB_XID, $blog_id, $event_id, $norm_url;

  $width = 450;
  if (!empty($args))
    extract($args);

  if ($xid == NULL) {
    if ($event_id == 6600)
      $xid = "href='http://seeyourimpact.org/members/jeremyw/'";
    else if ($event_id == 5436)
      $xid = "href='http://seeyourimpact.org/members/li/'";
    else if ($event_id > 6570 && $compat != FALSE)
      $xid = "xid='ev-$event_id'";
    else if (!empty($norm_url)) 
      $xid = "href='" . esc_url($norm_url) . "'";
    else
      $xid = "href='" . $_SERVER['SCRIPT_URI'] . "'";
  } else {
    $xid = 'xid="' . esc_attr($xid) . '"';
  }
  if (empty($header))
    $header = "Leave a message of support.";

  if (empty($title))
    $title = $event_id > 0 ? get_the_title($event_id) : get_blog_option($blog_id, 'blogname');

  if (!as_bool($no_header)) {
    ?><h2 class="section-header"><?= xml_entities($header); ?></h2><?
  }
  ?><fb:comments <?= $xid ?> title="<?=esc_attr(xml_entities($title))?>" num_posts="20" width="<?=$width?>"></fb:comments><?
}

function syi_share_section($invite = FALSE) {
  global $norm_url, $post;
  $url = esc_url($norm_url);
  if (empty($url))
    $url = get_permalink($post->ID);

  ?>
  <div id="sharing" class="share-campaign evs">
    <? if (!empty($invite)) { ?>
    <div class="share-invite left">
       <? draw_invite_link($invite,'',false,'small' ); ?>
    </div>
    <? } ?>
    <div class="share-twitter left">
      <a id="twitter-share" href="http://twitter.com/share" data-url="<?=$url?>" data-via="SeeYourImpact" class="twitter-share-button ev">Tweet</a>
    </div>
    <div class="share-facebook left">
      <iframe class="left" style="width:100px; height:24px; border:none; overflow:hidden;" 
        src="//www.facebook.com/plugins/like.php?href=<?=$url?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=trebuchet+ms&amp;colorscheme=light&amp;height=21&amp;ref=campaign" scrolling="no" frameborder="0" allowTransparency="true"></iframe> 
    </div>
  </div>
  <?
}

function syi_stat_section() {
  global $blog_id, $event_id, $wpdb;

  if($blog_id > 1) { ?>
    <section class="stats stats2" style="height:36px;"></section>
    <? return;
  }

  $campaign = get_campaign_stats($event_id);
  $stats = calculate_campaign_stats($event_id);
  $stats = apply_filters('campaign_stats', $stats);

  $total = should_include_tips($event_id) ? $stats->total : $stats->raised;

  $goal = eor($campaign->goal, 1000);
  if ($total < $goal)
    $togo = " - $" . number_format($goal - $total) . " to go!";

  if ($stats->lives > 1) $title = "$stats->lives life-changing gifts donated$togo";
  else if ($stats->lives > 0) $title = "$stats->lives life-changing gift donated$togo";
  else $title = "be the first to donate!";

  draw_campaign_stat_bar(array(
    'raised' => $total,
    'donors_count' => $stats->donors,
    'title' => $title,
    'goal' => $goal,
    'hide_money' => !show_money_amounts()
  ));

  update_campaign_stats($event_id,1);
}

// show the current dollar amount if either:
// 1. the "downplay money" option is not set
// 2. the current user is the campaign owner
function show_money_amounts() {
  global $show_money_amounts; // Cache per page
  global $TEMPLATE, $event_id;

  if ($show_money_amounts !== NULL)
    return $show_money_amounts;

  if ($TEMPLATE && $TEMPLATE->downplay_money && !can_manage_campaign($event_id))
    return $show_money_amounts = false;

  return $show_money_amounts = true;
}

function draw_campaign_stat_bar($stats) {
  $stats = (object)$stats;

  if ($stats->donor_goal > 0) {
    $stats->goal = 0; // Don't show money goal
    $perc = pct($stats->donors_count, $stats->donor_goal);
  } else if ($stats->goal > 0) 
    $perc = pct($stats->raised, $stats->goal);
  else 
    $stats->hide_bar = TRUE;

  if ($perc <= 0) $perc = 5;
  else if ($perc < 10) $perc = 10;
  else if ($perc > 100) $perc = 100;

?>
  <section class="stats stats2">

    <? if (!$stats->hide_money) { ?>
      <div class="stat2 left"><b>$<?= number_format($stats->raised) ?></b>
      <? if ($stats->raised < 10000) { ?><label>raised</label><? } ?></div>
    <? } ?>

    <? if (!$stats->hide_donors) { ?>
      <div class="stat2 left donors"><b><?= number_format($stats->donors_count) ?></b><label>donor<? if ($stats->donors_count != 1) echo 's';?></label></div>
    <? } ?>

    <? if (!$stats->hide_bar) { ?>
      <div class="stat2 meter2">
        <div class="meter" title="<?=esc_attr($stats->title)?>"><span style="width: <?= $perc ?>%">
          <? if ($perc > 15) { echo '<span class="reached">' . $perc . '%</span>'; } ?></span>
        </div>
      </div>
    <? } ?>

    <? if ($stats->goal > 0 && !$stats->hide_money) { ?>
      <div class="stat2 right goal"><b>$<?= number_format($stats->goal) ?></b><label>goal</label></div>
    <? } ?>

    <? if ($stats->donor_goal > 0 && !$stats->hide_donors) { ?>
      <div class="stat2 right goal"><b><?= number_format($stats->donor_goal) ?></b><label>goal</label></div>
    <? } ?>

  </section>
<?  
}

function draw_stat_section2() {
  global $event_id, $wpdb, $norm_url;
  $stats = $wpdb->get_row($wpdb->prepare(
    "SELECT COUNT(dg.ID) as lives,COUNT(DISTINCT(d.donorID)) as donors,SUM(dg.amount) as total
    from donationGifts dg
    JOIN donation d ON d.donationID=dg.donationID
    WHERE d.test=0 AND event_id=%d", $event_id));
  $goal = intval(get_post_meta($event_id, 'syi_lives_goal', true));
  if ($goal == 0) $goal = 30;
  $perc = intval(100 * $stats->lives / $goal);
  if ($perc > 100) $perc = 100;
  if ($perc == 0 && $stat->lives > 0) $perc = 1;
  if ($goal == 1) $mgoal = "change 1 life";
  else $mgoal = "raise $goal gifts";
  $stats->goal = $goal;
  if ($perc == 0) {
    $mperc = 5;
    $title = "Please help $mgoal!";
  } else if ($perc < 10) {
    $mperc = 10;
    $title = "Please help $mgoal!";
  } else if ($perc == 100) { 
    $mperc = $perc;
    $title = "We've reached our goal to $mgoal!";
  } else { 
    $mperc = $perc;
    $title = "We've reached $perc% of our goal to $mgoal!";
  }
  ?>
  <section class="stats">
    <? if ($stats->total < $stats->goal) { ?>
      <div class="stat lives goal"><b><?= ($stats->goal - $stats->lives) ?></b><label>life-changing<br>gifts needed!</label></div>
    <? } else if ($stats->total > 0 && $stats->goal > 0) { ?>
      <div class="stat lives goal"><b><?= $stats->lives ?></b><label>life-changing<br>gifts donated</label></div>
    <? } ?>
    <? if ($stats->donors > 0) { ?>
      <div class="stat raised"><b>$<?= $stats->total ?></b><label>raised from<br><?= $stats->donors ?> donor<? if ($stats->donors != 1) echo 's'; ?></label></div>
    <? } ?>
  </section>
  <?
}

function syi_actions_section() {
  global $event_id;
  $stories = get_stories_by_event($event_id, 1, 'post_modified DESC');
  ?>
  <div class="actions">
    <a href="#gifts" class="button orange-button big-button give-now">Donate now<span>(see all gift options)</span></a>
    <!--
    <div class="gallery-widget">
      <label>Most recent gift</label>
      <? draw_avatar_box(1); ?>
      a three month supply of vitamin supplements
    </div>
    -->
    <? if (count($stories) > 0) { ?>
      <div class="gallery-widget">
        <? draw_stories($stories); ?>
        <a href="#stories" class="read-now">Latest story (<u>see all</u>)</a>
      </div>
    <? } ?>
  </div>
  <?
}

function contact_form_row($label, $name, $inp = NULL) {
  if (empty($inp))
  if (empty($inp))
    $inp = '<input style="width:400px;" class="focused" type="text" name="_' . esc_attr($name) . '">';
return '
<div class="cc_form fields" style="margin-bottom: 4px;">
  <div class="field-label">' . esc_html($label) . ':</div>
  <div class="labeled">' . $inp . '</div>
</div>
';
}

function contact_form_shortcode($args) {
  global $emailEngine;

  extract(shortcode_atts(array(
    'type' => '',
  ), $args));

  if ($_POST) {
    $name = $_POST['_name'];
    $email = $_POST['_email'];
    $title = $_POST['_title'];
    $organization = $_POST['_organization'];
    $budget = $_POST['_budget'];
    $comments = stripslashes(nl2br($_POST['_comments']));
    $emailEngine->sendMailSimple('Administrator',
      get_email_address("cloud"), 
      "Cloud Services request: $name, $organization",
      "
Name: <b>$name</b><br>
E-mail: $email<br>
Title: $title<br>
Organization: $organization<br>
Budget: $budget<br>
<br>
$comments",
      '',
      false,
      false,
      "contact_form"
    );

    return "<b style=\"font-size: 20pt;\"><span style=\"color: #f27019;\">$s Thank you!</span>  We'll contact you soon.</b>";
  }

  $inpBudget = '
<select name="_budget" style="width:400px;">
  <option value="lt500k">&lt; $500,000 (annual)</option>
  <option value="500k">$500,000 to $2 million</option>
  <option value="2m">$2 to $10 million</option>
  <option value="10m">$10 to $50 million</option>
  <option value="50m">&gt; $50 million</option>
</select>
';

  $inpComments = '
<textarea style="width:400px;" name="_comments"></textarea>';

  return '<form class="standard-form" id="cc-form" method="post" action="/cloud/">'
    . contact_form_row('Name', 'name')
    . contact_form_row('E-mail', 'email')
    . contact_form_row('Title', 'title')
    . contact_form_row('Organization', 'organization')
    . contact_form_row('Budget', 'budget', $inpBudget)
    . contact_form_row('Comments', 'comments', $inpComments)
    . '<div class="cc_form fields"><div class="field-label"></div><input type="submit" class="button big-button green-button" name="contact_form_post" value="Submit"></div>'
    . '</form>';
}
add_shortcode('contact-form','contact_form_shortcode');

function faq_shortcode($args, $content = '') {
  extract(shortcode_atts(array(
    'q' => ''
  ), $args));

  return "<dt class='faq'>" . xml_entities($q) . "</dt><dd class='faq'>" . xml_entities($content) . "</dd>";
}
add_shortcode('faq','faq_shortcode');



