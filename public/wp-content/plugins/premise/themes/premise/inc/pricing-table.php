<?php
$columns = premise_get_pricing_columns();
$columnCount = count($columns);
$ctaAlign = premise_get_fresh_design_option('pricing_tier_cta_align');
$wrapWidth = premise_get_fresh_design_option('wrap_width') - (2 * premise_get_fresh_design_option('wrap_padding'));
$margins = (10 * ($columnCount - 1)); // Account for margins
$extraneous = ($columnCount * ((2 * premise_get_fresh_design_option('pricing_tier_border')) + (2 * premise_get_fresh_design_option('pricing_tier_padding')))); // container extraneous
$available = $wrapWidth - $margins - $extraneous;

$max = 0; foreach($columns as $column) { if(count($column['attributes']) > $max) { $max = count($column['attributes']); } }
?>
<style type="text/css">

	.pricing-table .pricing-table-column {
		width: <?php printf('%d', $available / $columnCount); ?>px;
	}

	<?php
	$marker = strtolower(premise_get_pricing_bullet_marker());
	$color = strtolower(premise_get_pricing_bullet_color());
	if($marker != 'default') {
		?>
	#content .pricing-table .pricing-table-column ul, .pricing-table .pricing-table-column ul li {
		list-style: none;
		padding-left: 0;
	}
		<?php
	}
	if($marker != 'none' && $marker != 'default') {
		?>
	#content .pricing-table .pricing-table-column ul li {
		padding-left: 25px;
		background: transparent url(<?php echo get_template_directory_uri(); ?>/images/bullets/<?php echo $marker; ?>-<?php echo $color; ?>.png) no-repeat 0 5px;
	}
		<?php
	}
	?>

</style>
<div class="pricing-table-container">
	<div class="pricing-table">
		<?php $count = 0; foreach($columns as $column) { $count++;  ?>
		<div class="pricing-table-column <?php if($columnCount == $count) { ?>last<?php } ?>">
			<div class="pricing-table-column-header"><?php echo apply_filters('the_title', $column['title']); ?></div>
			<div class="pricing-table-column-features">
				<ul class="pricing-table-column-properties">
					<?php $atts = 0; foreach($column['attributes'] as $attribute) { $atts++; ?>
					<li><?php echo apply_filters('pricing_table_attribute', $attribute); ?></li>
					<?php } ?>
					<?php for($i = $atts; $i < $max; $i++) { ?>
					<li class="nothing">i</li>
					<?php } ?>
				</ul>
				<div class="pricing-table-call-to-action">
					<?php if(!empty($column['callurl']) && !empty($column['calltext'])) { $target = $column['newwindow'] == 'yes' ? 'target="_blank"' : ''; ?>
					<a <?php echo $target; ?> class="cta-align<?php echo $ctaAlign; ?>" href="<?php esc_attr_e($column['callurl']); ?>"><?php echo apply_filters('pricing_table_call_to_action', $column['calltext']); ?></a>
					<?php } ?>
				<br class="clear" />
				</div>
			</div>
		</div>
		<?php } ?>
		<br class="clear" />
	</div>
</div>