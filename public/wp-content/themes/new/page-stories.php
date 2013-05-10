<? 
function show_stories_promo() {
  echo '<div class="sidebar-panel">';
  draw_promo_content("stories-sidebar", false);
  echo '</div>';
}
add_action('get_sidebar', 'show_stories_promo');

function page_after_content() {
  draw_widget('stories_widget', array(
    'featured_only' => true,
    'title' => 'Featured Stories',
    'cols' => 6,
    'large' => true,
    'limit' => 32
  ));
}
add_action('page_after_content', 'page_after_content');

include("standard-page.php");
