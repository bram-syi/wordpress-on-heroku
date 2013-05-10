<? 

global $NO_SIDEBAR, $NO_PADDING, $header_file, $post, $GIFTS_EVENT, $GIFTS_LOC;
$NO_SIDEBAR = true;
$NO_PADDING = true;
$header_file = "landing";

remove_action('syi_pagetop', 'draw_the_crumbs', 0);
add_action( 'wp_head', 'add_kodak_styles' );
wp_enqueue_script('twitter', "http://platform.twitter.com/widgets.js");
add_action('facebook_meta', 'campaign_facebook_meta');

include_once('page.php');

function add_kodak_styles() {
  global $GIFTS_EVENT, $GIFTS_LOC, $post;

  $GIFTS_EVENT = $post->ID; // 6819
  $GIFTS_LOC = 'kodak';
  set_event_cookie($GIFTS_EVENT);

?>
<style>
.login-bar { display: none; }
#features ul li {
  background:url("http://www.kodakgallery.com/A/external/gallery/images/smo/themes/2011/groupalbums/groupalbums/checkbox.jpg") no-repeat;
  padding-left:35px;
  font-size:12px;
  list-style: none;
  margin: 25px 0 0;
}

#features ul li strong {
  font-size:14px;
  display: block;
  margin-bottom: 5px;
}

.gift-browser .button {
  background: #c72026;
  border: 1px solid #c72026;
}
.gift-page .page-title {
  font-size: 20pt;
  color: #444;
}
</style>
<?
}

function campaign_facebook_meta() {
  global $post;
  $ex = get_excerpt($post);

?>
  <meta name="description" content="<?= esc_attr($ex) ?>" />
  <meta property="og:title" content="<?= esc_html($post->post_title) ?>"/>
  <meta property="og:description" content="<?= esc_attr($ex) ?>" />
  <meta property="og:image" content="http://grub.seeyourimpact.org/files/2011/04/Mallorie.jpg" />
<?
}

