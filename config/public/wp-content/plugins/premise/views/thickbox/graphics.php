<?php
$categories = $this->getGraphicsCategories();
$graphics = $this->getGraphics(10, $_REQUEST['paged'], $_REQUEST['premise-graphic-type'], $_REQUEST['premise-search-graphics-input']);
?>
<div class="premise-thickbox-container">

	<?php
	if(is_wp_error($categories)) {
		?><div class="error fade"><p><?php echo $categories->get_error_message(); ?></p></div><?php
	} elseif(is_wp_error($graphics)) {
		?><div class="error fade"><p><?php echo $graphics->get_error_message(); ?></p></div><?php
	} else {
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'prev_text' => __('&laquo;'),
			'next_text' => __('&raquo;'),
			'total' => ceil($graphics['total'] / 10),
			'current' => $graphics['current']
		));
	?>
	<form method="get" id="filter">
		<p class="search-box">
			<input type="hidden" name="send_to_premise_field_id" value="<?php esc_attr_e($_REQUEST['send_to_premise_field_id']); ?>" />
			<label class="screen-reader-text" for="premise-search-graphics-input"><?php _e('Search Graphics:'); ?></label>
			<input id="premise-search-graphics-input" type="text" value="<?php esc_attr_e($_REQUEST['premise-search-graphics-input']); ?>" name="premise-search-graphics-input" />
			<input type="hidden" id="tab-premise-resource-graphics" name="tab" value="premise-resources-graphics" />
			<input type="hidden" id="send_to_premise_field_id_hidden_field" name="send_to_premise_field_id" value="<?php esc_attr_e($_REQUEST['send_to_premise_field_id']); ?>" />
			<select name="premise-graphic-type" id="premise-search-graphics-category">
				<option value=""><?php _e('All'); ?></option>
				<?php foreach($categories as $key => $category) { ?>
				<option <?php if($_REQUEST['premise-graphic-type'] == $category['id']) { ?>selected="selected"<?php } ?> value="<?php esc_attr_e($category['id']); ?>"><?php esc_html_e($category['name']); ?></option>
				<?php } ?>
			</select>
			<input class="button" type="submit" value="<?php _e('Search Graphics'); ?>" />
		</p>
	</form>

	<?php
	$base = add_query_arg(array('post_id' => $_GET['post_id'], 'tab' => $_GET['tab'], 'send_to_premise_field_id' => $_REQUEST['send_to_premise_field_id']), admin_url('media-upload.php'));
	if(!empty($_REQUEST['premise-search-graphics-input'])) {
		$search = $_REQUEST['premise-search-graphics-input'];
		$current = 'search';
	} else {
		$current = $_GET['premise-graphic-type'];
	}
	?>

	<?php if(empty($categories)) { ?>
	<br class="clear" />
	<p><?php _e('Error retrieving image information.  Please check your API key.'); ?></p>
	<?php } else {  ?>

	<ul class="subsubsub" style="clear: both;">
		<li><a <?php if(empty($current)) { ?>class="current"<?php } ?> href="<?php esc_attr_e(add_query_arg(array('premise-graphic-type'=>null), $base)); ?>"><?php _e('All'); ?></a> |</li>
		<?php $counter = 0; $count = count($categories); foreach($categories as $key => $category) { $counter++; ?>
		<li><a <?php if($current == $category['id']) { ?>class="current"<?php } ?> href="<?php esc_attr_e(add_query_arg(array('premise-graphic-type'=>$category['id']), $base)); ?>"><?php printf('%s (%d)', $category['name'], $category['count']); ?></a> <?php if($counter < $count) { ?>|<?php } ?></li>
		<?php } ?>
		<?php if(!empty($search)) { ?>
		<li>| <strong><?php printf(__('Search for <em>%s</em> (%d)'), $search, $graphics['total']); ?></strong></li>
		<?php } ?>
	</ul>

	<div id="ajax-loading-container">
		<img alt="" style="display: block; margin: 10px auto; visibility: hidden;" id="ajax-loading" src="<?php esc_attr_e('wp-admin/images/wpspin_light.gif'); ?>" />
	</div>

	<br class="clear" />
	<?php if(!empty($graphics['images'])) { ?>
	<div class="tablenav">
	<?php if ( $page_links ) { ?>
		<div class="tablenav-pages"><?php
			$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
								number_format_i18n( ( $graphics['current'] - 1 ) * 10 + 1 ),
								number_format_i18n( min( $graphics['current'] * 10, $graphics['total']) ),
								number_format_i18n( $graphics['total']),
								$page_links
								);
			echo $page_links_text;
			?></div>
		<?php
		}
	?>
	</div>
	<table class="widefat fixed">
		<thead>
			<tr>
				<th scope="col"><?php _e('Thumbnail'); ?></th>
				<th scope="col"><?php _e('Name'); ?></th>
				<th scope="col"><?php _e('Actions'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col"><?php _e('Thumbnail'); ?></th>
				<th scope="col"><?php _e('Name'); ?></th>
				<th scope="col"><?php _e('Actions'); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach($graphics['images'] as $graphic) { ?>
			<tr>
				<td><img src="<?php esc_attr_e($graphic['thumbnail_url']); ?>" width="100" /></td>
				<td><?php esc_html_e($graphic['name']); ?></td>
				<td><a class="premise-graphic-use-this" href="#" data-filename="<?php esc_attr_e($graphic['filename']); ?>" data-slug="<?php esc_attr_e($graphic['id']); ?>" data-name="<?php esc_attr_e($graphic['name']); ?>"><?php _e('Use Image'); ?></a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="tablenav">
	<?php if ( $page_links ) { ?>
		<div class="tablenav-pages"><?php
			echo $page_links_text;
			?></div>
		<?php
		}
	?>
	</div>
	<?php } else { ?>
	<p><?php _e('No graphics were found that matched your criteria.'); ?></p>
	<?php } ?>

	<?php } ?>
	<?php } ?>
</div>