<?

global $TEMPLATE;
$TEMPLATE = (object)array(
  'please' => "Help me help these children, and I'll match every dollar of your donation!",
  'title' => "Join me to help the most at-risk children in New York City.",
  'post_title' => "Friends of Children NY",
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'foc',
  'fields' => array('photo','body', 'goal'),
  'logo' => "http://foc.seeyourimpact.org/files/2012/02/FOTC_300.jpg"
);

$TEMPLATE->post_content = <<<EOF
For the month of April, I will be participating in a campaign to change the destiny of the most vulnerable children in New York City. You can join me in supporting this cause by making a donation on my behalf. Your donation will benefit the Achievers at Friends of the Children NY, an early-intervention program that inserts a consistent and responsible adult in the lives of New York City's most at-risk children starting in kindergarten and continuing that commitment through high school graduation. Their model is based on well-documented research indicating that the single greatest success factor in the life of an at-risk child is a committed role model.   

Please join me to support this program! 100% of donations go directly to Friends of the Children NY and you'll receive a photo and story about the child you helped. Thank you!
EOF;

include("default-v1.php");
