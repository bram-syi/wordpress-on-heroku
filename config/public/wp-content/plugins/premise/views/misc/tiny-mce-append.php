<script type="text/javascript">
	var selector = '<?php echo array_shift($this->_data_TinyMCESelectors); ?>';
	tinyMCEPreInit.mceInit.editor_selector = selector;
	tinyMCE.init(tinyMCEPreInit.mceInit);
</script>
