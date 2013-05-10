<?php 

define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation
include_once('wp-load.php');

global $post;

$ids = array_map('intval', explode('/', $_REQUEST['id']));
if (count($ids) != 2)
  die();

if (isset($_REQUEST['featured'])) {
  $feat = ($_REQUEST['featured'] == 1 || $_REQUEST['featured'] == 'true') ? 1 : 0;
  set_story_featured(intval($ids[0]), intval($ids[1]), $feat); 
  echo $feat;
  die();
}

switch_to_blog($ids[0]);
$post = get_post($ids[1]);
setup_postdata($post);
?>
<div class="based content">
<article class="hentry post type-post padded">
<h1 class="entry-title"><? the_title(); ?></h1>
<section class="entry-content">
<? the_content(); ?>
</section>
<? if($_GET['full']) { do_action('syi_after_post', $post, true); } ?>

</article>
</div>
