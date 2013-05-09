<?
global $post, $wpdb;

function gift_sidebar() {
  global $post;
?>
  <div id="gift-details-sidebar" class="panel sidebar-panel charity-panel evs">
    <? draw_promo_content("pay-sidebar", false); ?>
    <p><a href="/give/#" class="button green-button medium-button">See all available gifts</a></p>
  </div>
<?
}
add_action('get_sidebar', 'gift_sidebar');
remove_action('syi_sidebar', 'social_widgets', 5);

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<article class="padded"><section id="frame"><div id="gift-browser" class="panel current-panel gift-browser ev"><div id="gift-details" class="panel gift-browser-panel current-panel">
<?

$g = get_gift_where("g.post_id = " . $post->ID, true);
$g = get_gift_details($g['id']);

if ($g['towards_gift_id'] > 0)
  draw_agg_gift_details($g);
else
  draw_gift_details($g);
?></div></div></section></article>
<?php endwhile; // End the loop. Whew. ?>

<?  get_footer(); ?>
