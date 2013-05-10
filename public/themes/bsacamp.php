<?

global $TEMPLATE;
$TEMPLATE = (object)array(
  'title' => 'Boy Scouts of America: Camp Parsons',
  'please' => FALSE,
  'post_title' => "Give Back to Camp Parsons: Help Build A New Dining Hall!",
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'bsacamp',
  'goal' => 50,
  'fields' => array('photo', 'body', 'goal'),
  'required_fields' => array('body', 'goal'),
  'comments' => "Tell [name] why <b>you love</b> BSA's cause!",
  'about_promo' => FALSE
);

$TEMPLATE->about = <<<EOF
<h2 class="section-header heading clearfix" style="font-size: 15pt; padding: 0 75px; margin-top: 10px; margin-bottom: 30px;"><img class="right" style="margin-left: 15px;" src="http://dev2.seeyourimpact.com/files/2012/05/caa-big.png" alt="" width="265" height="77" />Coach Across America believes that sports can help end poverty! <a style="color: orange; display: block; text-decoration: underline; font-size: 15px; margin-top: 5px; margin-left: 12px;" href="http://caa.dev2.seeyourimpact.com/">learn more</a></h2>
<div class="right big-pic bordered-photo clipped" style="margin-left: 20px;"><img src="http://dev2.seeyourimpact.com/files/2012/05/coach-andy.jpg" alt="" width="240" height="180" /></div>
<h2><span style="color: #f47c20;">Give a gift</span></h2>
$120 covers the cost of training a coach, but even a gift of $10 towards that goal will make a difference for a life, a community, and our future. 100% of your donation will go directly to CAA, and you'll get a story about the difference you make!
<h2 class="campaign-title heading"></h2>
<a class="button orange-button medium-button" style="width: 120px;" href="https://dev2.seeyourimpact.com/cart/?item=439">Give $10</a> <a class="button orange-button medium-button" style="width: 120px;" href="https://dev2.seeyourimpact.com/cart/?item=438">Give $120</a> <a class="button orange-button medium-button" style="width: 120px;" href="https://dev2.seeyourimpact.com/cart/?item=50&amp;amount=50">Give other</a>
<div style="clear: both; padding-top: 10px;">[campaignstories count="4"]</div>
EOF;

$TEMPLATE->banner = <<<EOF
<div style="width:990px; height: 120px; background: url(/themes/placeholder_990x120.png) no-repeat 0 0"></div>
EOF;

$TEMPLATE->start_banner = <<<EOF
<div style="width:690px; height: 120px; background: url(/themes/placeholder_690x120.png) no-repeat 0 0; margin: -15px -35px 20px !important;"></div>
EOF;

$TEMPLATE->post_content = <<<EOF
Camp Parsons holds a special place for Eagle Scouts of all ages, whose summers there offered times of camaraderie, life-changing experiences, and treasured memories.  With upwards of 5,000 scouts now attending each summer, Camp Parsons urgently needs a new dining hall facility to keep up with its popularity. 

Please give back to Camp Parsons by making a donation to support construction of the new dining hall.  Your gift will make a difference for the camp and a whole new generation of Eagle Scouts--a “merit badge” of giving back that you can be proud of.
EOF;

include("default.php");
