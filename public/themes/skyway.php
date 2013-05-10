<?

global $TEMPLATE;
$TEMPLATE = (object)array(
  'please' => 'Help me empower the youth of Renton/Skyway B&G Club! Thank you!',
  'title' => "The kids of Washington need our help!",
  'post_title' => "SkyWay Boys and Girls",
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'skyway',
  'gifts' => TRUE,
  'fields' => array('photo','body', 'goal'),
  'logo' => "http://www.renway.positiveplace.org/images/Renton_Skyway.jpg"
);

$TEMPLATE->post_content = <<<EOF
Did you know that thousands of kids, aged 6-18, are living at the poverty level in South Seattle? Many don't have access to things we take for granted, tools that could help them rise above their circumstances.

Luckily, there's an organization that offers those tools: The Boys & Girls Club of Renton/Skyway. Their programs help kids learn, stay motivated, and have healthy emotional connections so they can become productive, responsible, and caring citizens. And it's working! Over 2,000 vulnerable kids are reached every year.

I support the Boys & Girls Club of Renton/Skyway because I know that making a difference for even one child will mean less crime, less addiction, less abuse for him, his community, his state, his country, and our world. All kids deserve to have a fair chance in life-please join me to help the most vulnerable in Washington State have one!
EOF;

$TEMPLATE->gifts_content = <<<EOF
<h2 class="section-header heading clearfix">Help empower the children of Renton and Skyway!</h2>
<p>Youth, ages 6-18, living in Renton and Skyway in South Seattle continue to face a cycle of generational poverty.  The Renton/Skyway Boys &amp; Girls Club empowers these youth with ongoing support, education, and important skills.</p>
EOF;

include("default-v1.php");
