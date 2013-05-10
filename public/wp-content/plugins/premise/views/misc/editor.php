<?php
$rows = get_option('default_post_edit_rows');
$rows = ($rows < 3 || $rows > 100) ? 12 : $rows;
$media_buttons = $media_buttons && current_user_can('upload_files');
$rich_editor = user_can_richedit();
$wp_default_editor = wp_default_editor();
$class = '';

global $wp_scripts;
if(!in_array('quicktags', $wp_scripts->done)) {
	wp_print_scripts('quicktags');
}
?>

<div class="premise-editor-area">
	<?php if($rich_editor || $media_buttons) { ?>
	<div class="premise-editor-toolbar">
		<?php if($rich_editor) { ?>

			<?php if('html' == $wp_default_editor) { ?>
			<?php add_filter('the_editor_content', 'wp_htmledit_pre'); ?>
			<a data-mode="html" id="premise-editor-button-html-<?php echo $number; ?>" class="active premise-editor-toolbar-button premise-editor-button-html hide-if-no-js"><?php _e('HTML'); ?></a>
			<a data-mode="tinymce" id="premise-editor-button-preview-<?php echo $number; ?>" class="premise-editor-toolbar-button premise-editor-button-preview hide-if-no-js"><?php _e('Visual'); ?></a>
			<?php } else { ?>
			<?php add_filter('the_editor_content', 'wp_richedit_pre'); ?>
			<?php $class = 'class="premise-the-editor premise-the-editor-'.$number.'"'; ?>
			<a data-mode="html" id="premise-editor-button-html-<?php echo $number; ?>" class="premise-editor-toolbar-button premise-editor-button-html hide-if-no-js"><?php _e('HTML'); ?></a>
			<a data-mode="tinymce" id="premise-editor-button-preview-<?php echo $number; ?>" class="active premise-editor-toolbar-button premise-editor-button-preview hide-if-no-js"><?php _e('Visual'); ?></a>
			<?php } ?>

		<?php } ?>
		<?php if($media_buttons) { ?>
		<div rel="premise-editor-<?php echo $number; ?>" class="premise-media-buttons" id="premise-media-buttons-<?php echo $number; ?>"><?php premise_the_media_buttons(); ?></div>
		<?php } ?>
	</div>
	<?php } ?>

	<?php if($quicktags_before) { ?>

	<script type="text/javascript">
		create_premise_quicktags(<?php echo $number; ?>);
	</script>

	<?php } ?>

	<?php
		$the_editor = "<div id='premise-editor-container-{$number}' class='premise-editor-container'><textarea rows='$rows' $class cols='40' name='$id' id='premise-editor-{$number}'>%s</textarea></div>\n";
		$the_editor = apply_filters('the_editor', $the_editor);
		$the_editor_content = apply_filters('the_editor_content', $content);

		printf($the_editor, $the_editor_content);
	?>

	<?php if(!$quicktags_before) { ?>

	<script type="text/javascript">
		create_premise_quicktags(<?php echo $number; ?>);
	</script>

	<?php } ?>


	<script type="text/javascript">
		var premise_editor_canvas_<?php echo $number; ?> = document.getElementById('<?php echo $id; ?>');
	</script>
</div>

<?php
if($rich_editor) {
	$this->_data_TinyMCESelectors[] = 'premise-the-editor-'.$number;
	add_action('admin_print_footer_scripts', array(&$this, 'addTinyMCEToPremiseEditor'), 26 + $number);
}
?>