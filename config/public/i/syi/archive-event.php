<?
standard_page();
force_login();

$dir = SITE_URL . "/wp-content/themes/syi";
wp_enqueue_style('campaign', "$dir/campaign.css");

get_header();

?>
<h2 class="campaign-h2">Support these fundraisers in reaching their goals!</h2>
<div class="all-campaigns">
<?

$results = $wpdb->get_results($wpdb->prepare(
  "SELECT * FROM campaigns c WHERE c.public=1 ORDER BY c.post_id DESC"));

foreach($results as $p) {
  draw_campaign_badge($p);
}
?></div><?

get_footer();
