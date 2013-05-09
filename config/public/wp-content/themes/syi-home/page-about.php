<?

function list_subpages() {
  global $post;
  $id = $post->ID;

  ?>
  <div class="sidebar-panel">
  <h2 class="sidebar-title banner"><?= get_the_title($id); ?></h2>
  <? wp_list_pages("child_of=$id&depth=1&title_li="); ?>
  </div><?
}

add_action('get_sidebar', 'list_subpages');

get_header() 

?>

<? get_template_part( 'loop', basename(__FILE__, '.php') ); ?>

<? get_footer() ?>
