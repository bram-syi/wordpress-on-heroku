<?

global $header_file;
$header_file = "landing";

global $TEMPLATE;
$TEMPLATE = (object)array(
  'post_title' => "Friends of the Children NY",
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'foc',
  'logo' => "http://foc.seeyourimpact.org/files/2012/02/FOTC_300.jpg",
  'comments' => "Tell [name] why <b>you love</b> FriendsNY's cause!",
  'thumbnail' => '/support/foctix',
  'progress_widget' => FALSE
);

$TEMPLATE->about = <<<EOF
EOF;

$TEMPLATE->banner = <<<EOF
EOF;

$TEMPLATE->start_banner = <<<EOF
EOF;

$TEMPLATE->post_content = <<<EOF
[FOC NY default text]
EOF;

include("default.php");

function foc_quotes($campaign = NULL) {
/*
?><div class="quote"><img class="left mark" src="http://seeyourimpact.org/wp-content/images/quote.png" alt="">By catching children when they're young, bringing professional rigor to the task and making a long-term, virtually unconditional commitment to the children...  Friends of the Children is working to shift expectations about the kinds of changes that can by achieved in a social program that targets children who face multiple risk factors. <span class="author">--  David Bornstein, NY Times</span></div><?
*/
  syi_progress_widget(array(
    'campaign' => $campaign,
    'title' => "",
    'empty_message' => bp_is_my_profile() ? "Find your first supporters by inviting people to this page!" : "Be the first to give!",
    'avatars' => FALSE,
    'limit' => 100
  ));

}


function foc_tickets() {
?>
<div class="tickets-widget widget">
  <p style="padding: 20px 20px 0;">The more tickets you buy, the more lives you change!</p>
  <div style="text-align: center; margin: 10px 0;">
    <a class="button orange-button large-button" style="width: 200px; display: block;" href="https://seeyourimpact.org/cart/?item=50&amp;amount=20"><b>$20</b> (one ticket)</a>
    <a class="button orange-button large-button" style="width: 200px; display: block;" href="https://seeyourimpact.org/cart/?item=50&amp;amount=40"><b>$40</b> (two tickets)</a>
    <a class="button orange-button large-button" style="width: 200px; display: block;" href="https://seeyourimpact.org/cart/?item=50&amp;amount=120"><b>$120</b> (six tickets)</a>
    <a class="button orange-button large-button" style="width: 200px; display: block;" href="https://seeyourimpact.org/cart/?item=50&amp;amount=100">donate any amount</a>
  </div>
</div>
<style>
.tickets-widget { 
  background: #F4F5EA;
  border: 1px solid #ccc;
  box-shadow: 0 0 10px #BBB;
  margin: 10px 5px 20px;
  width: 240px;
}
.tickets-widget .button {
  width: 200px; margin: 10px auto;
}
.tickets-widget .button b { font-size: 120%; }
</style>
<?
}
 

replace_action('draw_campaign_sidebar', 'foc_tickets');
add_action('draw_campaign_sidebar', 'foc_quotes');











