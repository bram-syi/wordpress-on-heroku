<?php
$title = esc_attr(trim($seo['title']));
if(empty($title)) {
	$title = esc_attr($post->post_title);
}
if(!empty($title)) {
?>
<meta name="title" content="<?php echo $title; ?>" />
<?php
}

$description = esc_attr(trim($seo['description']));
if(empty($description)) {
	$description = esc_attr(substr(strip_tags($post->post_content), 0, 160));
}
if(!empty($description)) {
	?>
	<meta name="description" content="<?php esc_attr_e($description); ?>" />
	<?php
}

$keywords = esc_attr(trim($seo['keywords']));
if(!empty($keywords)) {
?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php
}

$canonical = esc_attr(trim($seo['canonical']));
if(!empty($canonical)) {
?>
<link rel="canonical" href="<?php echo $canonical; ?>" />
<?php
}


if($noarchive) {
?>
<meta name="robots" content="noarchive" />
<?php
}

$noarchive = $seo['noarchive'] == 1;
$noindex = $seo['noindex'] == 1;
$nofollow = $seo['nofollow'] == 1;
if($noarchive || $noindex || $nofollow) {
	$metas = array();
	foreach(array('noarchive', 'noindex', 'nofollow') as $possibleMeta) {
		if($$possibleMeta) { $metas[] = $possibleMeta; }
	}
?>
<meta name="robots" content="<?php echo implode(',',$metas); ?>" />
<?php
}

