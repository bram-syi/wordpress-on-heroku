<?

function kenyagirls_editor_top() {
?>
  <h1>Start a fundraiser for Kenya girls</h1>
<?
}

function kenyagirls_defaults($args) {
  $args['syi_tag'] = 'kenyagirls';
  $args['theme'] = 'kenyagirls';
  return $args;
}

function kenyagirls_after_appeal() {
?>
<p><strong>Join me in giving today.</strong> Together we can provide a route to safety and opportunity. A small gift opens the door.</p>

<ol>
<li><strong>Donate $25</strong> or more on this page.</li>
<li><strong>Email this page</strong> to your friends and ask them to join.</li>
<li><strong>Share it</strong> on Facebook and Twitter.</li>
</ol>

<p>Please give now to impact a real life.</p>

<div id="video"><iframe src="http://player.vimeo.com/video/29861914?title=0&amp;byline=0&amp;portrait=0" frameborder="0" width="512" height="288"></iframe><span style="font-size: 10pt;">100% of your gift will go to development organization World Vision, where it will provide education and safe harbor for Kenyan girls in desperate need.</span></div>

<?
}

add_action('after_campaign_appeal_message', 'kenyagirls_after_appeal');
add_action('campaign_editor_top', 'kenyagirls_editor_top');
add_filter('campaign_editor_defaults', 'kenyagirls_defaults');
