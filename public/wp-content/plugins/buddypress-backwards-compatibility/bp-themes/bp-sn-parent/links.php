<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>
<div class="blog">

	<div id="content">

		<?php do_action( 'bp_before_blog_links' ) ?>

		<div class="page" id="blog-latest">

			<h2 class="pagetitle"><?php _e( 'Links', 'buddypress' ) ?></h2>

			<ul id="links-list">
				<?php get_links_list(); ?>
			</ul>

		</div>

		<?php do_action( 'bp_after_blog_links' ) ?>

	</div>

</div>
<?php get_footer(); ?>
