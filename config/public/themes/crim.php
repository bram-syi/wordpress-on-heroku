<?

global $TEMPLATE;
$TEMPLATE = (object)array(
  'type' => "Crim Fundraiser",
  'post_title' => "Crim Fundraiser",
  'title' => "Exercise your heart!  Donate a Crim scholarship and see the life you change.",
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'crim',
  'goal' => '140',
  'fields' => array('photo', 'body', 'goal'),
  'required_fields' => array('goal'),
  'comments' => "Tell [name] why <b>you love</b> Crim's cause!"
);

$TEMPLATE->about = <<<EOF
<h2 class="section-header heading clearfix" style="font-size: 15pt; padding: 0 75px; margin-top: -18px; margin-bottom: 30px;"><img class="right" style="margin-top: -10px; height: 100px;" src="http://crim.seeyourimpact.org/files/2012/03/12logo.jpg" alt="" width="207" height="100" />
The Crim Fitness Foundation
inspires people to be healthy!</h2>
<div class="right big-pic bordered-photo clipped" style="margin-left: 30px;"><img src="http://crim.seeyourimpact.org/files/2012/03/gift_580.jpg" alt="" width="240" height="180" /></div>
At the Crim Fitness Foundation, our healthy outlook has changed the lives of thousands in Flint, MI. Through quality physical activity programs, nutrition and mindfulness education, and active living solutions, we help fight childhood obesity, improve our community’s health, and increase access to clean, safe parks and trails.
<div style="clear: both; padding-top: 10px;">[campaignstories count="4"]</div>
EOF;

$TEMPLATE->banner = <<<EOF
<div style="width:990px; height: 193px; background: url(http://seeyourimpact.org/themes/crim/runners.jpg) no-repeat 0 0"></div>
EOF;

$TEMPLATE->start_banner = <<<EOF
<div style="width:980px; height: 193px; background: url(http://seeyourimpact.org/themes/crim/runners.jpg) no-repeat 0 0; border: 1px solid #ccc; margin: 4px;"></div>
EOF;

$TEMPLATE->post_content = "I'm a fan of the Crim Foundation because it inspires our community to be healthier and happier. Their annual Festival of Races is an amazing event that encourages people to get in shape and have fun through friendly competition. Crim also provides a 15-week adult training program leading up to the festival that helps participants get in shape, stay motivated, and connect with other aspiring athletes.

We can sponsor someone in our community by donating a scholarship that covers the cost of the training program, and gives him or her the leadership and support he or she needs. Please join me to spread the health!";

include("default.php");












