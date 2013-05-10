<?php

// this header is for fundraiser pages where we do not want the usual page
// header:
// - seeyourimpact logo
// - "how it works" link
// - "see real stories" link

draw_login_bar();

global $context;
do_action('branded_header', $context);
