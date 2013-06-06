<?php

 if( defined("SITEURL_PATTERN") && defined("SITEURL_REPLACE")) {

    function syi_siteurl_filter($siteurl) {
      $new = preg_replace(constant("SITEURL_PATTERN"), constant("SITEURL_REPLACE"),$siteurl);
      return $new;
    }

    add_filter("option_siteurl","syi_siteurl_filter");
    add_filter("site_url","syi_siteurl_filter");
    add_filter("content_url","syi_siteurl_filter");
    add_filter("option_home","syi_siteurl_filter");
    add_filter("home_url","syi_siteurl_filter");

  }
?>
