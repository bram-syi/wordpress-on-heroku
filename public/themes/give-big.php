<?

if (!is_site_admin() && preg_match('/^jolkona\.(.*)/i', $_SERVER['HTTP_HOST'], $m)) {
#  $url = 'http://msaf.'.$m[1].$_SERVER['REQUEST_URI'];
#  if ($_SERVER['QUERY_STRING']) $url .= '?'.$_SERVER['QUERY_STRING'];
  $url = 'http://www.jolkona.org/';
  wp_redirect($url);
}

add_filter('draw_findraisers', '__return_false');

add_filter('get_campaign_teams', 'get_msaf_teams');
function get_msaf_teams($teams) {
  global $wpdb;

  return array();
}

add_filter('fundraisers_heading', 'msaf_fundraisers_heading');
function msaf_fundraisers_heading($h) {
  return "";
}

remove_filter('get_fundraiser_donation_item', 'get_fundraiser_donation_item');
add_filter('get_fundraiser_donation_item', 'msaf_fundraiser_donation_item');
function msaf_fundraiser_donation_item($name) {
  return $name;
}

function msaf_cart_top($cart) {
?>
  <div class="right" style="font-size:80%; color:#666;margin-top: 18px; text-align: right;">Want to give to more than one organization?<br><a href="http://msaf.seeyourimpact.org/" class="link">return to the Integral Fellows page</a> &raquo;</div>
<?
}
add_action('draw_cart_top', 'msaf_cart_top');


include('default.php');
