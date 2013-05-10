<? 
function blog_sidebar() {
  global $post;

  $old_post = $post;
  $post = get_page_by_path("blog");
  setup_postdata($post);

  ?>
  <div class="sidebar-panel">
  <h2 class="sidebar-title banner"><?= the_title(); ?></h2>
  <? the_content(); ?>
  <div class="blog-tags">
    <? wp_tag_cloud(array(
      'smallest' => 12,
      'largest' => 18,
      'number' => 24
    )); ?>
  </div>
  </div><?

  $post = $old_post;
  setup_postdata($post);
}
add_action('get_sidebar', 'blog_sidebar');

get_header();

?>

  <? get_template_part( 'loop', basename(__FILE__, '.php') ); ?>

<? get_footer(); ?>
