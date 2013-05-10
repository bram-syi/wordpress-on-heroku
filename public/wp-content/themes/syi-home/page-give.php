<?
global $wpdb, $GIFTS_LOC;

include_once(SYI_THEME . 'gift-browser.php');

add_action('get_sidebar', 'gift_browser_sidebar');
remove_action('syi_sidebar', 'social_widgets', 5);

$GIFTS_LOC='give';

get_header();

global $post;

?>
<article class="padded">
  <section id="frame">
    <? render_gift_browser($post); ?>
    <div class="frame-shadow"></div>
  </section>
</article>

<?
get_footer();
?>
