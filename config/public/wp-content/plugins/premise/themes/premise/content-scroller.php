<?php
/*
Template Name: Content Scroller
Template Description: A simple content scroller.
*/

get_header();
	$width = (premise_get_fresh_design_option('wrap_width') - 2 * premise_get_fresh_design_option('wrap_padding') - 90);
	?>
	<style type="text/css">
		#content .coda-slider, #content .coda-slider .panel {
			width: <?php echo absint($width); ?>px;
		}
		#content .coda-slider .container-border {
			width: <?php echo (absint($width) - 2); ?>px;
		}
	</style>

	<div id="content" class="hfeed">
		<?php the_post(); ?>
		<div class="hentry">
			<?php include('inc/headline.php'); ?>
			<?php
			$tabs = premise_get_content_tabs();
			if(!empty($tabs)) {
				?>
				<div class="coda-slider-wrapper">
					<?php if(premise_should_show_content_scroller_tabs()) { ?>
					<div id="coda-nav-1" class="coda-nav">
						<ul>
							<?php foreach($tabs as $key => $tab) { ?>
							<li class="tab<?php esc_attr_e($key+1); ?>"><a href="#<?php esc_attr_e($key+1); ?>"><?php echo apply_filters('the_title', $tab['title']); ?></a></li>
							<?php } ?>
						</ul>
					</div>
					<?php } ?>

					<?php if(premise_should_show_content_scroller_arrows()) { ?>
					<div id="coda-nav-left-1" class="coda-nav-left"><a href="#">&laquo;</a></div>
					<?php } else { ?>
					<div class="coda-nav-left-blank"></div>
					<?php } ?>

					<div class="coda-slider preload" id="coda-slider-1">
						<?php foreach($tabs as $tab) { ?>
						<div class="panel">
							<div class="container-border">
								<div class="panel-wrapper">
									<h2 class="title"><?php echo apply_filters('the_title', $tab['title']); ?></h2>
									<?php echo apply_filters('the_content', $tab['text']); ?>
								</div>
							</div>
						</div>
						<?php } ?>
					</div><!-- .coda-slider -->

					<?php if(premise_should_show_content_scroller_arrows()) { ?>
					<div id="coda-nav-right-1" class="coda-nav-right"><a href="#">&raquo;</a></div>
					<?php } else { ?>
					<div class="coda-nav-right-blank"></div>
					<?php } ?>
				</div><!-- .coda-slider-wrapper -->
				<?php
			}
			?>

			<script type="text/javascript">
				var $ = jQuery;
				jQuery(document).ready(function($) {
					jQuery('#coda-slider-1').codaSlider(
						{
							dynamicArrows: false,
							dynamicTabs: false
						}
					);
				});
			</script>
		</div>
		<?php ?>
	</div><!-- end #content -->
	<?php
get_footer();