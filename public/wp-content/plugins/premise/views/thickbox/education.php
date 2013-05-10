<div class="premise-thickbox-container premise-education-thickbox-container">
<?php
if(empty($_GET['section'])) {
	$advice = $this->getAdvice($this->convertLandingPageToId($this->getLandingPageAdviceType($_GET['post_id'])));
} else {
	$advice = $this->getSingleAdvice($_GET['section']);
}
if(is_wp_error($advice)) {
	?><div class="error fade"><p><?php echo $advice->get_error_message(); ?></p></div><?php
} else {
	$advice['advice'] = do_shortcode($advice['advice']);
	if(!empty($_GET['section'])) {
		echo "<h2>{$advice['name']}</h2>";
	}
	echo $advice['advice'];
	if(!empty($_GET['section'])) {
		?>
		<p>
		<?php printf(__('<a href="%s">&laquo; Back to Copywriting Assistance for %s Pages</a>'), add_query_arg(array('section' => false)), $this->getLandingPageTypeName($_GET['post_id'])); ?>
		</p>
		<?php
	}
}
?>
</div>