<?

get_header();

//add_action('syi_after_post', 'give_the_same_gift', 1,1);
function give_the_same_gift($id) {
  $ids = as_ints(get_post_meta($id, 'donation_items'));
  $did = $ids[0];
  if (!empty($did)) {
    global $wpdb;

    $gift = $wpdb->get_var($wpdb->prepare("select giftID from donationGifts where ID=%d", $did));

    $g = get_gift_where("g.id = $gift", 1);
    draw_gift_details($g);
  }
}

?>

<div style="background: white; padding: 8px;">
<div class="story-panel">
  <div class="frame-shadow"></div>
  <? get_template_part( 'loop', basename(__FILE__, '.php') ); ?>
</div>
</div>

<? 

function single_page_widgets() {
  dynamic_sidebar('story-widgets');
}
add_action('syi_bottom_widgets', 'single_page_widgets');

get_footer();

?>
