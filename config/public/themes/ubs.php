<?

function add_fonts() {
?>
<script type="text/javascript" src="http://use.typekit.com/pnf6ukr.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?
}

global $header_file;
$header_file = "landing";

sharing_init(FALSE);

function campaign_content() {
  ?><div style="background: #92D4EC; color: #2B4E64; padding: 8px; text-align: center; font: 20px Arial,Helvetica,sans-serif;">Choose a gift.  Change a life.  See your impact.</div><?

  syi_give_section();

  // Show sample stories regardless of campaign donations
  ?>
  <div class="sample-stories" style="padding: 0 45px 40px 30px;">
    <h2 class="section-header" style="margin-left: -15px; margin-bottom: 10px;">You'll see the impact of your donation on the actual recipient.</h2>
    <?= stories_shortcode(array( 'limit' => 6)); ?>
  </div>
  <?
}

function after_appeal() {
?>
  <p class="tk-caflisch-script-pro tk-ff-market-web best-wishes">Best wishes for the new year!</p>
<?
}

add_action('wp_head', 'add_fonts');
add_action('draw_campaign_content', 'campaign_content', -1);
add_action('after_campaign_appeal', 'after_appeal');
remove_action('draw_campaign_content', 'draw_campaign_content');
remove_action('draw_sharing_tools', 'draw_sharing_tools');
remove_action('draw_fundraiser_ad', 'draw_fundraiser_ad');
remove_action('draw_campaign_stats', 'draw_campaign_stats');
