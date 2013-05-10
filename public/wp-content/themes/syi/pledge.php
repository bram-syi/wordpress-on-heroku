<?
include_once('campaign-core.php');

global $header_file;
$header_file = 'signin';

global $NO_SHARING;
$NO_SHARING = TRUE;

add_filter('wp_title', 'pledge_title');
add_action('get_sidebar', 'do_nothing');
add_action('draw_pledge_options', 'draw_pledge_options', 0, 2);
add_action('handle_pledge_form', 'handle_pledge_form');
add_filter('body_class','cart_body_class');

$campaign = campaign_init();
$pledge_url = get_campaign_pledge_url($campaign->id);

if ($campaign->theme != 'readathon') {
  wp_redirect(get_permalink($campaign->id));die;
}

get_header();

?>
<style>
.appeal-msg p { color: #444; }
.photo {
  margin: 0 0 20px 20px;
  padding: 2px;
  background: white;
  border: 1px solid #ccc;
}
.standard-form .error {
  margin: 5px auto; text-align: center;
  font-size: 18px;
  position: relative; top: 20px;
}
</style>
<?

if ($_POST) {
  do_action('handle_pledge_form', $campaign);
}

global $pledge_error;

// Pledge FAQ sidebar
?>
<section class="profile-panel">
  <div class="campaign-page"><div class="campaign-sidebar">
    <? draw_pledge_box(array('campaign' => $campaign, 'no_button' => true)); ?>
    <? // do_action('draw_campaign_sidebar', $campaign); ?>
  </div><div class="campaign-content">
    <div class="right campaign-photo" style="margin:20px;">
      <?= make_img(fundraiser_image_src($campaign), 120,120); ?>
    </div>
    <div class="template-about based">
      <? draw_pledge_form($campaign); ?>
      <a class="link" href="<?= get_permalink($campaign->id) ?>" style="display:block; text-align: center; margin: 20px;">back to my fundraiser</a>
    </div>
  </div>
</section>
<?

get_footer(); 

function pledge_title($t) {
  return "$t &raquo; Support this fundraiser";
}

function handle_pledge_form($campaign) {
  global $pledge_error;

  if (wp_verify_nonce($_POST['action'],'submit_pledge'))
    return;

  $cartID = get_cart();

  $amount = $_REQUEST['amount'];
  $a = round($amount, 2);
  if ($a > 0) {
    cart_add($cartID, CART_GIVE_ANY, 1, TRUE, $a, NULL, NULL, NULL, NULL, $campaign->id);
    if ($campaign->id > 1)
      do_action('campaign_add_cart', $cartID, $campaign->id);
    wp_redirect(get_site_url(1, '/cart/'));
    exit;
  }

  $pledge = $_REQUEST['pledge-amount'];
  $a = round($pledge, 2);
  if ($a > 0) {
    cart_add($cartID, CART_PLEDGE, 1, TRUE, $a, NULL, NULL, NULL, NULL, $campaign->id);
    if ($campaign->id > 1)
      do_action('campaign_add_cart', $cartID, $campaign->id);
    wp_redirect(get_site_url(1, '/cart/'));
    exit;
  }

  $pledge_error = "Please enter the amount you'd like to give - thanks!";
}
