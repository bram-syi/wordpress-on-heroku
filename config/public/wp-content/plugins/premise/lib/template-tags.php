<?php
/**
 * This file contains all template tags necessary for the Premise landing pages plugin.
 * Template tags are generally used on the frontend or in the Landing Page templates.  There
 * are some template tags in this file designed for use on particular landing pages, as well
 * as some template tags needed for all the landing pages.
 *
 * Most of the names should be self explanatory.  For any template tag accepting a post ID, you can
 * pass nothing and it will automatically detect the global post.
 */

/**
 * This particular function is necessary because the callback to wp_iframe
 * must be a string or else warnings get thrown and you get crazy messages
 * in the WP admin.  It just delegates back to the Premise plugin object.
 * @return void
 */
function premise_thickbox() {
	global $Premise;
	return $Premise->displayPremiseResourcesThickboxOutput();
}

function premise_the_editor($content, $id = 'content', $prev_id = 'title', $media_buttons = true, $tab_index = 2, $quicktags_before = false) {
	global $Premise;
	$Premise->theEditor($content, $id, $prev_id, $media_buttons, $tab_index, $quicktags_before);
}

function premise_the_media_buttons() {
	ob_start();
	do_action('media_buttons');
	$buttons = ob_get_clean();
	$buttons = preg_replace('/id=(\'|").*?(\'|")/', '', $buttons);
	echo $buttons;
}

function premise_get_media_upload_src($type, $optional = array()) {
	global $post_ID, $temp_ID;
	$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
	$upload_iframe_src = add_query_arg('post_id', $uploading_iframe_ID, 'media-upload.php');

	if ( 'media' != $type ) {
		$upload_iframe_src = add_query_arg('tab', $type, $upload_iframe_src);
	}
	if(!empty($optional) && is_array($optional)) {
		$upload_iframe_src = add_query_arg($optional, $upload_iframe_src);
	}

	$upload_iframe_src = apply_filters($type . '_upload_iframe_src', $upload_iframe_src);

	return add_query_arg('TB_iframe', true, $upload_iframe_src);
}

function premise_get_version() {
	global $Premise;
	return apply_filters('premise_get_version', $Premise->_data_Version);
}
function premise_the_version() {
	echo apply_filters('premise_the_version', premise_get_version());
}

function premise_active_admin_tab($tab) {
	if($_GET['page'] == $tab) { echo 'nav-tab-active'; }
}

/// GENERAL TEMPLATE TAGS

function premise_get_header_copy($postId = null) {
	global $Premise;
	return apply_filters('premise_get_header_copy', $Premise->getHeaderCopy($postId), $postId);
}
function premise_the_header_copy($postId = null) {
	echo apply_filters('premise_the_header_copy', premise_get_header_copy($postId), $postId);
}

function premise_should_have_header_image($postId = null) {
	global $Premise;
	return apply_filters('premise_should_have_header_image', $Premise->shouldHaveHeaderImage($postId));
}

function premise_get_header_image($postId = null) {
	global $Premise;
	return apply_filters('premise_get_header_image', $Premise->getHeaderImage($postId), $postId);
}
function premise_the_header_image($postId = null) {
	echo apply_filters('premise_the_header_image', premise_get_header_image($postId), $postId);
}

function premise_get_footer_copy($postId = null) {
	global $Premise;
	return apply_filters('premise_get_footer_copy', $Premise->getFooterCopy($postId), $postId);
}
function premise_the_footer_copy($postId = null) {
	echo apply_filters('premise_the_footer_copy', premise_get_footer_copy($postId), $postId);
}

function premise_should_have_footer($postId = null) {
	global $Premise;
	return apply_filters('premise_should_have_footer', $Premise->shouldHaveFooter($postId), $postId);
}

function premise_should_have_header($postId = null) {
	global $Premise;
	return apply_filters('premise_should_have_header', $Premise->shouldHaveHeader($postId), $postId);
}

/// VIDEO TEMPLATE TAGS

function premise_get_video_embed_code($postId = null) {
	global $Premise;
	return apply_filters('premise_get_video_embed_code', $Premise->getVideoEmbedCode($postId), $postId);
}
function premise_the_video_embed_code($postId = null) {
	echo apply_filters('premise_the_video_embed_code', premise_get_video_embed_code($postId), $postId);
}

function premise_get_video_copy($postId = null) {
	global $Premise ;
	return apply_filters('premise_get_video_copy', $Premise->getVideoCopy($postId), $postId);
}
function premise_the_video_copy($postId = null) {
	echo apply_filters('the_content', premise_get_video_copy($postId), $postId);
}

function premise_get_video_below_copy($postId = null) {
	global $Premise ;
	return apply_filters('premise_get_video_below_copy', $Premise->getVideoBelowCopy($postId), $postId);
}
function premise_the_video_below_copy($postId = null) {
	echo apply_filters('the_content', premise_get_video_below_copy($postId), $postId);
}

function premise_get_video_align($postId = null) {
	global $Premise;
	return apply_filters('premise_get_video_align', $Premise->getVideoAlign($postId), $postId);
}
function premise_the_video_align($postId = null) {
	echo apply_filters('premise_the_video_align', premise_get_video_align($postId), $postId);
}

