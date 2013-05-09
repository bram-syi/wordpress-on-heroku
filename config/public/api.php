<? 

define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');

define('LATEST_EXPIRATION', 5); // Latest.JS expiration in minutes

global $site, $ver;
$ver = $_REQUEST['ver'];
$site = $_REQUEST['site'];

$js = $_REQUEST['js'];
$css = $_REQUEST['css'];

global $API_URL, $THUMB_URL;
if ($_SERVER["HTTP_USER_AGENT"] == "Amazon CloudFront")
  $API_URL = "//s.seeyourimpact.org";
else
  $API_URL = "//" . $_SERVER["HTTP_HOST"];
$THUMB_URL = "$API_URL/thumbs";
$API_URL = "$API_URL/api/v$ver/$site";

api($_REQUEST['api']);
exit();

function api_story_css() {
?>
.syi-s {
  width: 240px;
  border: 1px solid #ccc;
  padding: 5px;
  font-family: Arial,helvetica,sans-serif;
  font-size: 12pt;
}
.syi-s-text, .syi-s-pic, .syi-s-body, .syi-s-title {
  display: block;
}
.syi-s-title {
  margin: 3px 0;
  font-size: 110%;
}
.syi-s-more {
  font-size: 90%;
}
<?
}

function clean_body($b) {
  // Remove caption
  $b = preg_replace('/\[caption(.*?)\](.*?)\[\/caption\]/ms', '', $b);

  // Remove comments
  $b = preg_replace('/\<!\s*--(.*?)(--\s*\>)/m', '', $b);

  // Remove Word spans with font-family
  $b = preg_replace('/\<span style="font-family: (.*?)"\>(.*?)\<\/span\>/i', '$2', $b);

  // Remove empty links
  $b = preg_replace('/<a[^>]*>(\s*?)<\/a>/i', '', $b);

  // Remove whitespace spans and divs, etc
  for ($i = 0; $i < 3; $i++)
    $b = preg_replace('/<span[^>]*>(\s*?)<\/span>/m', '$1', $b);
  for ($i = 0; $i < 3; $i++)
    $b = preg_replace('/<div[^>]*>(\s*?)<\/div>/m', '$1', $b);
  for ($i = 0; $i < 3; $i++)
    $b = preg_replace('/<p [^>]*>(\s*?)<\/p>/m', '$1', $b);
  $b = preg_replace('/<em>(\s*?)<\/em>/m', '$1', $b);

  // Remove salutation
  $b = preg_replace('/Dear\s.*[,:]/mi', '', $b);

  $b = str_replace("&#160;", " ", $b);

  return clean_text($b);
}

function trim_excerpt($text, $excerpt = NULL, $length = 60)
{
  if ($excerpt) 
    return $excerpt;

  $text = strip_shortcodes( $text );
  $text = apply_filters('the_content', $text);
  $text = str_replace(']]>', ']]&gt;', $text);
  $text = strip_tags($text);
  $excerpt_length = apply_filters('excerpt_length', $length);
  $excerpt_more = '...';
  $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
  if ( count($words) > $excerpt_length ) {
    array_pop($words);
    $text = implode(' ', $words);
    $text = $text . $excerpt_more;
  } else {
    $text = implode(' ', $words);
  }

  return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

function api_latest_html() {
  global $API_URL;

?>
<html><head>
</head><body>
<script src="<?=$API_URL?>/latest.js"></script>
</body>
</html>
<?
}

function api_latest_js() {
  global $site, $API_URL, $THUMB_URL, $wpdb;

  $blog_id = $wpdb->get_var($sql = $wpdb->prepare(
    "select blog_id from $wpdb->blogs
    where domain like %s", "$site.%"));
  if ($blog_id <= 0) {
    echo "// unknown charity";
    return;
  }

  switch_to_blog($blog_id);

  $post = $wpdb->get_row($sql = $wpdb->prepare(
    "select * from donationStory ds
     where ds.blog_id = %s order by ds.post_id desc
     limit 1", $blog_id));
  if ($post == NULL) {
    echo "// no published stories";
    return;
  }

  $pic_url = preg_replace("/.*\/$blog_id\/files/", $blog_id, $post->post_image);
  $p2 = get_blog_post($blog_id, $post->post_id);

  $content = clean_body(strip_tags($p2->post_content));
  $content = trim_excerpt($content);

  $story = array(
    'href' => get_blog_permalink($blog_id, $post->post_id),
    'thumb' => "$THUMB_URL/240x200/$pic_url",
    'title' => $post->post_title,
    'body' => $content
  );
  
?>
var c = d.createElement('link');
c.type = "text/css";
c.rel = "stylesheet";
c.href = "<?=$API_URL?>/story.css";
var h = d.getElementsByTagName("head")[0] || d.documentElement;
h.insertBefore(c, h.firstChild); 
<?

  lineout('<div class="syi-s">');
  lineout('<a class="syi-s-link" href="' . $story['href'] . '">');
  if ($pic_url != NULL) {
    lineout('<img class="syi-s-pic" src="' . $story['thumb'] . '" width="240" height="200">');
  }
  lineout('<span class="syi-s-title">' . htmlspecialchars($story['title']) . '</span>');
  lineout('</a>');
  lineout('<div class="syi-s-body">');
  lineout($story['body']);
  lineout(' <span class="syi-s-more">(<a href="' . $story['href'] . '">read more</a>)</span>');
  lineout('</div>');
  lineout('</div>');
}

function lineout($line) {
  echo "d.write('" . addslashes($line) . "');\r\n";
}

function api($func) {
  $api = explode('.', $func);
  $func = $api[0];
  $ext = $api[1];
  $func = "api_{$func}_{$ext}";

  switch ($ext) {
    case 'css': 
      headers('text/css'); 
      break;
    case 'html': 
      headers('text/html'); 
      break;
    case 'js': 
      headers('text/javascript'); 
      $before = "(function(d) {\r\n";
      $after = "})(document);";
      break;
  }

  echo $before;
  if (function_exists($func))
    call_user_func($func);
  else
    header('HTTP/1.0 400 Bad Request');
  echo $after;
}

function headers($type='text/javascript') {
  header("Content-Type: $type");
  $expires = 60 * 60 * LATEST_EXPIRATION; // short expiration for now - we want to remain somewhat dynamic
  header("Cache-Control: max-age=$expires, public, must-revalidate");
  $expiry = gmdate( 'D, d M Y H:i:s',time()+$expires );
  header("Expires: $expiry GMT" );
}
