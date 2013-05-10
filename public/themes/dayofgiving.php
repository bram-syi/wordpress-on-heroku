<?php

// appears after the banner
function our_branded_header() {
?>
<span style=" text-align: center; margin: 5px 5px 0; font-size: 18px; background: #5F9ABF; padding: 5px; color: #eee; text-decoration: none; display: block;">The annual International Day of Giving is November 18, 2012!</span>
<?
}
add_action('branded_header', 'our_branded_header');

// This script changes all instances of the name HOPE worldwide into their italic font
function our_scripts() {
?>
<script type="text/javascript">
$(function() {
  $("h1, h2.page-title, .campaign-page div.page-content, .page-sidebar .promo").each(function(i, e) {
    el = $(e);
    el.html(el.html().replace(/hope( |&nbsp;)worldwide/gi, 'HOPE&nbsp;<i class="ww">worldwide</i>'));
  });
  
});
</script>
<?
}
add_action('wp_head', 'our_scripts', 100);



require('default.php');

global $TEMPLATE;
$TEMPLATE->thumbnail = '/members/mandyj';

remove_action('draw_campaign_appeal', 'draw_template_appeal');
