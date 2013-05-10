<?
global $post;
$topic = $post->post_name;
$title = $post->post_title;
$usage = "about " . $title;

$is_home = ($topic == 'need');
$tag = $is_home ? 'featured' : $topic;

global $GIFTS_V2;

if ($is_home) {

  gift_browser_widget(array(
    'header' => true,
    'causes' => array('featured'),
    'limit' => 6
  ));

  draw_home_widget('need-causes', 'causes_widget', array(
    'cols' => 6,
    'has_text' => false,
    'mode' => 'cloud',
    'link_after' => '<span class="the-oc"> &raquo;</span>'
  ));

  draw_home_widget('home-stories', 'stories_widget', array(
    'featured_only' => true,
    'limit' => 3
  ));

} else {

/*
  gift_browser_widget(array(
    'causes' => array($topic)
  ));
*/

  draw_widget('gifts_widget', array(
    'cols' => 6,
    'tag' => $topic,
    'ids' => get_post_meta($post->ID,'post_ids',true),
    'show_all' => true
  ));

  draw_widget('stories_widget', array(
    'cols' => 6,
    'tag' => $tag,
    'title' => "Read stories others are receiving",
    'banner' => true,
    'ids' => get_post_meta($post->ID,'story_ids',true)
  ));

  draw_widget('charities_widget', array(
    'cols' => 6,
    'tag' => $topic,
    'title' => "Partner charities in the SeeYourImpact network",
    'banner' => true,
    'ids' => get_post_meta($post->ID,'gift_ids',true)
  ));

  draw_widget('posts_widget', array(
    'cols' => 6,
    'title' => "Learn more on the SeeYourImpact blog",
    'banner' => true,
    'tag' => $topic,
    'ids' => get_post_meta($post->ID,'gift_ids',true)
  ));

}

?>
