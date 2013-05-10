<?php
/*
Template Name: Standard
*/

standard_page();

get_header();
global $post;

?>
<div class="<?= $post->post_name ?>-page standard-page row">
  <div class="page-main based span8">
    <? the_content(); ?>

    <? do_action('page_after_content'); ?>

    <? if (current_user_can("editor")) { ?>
      <form method="POST" class="full-wide admin-actions">
        <b>Admin</b>:
        <?php edit_post_link( 'edit page', '<span class="edit-link">', '</span>'); ?>
      </form>
    <? } ?>

  </div>
  <div class="page-sidebar based span4">
  </div>
</div>

<?
get_footer();
