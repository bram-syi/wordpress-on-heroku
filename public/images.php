<?php

include_once('wp-load.php');

ensure_logged_in_admin();

class Story
{
  public $title;
  public $url;
  public $img;
  public $thumb;
  public $text;
}

function bm_better_excerpt($length, $ellipsis = "...") {
  $text = get_the_excerpt() . ' ';
  $text = strip_tags($text);
  $text = str_replace("&#8217;", "'", $text);
  $text = str_replace("&#8216;", "'", $text);
  $text = str_replace("&#8220;", '"', $text);
  $text = str_replace("&#8221;", '"', $text);
  $text = str_replace("&#8212;", '--', $text);
  $text = substr($text, 0, $length);
  $text = substr($text, 0, strripos($text, " "));
  $text = str_replace("[...]","",$text).$ellipsis;
  return trim($text);
}

function get_image($post_id, $size = 'thumbnail') {
  global $blog_id;
  if (empty($size))
    $size = full;

  $args = array(
    'blog_id' => $blog_id,
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'post_parent' => $post_id,
    'numberposts' => 1
  );
  $attachments = get_posts($args);
  if ( ! is_array($attachments) ) return null;
  $first = array_shift($attachments);

  $meta = wp_get_attachment_metadata($first->ID);
  if (empty($meta))
    return null;
  $sized = $meta['sizes'];
  if (is_array($sized)) {
    $sized = $sized[$size];
  } else {
    $sized = array();
  }
  $file = $meta['file'];
  $thumb = preg_replace('/\/[^\/]*?$/', '/' . $sized['file'], $file);

  return array( $meta['width'], $meta['height'], $file, $thumb, $sized['width'], $sized['height'] );
}

function debug($val = null, $title = '') {
  echo "<!-- $title\r\n";
  var_dump($val);
  echo "\r\n-->";
}

$stories = array();
    global $wpdb;

$blog_list = get_blog_list( 0, 'all' );
$blogs = array();
foreach ($blog_list AS $blog) {
  $blogid = $blog['blog_id'];
  if ($blogid == 1)
    continue;
  if (strpos($blog['domain'], 'sss.') === 0)
    continue;

  $details = get_blog_details($blogid);
  $blogurl = $details->siteurl;
  $files = $blogurl . $blog['path'] . 'files/';

  switch_to_blog($blogid);
  wp_cache_flush(); // necessary because switch is not clearing

  $apolo = !empty($_REQUEST['apolo']);

  if ($apolo) {
    $sql = "";

    $sql = $wpdb->prepare(
      "SELECT DISTINCT dg.story AS ID FROM donationGifts dg
       JOIN donation d ON dg.donationID=d.donationID
       JOIN donationGiver donor on donor.ID=d.donorID
       WHERE dg.story > 0
         AND d.donationDate >= '2010-10-21' 
         AND d.donationDate <= '2010-10-28' 
         AND dg.blog_id = $blogid
         AND NOT donor.email LIKE '%%wall%%'
       LIMIT 25");
    $rand_posts = $wpdb->get_results($sql, OBJECT);
  } else {
    $rand_posts = get_posts('numberposts=25');
  }

  if ($rand_posts) foreach( $rand_posts as $post ) {
    $id = intval($post->ID);
    if ($apolo)
      $post = get_post($id);

    $info = get_image($id, 'medium');
    if (empty($info))
      continue;
    if (empty($info[2]) || empty($info[3]))
      continue;
    if (empty($info[4]) || empty($info[5]))
      continue;

    setup_postdata($post);
    $story = new Story();
    $story->img_width = $info[0];
    $story->img_height = $info[1];
    $story->img = $files . $info[2];
    $story->thumb = $files . $info[3];
    $story->thumb_width = $info[4];
    $story->thumb_height = $info[5];
    $story->title = $post->post_title;
    $story->text = bm_better_excerpt(400);
    $story->url = $post->guid;

    $stories[] = $story;
  }

  restore_current_blog();
}

$is_xml = !empty($_GET["xml"]);
if (!$is_xml) {
?>
<html><head>
<? core_scripts(); ?>
<script src="http://www.appelsiini.net/projects/lazyload/jquery.lazyload.js" type="text/javascript"></script>
<script>
$(function() {
  $("img").lazyload({
    effect: "fadeIn"
  });
});
</script>
</head><body style="background:white;">
<?
}

shuffle($stories);
foreach ($stories as $story) {
  if ($is_xml) {
   ?><item>
     <title><?= $story->title ?></title>
     <link><?= $story->url ?></link>
     <img src="<?= $story->img ?>" lowsrc="<?= $story->thumb ?>" />
     <text><?= $story->text ?></text>
   </item><?
  } else {
   ?><div style="clear:both; padding: 4px;">
     <a href="<?= $story->img ?>" style="float:left; margin-right:10px; text-decoration: none;">
       <div style="position: absolute; z-index:2; margin: 5px; background: white; color: black; padding:2px; font-size: 8pt;"><?= $story->img_width ?> x <?= $story->img_height ?></div>
       <img width="<?= $story->thumb_width ?>" height="<?= $story->thumb_height ?>" style="position: relative; z-index:0; border:0;" src="<?= $story->thumb ?>" />
     </a>
     <a href="<?= $story->url ?>" style="display:block;"><?= htmlspecialchars($story->title) ?></a>
     <div><?= htmlspecialchars($story->text) ?></div>
   </div><?
  }
}

if (!$is_xml) 
  echo '</body></html>';
?>
