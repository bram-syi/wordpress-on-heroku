<?php

draw_login_bar();

$bg_image = get_stylesheet_directory_uri() . '/campaign-page-banner.png';

$blog = (object)array(
  'text' => get_bloginfo('name'),
  'href' => get_bloginfo('wpurl') . '/',
  'link' => true
);

global $post;
if ($post->post_parent > 0) {
  $campaign = (object)array(
    'text' => $post->post_title,
    'href' => get_permalink($post->ID),
    'link' => false
  );

  $parent = get_post($post->post_parent);

  $org = (object)array(
    'text' => $parent->post_title,
    'href' => get_permalink($parent->ID),
    'link' => true
  );
}
else {
  $org = (object)array(
    'text' => $post->post_title,
    'href' => get_permalink($post->ID),
    'link' => false
  );
}

$link_separator = '&nbsp;&raquo;&nbsp;';

function make_link($obj) {
  if ($obj->link) {
    return "<a style=\"font-weight:bold; text-decoration:underline; color:#ff6600;\" href=\"$obj->href\">$obj->text</a>";
  }
  else {
    return "<span style=\"font-weight:bold;\">$obj->text</span>";
  }
}

?>
<div class="custom-tab-bar" style="height:55px; background: #92D4EC url(<?= $bg_image ?>) no-repeat 0 0;">
  <a href="http://seeyourimpact.org/" style="display:block;"><span class="left" style="display: block;height:50px; width: 200px;"></span></a>
  <div style="padding: 21px 0 0 215px;">
<?

  //error_log(var_export(array($blog, $org, $campaign),1));
  if ($blog->href != $org->href) {
    echo $link_separator, make_link($blog);

  }
  echo $link_separator, make_link($org);
  if (isset($campaign)) {
    echo $link_separator . make_link($campaign);
  }

?>
  </div>
</div>
