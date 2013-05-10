<?php
/*
Template Name: Video
Template Description: Embed a video that makes it easier to sell your product or subscription to potential customers.
*/
get_header();
	$entryVideoWidth = intval(premise_get_fresh_design_option('wrap_width')) - intval(premise_get_fresh_design_option('video_holder_padding') * 2 + 2 * premise_get_fresh_design_option('video_holder_border'));
	?>
	<div id="content" class="hfeed">
		<?php the_post(); ?>
		<div class="hentry">
			<?php include('inc/headline.php'); ?>

			<div class="entry-video entry-video-align-<?php premise_the_video_align(); ?>">
				<div class="container-border">
					<div class="entry-video-video">
						<?php if(premise_has_video_image()) { ?>
						<a rel="#entry-video-video-embed" class="video-overlay" href="#entry-video-video-embed"><img src="<?php premise_the_video_image(); ?>" alt="<?php premise_the_video_image_title(); ?>" /></a>
						<?php } else { premise_the_video_embed_code(); } ?>
					</div>
					<?php if(premise_has_video_image()) { ?><div id="entry-video-video-embed" class="entry-video-video-embed hide"><?php premise_the_video_embed_code(); ?><span class="clear"></span></div><?php } ?>
					<?php if(premise_get_video_align() != 'center') { ?>
					<div class="entry-video-content"><?php echo apply_filters('the_content', premise_get_video_copy()); ?></div>
					<?php } ?>
					<span class="clear"></span>
				</div>
				<span class="clear"></span>
			</div>
			<div class="entry-content"><?php echo apply_filters('the_content', premise_get_video_below_copy()); ?></div>
		</div>
	</div><!-- end #content -->
	<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function(){
			jQuery("a.video-overlay").overlay({mask: '#000', effect: 'apple'});
		});
	</script>
	<?php
get_footer();