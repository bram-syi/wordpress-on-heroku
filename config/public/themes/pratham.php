<?

global $header_file;
$header_file = 'pratham';

global $TEMPLATE;
$TEMPLATE = (object)array(
  'please' => FALSE, //"Please join me to help make that happen, one child at a time - and see the lives we change!",
  'title' => "I believe <u>all</u> children have the right to an education.",
  'post_title' => "Pratham fundraiser",
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'pratham',
//   'goal' => 500,
  'comments' => "Tell [name] why <b>you love</b> Pratham's cause!",
);

global $event_id;
$title = get_the_title();
$TEMPLATE->short_form = $event_id <= 0 || empty($title) || ($title == $TEMPLATE->post_title);
if ($TEMPLATE->short_form) {
  $TEMPLATE->fields = array('photo', 'body', 'goal');
  $TEMPLATE->body_label = "<b style='display: block; margin-bottom: -10px;font-size:110%;'>I'm proud to support Pratham because...</b>";
}

$TEMPLATE->post_content = <<<EOF
through them I can help solve a seemingly overwhelming problem by giving to one child at a time.  You and everyone I'm reaching out to can do the same, and together, our 'village' can make a difference for those in India!
EOF;

function pratham_leadin($content) {
  return "<b>I'm proud to support Pratham</b> because " . trim($content);
}
add_filter('campaign_content', 'pratham_leadin');

$TEMPLATE->before = <<<EOF
<strong>Did you know</strong> almost half of India's 210 million children can't read? Without an education, they are unlikely ever to break the cycle of poverty that often ends in crime, addiction, and abuse.

<strong>Luckily, there's Pratham</strong>, an organization whose mission is to get every child in India in school and learning well, through literary programs and volunteer teachers. And IT'S WORKING: since 1994, Pratham has changed the lives of millions of children in 21 Indian states!
EOF;

include("default.php");

add_filter('show_donor_last_names', '__return_true');