function premise_has_video_image($postId = null) {
	$value = trim(premise_get_video_image($postId));
	return apply_filters('premise_had_video_image', !empty($value), $postId);
}
function premise_get_video_image($postId = null) {
	global $Premise;
	return apply_filters('premise_get_video_image', $Premise->getVideoImage($postId), $postId);
}
function premise_the_video_image($postId = null) {
	echo apply_filters('premise_the_video_image', premise_get_video_image($postId), $postId);
}

function premise_get_video_image_title($postId = null) {
	global $Premise;
	return apply_filters('premise_get_video_image_title', $Premise->getVideoImageTitle($postId), $postId);
}
function premise_the_video_image_title($postId = null) {
	echo apply_filters('premise_the_video_image_title', premise_get_video_image_title($postId), $postId);
}

/// CONTENT SCROLLER

/**
 * This function returns an array of arrays.  Each inner array is associative
 * and has data 'title', and 'text'
 * @return array
 */
function premise_get_content_tabs($postId = null) {
	global $Premise;
	return apply_filters('premise_get_content_tabs', $Premise->getContentScrollers($postId));
}
function premise_the_content_tabs($postId = null, $before = '', $after = '', $beforeTitle = '', $afterTitle = '', $beforeContent = '', $afterContent = '') {
	$tabs = premise_get_content_tabs($postId);

	$output = '';
	if(!empty($tabs)) {
		$output .= $before;
		foreach($tabs as $key => $tab) {
			$output .= $beforeTitle.$tab['title'].$afterTitle.$beforeContent.$tab['text'].$afterContent;
		}
		$output .= $after;
	}

	echo apply_filters('premise_the_content_tabs', $output);
}

function premise_should_show_content_scroller_tabs($postId = null) {
	global $Premise;
	return apply_filters('premise_should_show_content_scroller_tabs', $Premise->getContentScrollerShowTabs($postId), $postId);
}
function premise_should_show_content_scroller_arrows($postId = null) {
	global $Premise;
	return apply_filters('premise_should_show_content_scroller_arrows', $Premise->getContentScrollerShowArrows($postId), $postId);
}

/// PRICING

function premise_get_pricing_columns($postId = null) {
	global $Premise;
	return apply_filters('premise_get_pricing_columns', $Premise->getPricingColumns($postId), $postId);
}

function premise_the_above_pricing_table_content($postId = null) {
	echo apply_filters('the_content', premise_get_above_pricing_table_content($postId), $postId);
}
function premise_get_above_pricing_table_content($postId = null) {
	global $Premise;
	return apply_filters('premise_get_above_pricing_table_content', $Premise->getAbovePricingTableContent($postId), $postId);
}

function premise_the_below_pricing_table_content($postId = null) {
	echo apply_filters('the_content', premise_get_below_pricing_table_content($postId), $postId);
}
function premise_get_below_pricing_table_content($postId = null) {
	global $Premise;
	return apply_filters('premise_get_below_pricing_table_content', $Premise->getBelowPricingTableContent($postId), $postId);
}

function premise_get_pricing_bullet_marker($postId = null) {
	global $Premise;
	return apply_filters('premise_get_pricing_bullet_marker', $Premise->getPricingBulletMarker($postId), $postId);
}
function premise_get_pricing_bullet_color($postId = null) {
	global $Premise;
	return apply_filters('premise_get_pricing_bullet_color', $Premise->getPricingBulletColor($postId), $postId);
}

/// OPT-IN TEMPLATE TAGS

function premise_get_optin_copy($postId = null) {
	global $Premise;
	return apply_filters('premise_get_optin_copy', $Premise->getOptinCopy($postId), $postId);
}
function premise_the_optin_copy($postId = null) {
	echo apply_filters('premise_the_optin_copy', premise_get_optin_copy($postId), $postId);
}

function premise_get_optin_below_copy($postId = null) {
	global $Premise;
	return apply_filters('premise_get_optin_below_copy', $Premise->getOptinBelowCopy($postId), $postId);
}
function premise_the_optin_below_copy($postId = null) {
	echo apply_filters('the_content', premise_get_optin_below_copy($postId), $postId);
}

function premise_get_optin_form_code($postId = null) {
	global $Premise;
	return apply_filters('premise_get_optin_form_code', $Premise->getOptinFormCode($postId), $postId);
}
function premise_the_optin_form_code($postId = null) {
	echo apply_filters('premise_the_optin_form_code', premise_get_optin_form_code($postId), $postId);
}


function premise_get_optin_align($postId = null) {
	global $Premise;
	return apply_filters('premise_get_optin_align', $Premise->getOptinAlign($postId), $postId);
}
function premise_the_optin_align($postId = null) {
	echo apply_filters('premise_the_optin_align', premise_get_optin_align($postId), $postId);
}

/// LONG COPY
function premise_get_subhead($postId = null) {
	global $Premise;
	return apply_filters('premise_get_subhead', $Premise->getSubhead($postId), $postId);
}
function premise_the_subhead($postId = null) {
	echo apply_filters('premise_the_subhead', premise_get_subhead($postId), $postId);
}