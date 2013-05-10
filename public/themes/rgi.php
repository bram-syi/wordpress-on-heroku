<?

global $TEMPLATE;
$TEMPLATE = (object)array(
  'please' => "Please join me in giving a Rwandan girl the <b>opportunity of a lifetime</b>. Thank you!",
  'title' => "Together we can change a girl's life in Rwanda.",
  'post_title' => "RGI Sponsorship",
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'rgi',
  'goal' => 2000,
  'fields' => array('photo','body'),
  'about_promo' => 'rgi-about'
);

$TEMPLATE->post_content = <<<EOF
Even the brightest girls in Rwanda face obstacles to success in secondary school, including household responsibilities and financial burdens. Yet, the positive effects of education are obvious and widespread as girls become more productive, confident and healthy in the long run.

The Gashora Girls' Academy in Rwanda provides an incredible secondary education for the brightest young girls. Just $2000 provides a scholarship for a girl to attend the Gashora Girls' Academy for an entire year. I want to see the smile on the face of the girl who's life we can change forever - won't you join me? When we reach our goal of $2000, we will all receive a photo and story about the girl we helped in Rwanda.
EOF;

include("default-v1.php");
