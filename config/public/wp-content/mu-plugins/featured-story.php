<?
/*
Plugin Name: Featured Story
Plugin URI: http://www.seeyourimpact.com
Description: controls the stories featured in post widget
Version: 1
Author: Yosia Urip
Author URI: http://www.seeyourimpact.com
*/

add_action('admin_menu', 'featured_story_add_menu', SW_HOME_PRIORITY);

function featured_story_add_menu(){
  if (!is_site_admin())
    return;

  $page = add_submenu_page(SW_HOME, __('Featured Story', 'featured-story'),
    __('Featured Story', 'featured-story'), 8, 'featured-story',
    'featured_story_page');
  add_action('admin_print_styles-' . $page, 'featured_story_styles');
}

function featured_story_styles() {
  $dir = normalize_resource_url(get_bloginfo('template_url', 'display')); // Register to site #1
  wp_enqueue_script('syi-behavior', "$dir/behavior.js", null);
}

function featured_story_page(){
  global $wpdb;

  $ret = '';

echo '<style type="text/css">
.story {
  padding: 5px;
  position: relative;
  float: left;
  width: 140px;
  height: 153px;
  overflow: hidden;
  text-decoration:none;
}
.story .box {
}
.story .pic {
}
.story .title {
  padding: 5px 0;
  font-size: 13px;
}
.pic { position: relative; overflow: hidden; height:100px; width:150px; }
.pic .zoom {
  display:none;
}
.story .set-featured {
  display: block;
  position: absolute;
  top: 87px; left: 5px;
  height: 20px;
  padding-left: 25px;
  background: url(/wp-content/themes/syi/images/checkbox.png) no-repeat 0 -55px;
  cursor: pointer;
}
.story .is-featured {
  background-position: 0 0;
}


</style>';

$ds_count = $wpdb->get_var("SELECT COUNT(*) FROM donationStory");
$ds_count_featured = $wpdb->get_var(
  "SELECT COUNT(*) FROM donationStory WHERE featured = 1");
$blog_1 = $wpdb->get_var("SELECT domain FROM wp_blogs WHERE blog_id = 1");
$ds_blogs = $wpdb->get_col("SELECT DISTINCT domain FROM donationStory ds"
." LEFT JOIN wp_blogs wb ON ds.blog_id = wb.blog_id WHERE ds.featured = 1 ");

add_action('after_draw_story', 'draw_story_featured');
foreach($ds_blogs as $k=>$v){$ds_blogs[$k] = str_replace(".".$blog_1,"",$v);}

  echo '<div class="wrap">';
  echo "<h2>Featured Stories ($ds_count_featured of $ds_count)</h2>";
  echo 'Blogs with stories featured: '.implode(", ",$ds_blogs);
  echo '<br/><br/>';
  stories_widget(array(
    'featured_only' => true,
    'title' => 'Stories',
    'order' => 'ds.post_modified DESC',
    'limit' => 150
  ));
  echo '</div>';

  echo '<div class="wrap" style="clear:both;">';
  echo "<h2>Recent Stories</h2>";
  stories_widget(array(
    'title' => 'Stories',
    'order' => 'ds.post_modified DESC',
    'limit' => 88
  ));
  echo '</div>';

  echo $ret;
}

function draw_story_featured($s) {
  $feat = intval(get_story_featured($s['blog_id'], $s['id']));
  $cls = "set-featured";
  if ($feat)
    $cls .= " is-featured";
  ?><span class="<?=$cls?>" id="<?= $s['ref'] ?>"></span><?
}

function set_story_featured($blog_id, $story_id, $feat = 1) {
  global $wpdb;

  if (empty($blog_id) ||
      empty($story_id) ||
      !current_user_can_for_blog($blog_id, 'level_1'))
    return false;

  $wpdb->query($sql = $wpdb->prepare("
    UPDATE donationStory
    SET featured=%d
    WHERE blog_id=%d AND post_id=%d",
    $feat, $blog_id, $story_id));
  return true;
}

function get_story_featured($blog_id, $story_id) {
  global $wpdb;

  return $wpdb->get_var($wpdb->prepare(
    "SELECT featured FROM donationStory WHERE blog_id=%d AND post_id=%d",
    $blog_id, $story_id)) == 1; 
}




?>
