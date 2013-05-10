<? 

function explorer_sidebar() {
?>
  <div id="featured-photo-sidebar" class="panel sidebar-panel current-panel">
    <? causes_widget(array('mode'=>'sidebar')); ?>
  </div>
<?
}
add_action('get_sidebar', 'explorer_sidebar');

global $post, $GIFTS_LOC;
$GIFTS_LOC = "ex/$post->post_name";

get_header(); 
get_template_part( 'loop', basename(__FILE__, '.php') );
get_footer(); 

?>
