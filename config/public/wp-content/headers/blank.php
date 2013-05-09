<?php

// this header is for fundraiser pages where we do not want the usual page
// header:
// - seeyourimpact logo
// - "how it works" link
// - "see real stories" link

if ($blog_id == 1) {
  draw_login_bar();
  // print no header, we are on a fundraiser page
}
else {
  // do the campaign page header
  require('campaign-page.php');
}
