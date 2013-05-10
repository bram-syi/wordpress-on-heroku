<?
// These are functions that are moving from syi/functions.php into a common core.
// We'll keep them here until they find a good home.

function add_typekit() {
?>
<script type="text/javascript" src="http://use.typekit.com/nbw4bxb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?
}

function standard_page() {
  global $standard_page;
  if ($standard_page)
    return;
  $standard_page = TRUE;

  global $NO_SIDEBAR, $NO_PADDING, $header_file, $post, $GIFTS_EVENT, $GIFTS_LOC;
  $NO_SIDEBAR = true;
  $NO_PADDING = true;

  add_action('wp_head', 'add_typekit', 100);

  if (is_user_logged_in())
    get_currentuserinfo();

  if (have_posts())
    the_post();
  global $post;

  // sharing_init(TRUE);  commented until we have a reliable way to make sure it won't break javascript when draw_sharing_* are forgotten
  add_filter('body_class', 'add_body_classes');
}

function add_body_classes($classes) {
  global $post;

  if (is_page() && !empty($post->post_name))
    $classes[] = "page-$post->post_name";
  return $classes;
}


