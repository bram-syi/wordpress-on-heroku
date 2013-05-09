<?
$HAS_SIDEBAR = defined('HAS_SIDEBAR') && HAS_SIDEBAR == TRUE;
?>

<?php get_header(); ?>
<?php roots_content_before(); ?>
<div id="content" class="<?php echo CONTAINER_CLASSES; ?> padded">
<?php roots_main_before(); ?>
  <div id="main" class="<?php echo $HAS_SIDEBAR ? MAIN_CLASSES : FULLWIDTH_CLASSES; ?>" role="main">
    <section>
      <?php roots_loop_before(); ?>
      <?php get_template_part('loop', 'page'); ?>
      <?php roots_loop_after(); ?>
    </section>
  <? if (current_user_can("editor")) { ?>
    <form method="POST" class="full-wide admin-actions">
      <?php edit_post_link( 'edit page', '<span class="edit-link"><i class="icon icon-edit"></i> ', '</span>'); ?>
    </form>
  <? } ?>
  </div><!-- /#main -->

<?php roots_main_after(); ?>

<? if ($HAS_SIDEBAR == TRUE) { ?>
  <?php roots_sidebar_before(); ?>
  <aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementar
y">
    <?php roots_sidebar_inside_before(); ?>
    <?php get_sidebar(); ?>
    <?php roots_sidebar_inside_after(); ?>
  </aside><!-- /#sidebar -->
  <?php roots_sidebar_after(); ?>
<? } ?>

</div><!-- /#content -->
<?php roots_content_after(); ?>
<?php get_footer(); ?>
