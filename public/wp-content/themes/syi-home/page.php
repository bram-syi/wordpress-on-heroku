<?

function list_subpages() {
  global $post;

  $id = $post->post_parent;
  if ($id == 0)
    $id = $post->ID;

  $tag = $post->post_parent == 0 ? 'h1' : 'h2';

  ?>
  <div class="sidebar-panel">
  <<?=$tag?> class="sidebar-title banner"><?= get_the_title($id); ?></<?=$tag?>>
  <? draw_promo_content($post->post_name . "-sidebar"); ?>
  <? wp_list_pages("child_of=$id&depth=1&title_li="); ?>
  </div><?
}

global $post, $GIFTS_LOC;
$GIFTS_LOC = "p/$post->post_name";

add_action('get_sidebar', 'list_subpages');

get_header() 

?>

  <? get_template_part( 'loop', basename(__FILE__, '.php') ); ?>

<? get_footer() ?>
