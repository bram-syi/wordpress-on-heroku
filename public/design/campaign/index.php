<? 

require_once('../../wp-load.php');
require_once(ABSPATH . '/wp-content/themes/syi/functions.php');

nocache_headers();
define('WP_ADMIN', true);
define('DONOTCACHEPAGE',1);

wp_enqueue_style('campaign.css', "/design/campaign/campaign.css", null, $ver);

?>

<html><head>
<title>SeeYourImpact campaign page</title>
<script type='text/javascript' src='//cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js'></script>
<link type="text/css" rel="stylesheet" href="/wp-content/themes/syi/jquery.qtip.css" />
<? wp_head(); ?>
<? draw_styles(); ?>

</head><body class="user-style-charity">

<div class="top-bar"><div class="login-bar"></div></div>
<header id="header" class="header page-content">
  <div class="login-bar" style="font-size: 15px; padding:3px 10px 3px 10px;">
    <div class="left">
      <b style="color:#ccc">Thanks for visiting</b> -
      <span style="color:#fc8;">Become a fan!</span>
    </div>
    <div class="right">
      <select style="font-size:8pt;">
        <option value="">members</option>
        <option value="kenny">kenny</option>
        <option value="zulily">zulily</option>
        <option value="charity">charity</option>
      </select>
    </div>
  </div>
<? function draw_login_bar() { }
include_once( ABSPATH . "/wp-content/themes/syi/default-header.php" ); ?>
</header>

<div id="appeal" class="appeal page-content">
  <div class="only-for for-kenny">
    <div class="msg">
      <h1>Join me in saving lives. Then join me in concert.</h1>
      <p style="margin-top:10px;">Hi, I'm Kenny G.  You might know me from such songs as the one that has a sax in it, and the other one that has a sax in it.</p> 
      <p style="margin-left: 80px;">Please help me fulfill my mission to bring my music to those in need around the world.</p>
      <p style="margin-left: 80px;">Thanks, and stay saxy!</p>
    </div>
    <img src="signature.jpg" class="only-for for-kenny signature" />
  </div>
  <div class="only-for for-zulily">
    <img class="left pic" src="zulily-logo.gif" />
    <div class="msg">
      <h1>Please help us make this a better world for children everywhere.</h1>
    </div>
  </div>
  <div class="only-for for-charity">
    <div class="msg">
      <h1>Provide education, empowerment and employment for girls in rural India</h1>
    </div>
  </div>
</div>

<div class="page page-content">
<?

global $event_id;

$limit = 16;
$event_id = 3148;

draw_stat_section();
draw_gift_section();
draw_stories_section(get_stories_by_event($event_id, $limit, 'ds.post_modified DESC'));

function draw_stat_section() {
  global $event_id, $wpdb;

  $stats = $wpdb->get_row($wpdb->prepare(
    "SELECT COUNT(dg.ID) as lives,COUNT(DISTINCT(d.donorID)) as donors,SUM(dg.amount) as total
    from donationGifts dg
    JOIN donation d ON d.donationID=dg.donationID
    WHERE d.test=0 AND event_id=%d", $event_id));

  $goal = intval(get_post_meta($event_id, 'syi_lives_goal', true));

?>
  <div class="stats">
     <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
     <div class="right stat share only-for for-zulily for-member" style="width:350px; float:right;">
       <fb:like href="/" show_faces="false" width="400" font="trebuchet ms"></fb:like>
     </div>
     <div class="only-for for-kenny right stat share">
       <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
       <div class="right">
       <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
       </div>
       <div class="right">
       <fb:like href="/" show_faces="false" width="100" layout="button_count" font="trebuchet ms"></fb:like>
       </div>
     </div>
    <div class="stat"><b><?= $stats->lives ?></b><label>lives<br>changed</label></div>
    <div class="meter"><span style="width: 73%"></span></div>
    <div class="stat"><b>$<?= $stats->total ?></b><label>from <?= $stats->donors ?><br>donors</label></div>
  </div>
<?
}

function draw_gift_section() {
  global $event_id;
?>
<h2>Give a gift.  In about 2 weeks, you'll see who you helped and how.</h2>
<div id="give" class="give gallery section collapsed-section">
  <div class="left gallery-left">
    <div class="to-share">
      <p class="only-for for-charity">Illiteracy among girls in India is widespread. Of the children who are not enrolled in school, <b>70% are girls</b>.</p>
      <p class="only-for for-charity">Pardada Pardadi provides <b>free education for its students</b>, including vocational training, textbooks, meals and supplies.</p>
      <p class="only-for for-charity">Starting at an enrollment of 45, PPES now educates and provides job training and employment for 996 girls.</p>
      <p class="only-for for-zulily">Zulily isn't just about deals - it's about providing for moms, babies, and kids everywhere.</p>
      <p class="perc"><b>100% of your donation</b> goes to the gifts you choose.</p>
      <p class="only-for for-kenny">With each gift, you'll be entered to win tickets to your choice of my upcoming concerts.</p>
      <p class="only-for for-kenny">Here's something else that's interesting.</p>
    </div>
    <div class="browse-more see-more-gifts">
       <div class="center butt">see more...</div>
    </div>
    <div class="more-options">
      <a href="#"><b>choose by need</b> &raquo;<div class="rarr"></div></a>
      <a href="#"><b>by age / gender</b> &raquo;<div class="rarr"></div></a>
      <a href="#"><b>or by location</b> &raquo;<div class="rarr"></div></a>
      <div class="box">
        <h3>Can't decide? No problem.</h3>
        <p><strong></strong>Donate any amount, and we'll invest it in causes with the greatest need.</p>
        <div style="text-align: center;"><form action="https://seeyourimpact.org/cart/" method="post" style="font-size:14pt;">
  <input type="hidden" name="item" value="50">
  <label for="damt"><b>Give $</b></label><b><input type="text" name="amount" size="5" maxlength="5" style="width:60px;padding:1px; font-size:12pt;" value="" id="damt"></b>
  <input type="submit" class="button medium-button orange-button" name="submit" value="Donate" id="damt">
</form>
        </div>
      </div>
    </div>
  </div>
  <div class="right gallery-right give-browse" style="height:220px;">
<?
  $p = get_posts('post_type=page&name=give');

  $args = array(
    'header' => false,
    'controls' => true,
    'v2' => true,
    'empty_html' => 'Loading...',
    'event_id' => $event_id
  );
  $args['regions'] = 'americas';
  gift_browser_widget($args);
?>
  </div>
  <div class="right gallery-right">
    <div class="browse-more"><div class="center" style="width:365px;"><u class="left">browse the full list</u><div class="left" style="margin-left:5px;"> of over <b>100 ways</b> to make a difference.</div></div></div>
  </div>
</div>

<?
}

function draw_stories_section($stories) {
?>

<h2>See the impact of your donations on the actual recipient.</h2>
<div id="stories" class="stories gallery section collapsed-section">
  <div class="left gallery-left">

<? 
$i = 0;
$the_story = $stories[0];
foreach ($stories as $story) { 
  if ($i == 2) {
    ?>
    <a class="donation video" href="http://www.youtube.com/watch?v=GaoLU6zKaws" title="Sexy Saxman Serenade"><img class="recip" src="http://img.youtube.com/vi/GaoLU6zKaws/0.jpg" width="110" height="90"><div class="play"></div></a>
    <?
  }
  if ($_REQUEST['gal'] == $story->ref)
    $the_story = $story;
  cmt_dump($story);
  ?><a class="donation" href="<?= add_query_arg('gal', $story->ref); ?>" title="<?=esc_attr($story->post_title)?>"><?
  $b_id = $story->blog_id;
  if ($i < 4) { 
    ?>
    <? draw_thumbnail($story->blog_id, $story->post_thumb, 110,90, false, null, 'img class="recip"') ?>
    <!-- <img src="http://dev1.seeyourimpact.com/files/avatars/14/ecbe22277a16c8125c4693ab604af50f-bpthumb.jpg" class="donor avatar user-14-avatar" width="40" height="40"> -->
    <?
  } else { 
    draw_thumbnail($story->blog_id, $story->post_thumb, 48,40, false, null, 'img class="recip smaller"') ;
  }
  $i++;
  ?></a><?
}

?>

  </div>
  <div class="right gallery-right"><div class="scrollable">
    <div class="items">
      <? $i = 0; foreach ($stories as $story) { ?>
         <? if ($i == 2) { ?>
           <div class="impact">
             <div class="impact-photo shadowed">
               <iframe title="YouTube video player" width="400" height="255" src="http://www.youtube.com/embed/GaoLU6zKaws?rel=0&amp;hd=1" frameborder="0" allowfullscreen></iframe>
             </div>
             <div class="impact-story">
                <div class="gave">
    <img src="http://dev1.seeyourimpact.com/files/avatars/14/ecbe22277a16c8125c4693ab604af50f-bpthumb.jpg" class="donor avatar user-14-avatar" width="40" height="40">
    <p><b>Kenny</b> posted this video.</p>
                </div>
               <h3>Playing Sax is Sexy</h3>
               <p>For more Sergio Flores, check out his channel at <u><a target="_new" href="www.youtube.com/sergiofloresvideos">sexysaxmansaxagrams.com</a></u></p>
             </div>
           </div>
         <? } ?>
         <? draw_impact($story); $i++; ?>
      <? } ?>
    </div></div>
    <div style="width:400px;">
      <div class="navi"></div>
    </div>
  </div>
</div>
<?
}

?>

<div id="social" class="social gallery section">
  <div class="left gallery-left">
    <h2>Together, we'll make a difference.</h2>
    <? syi_progress_widget(array( 'event_id' => $event_id, 'limit' => 20, 'show_avatars' => true )); ?>
  </div>
  <div class="right gallery-right">
    <h2>Tell the world what you think.</h2>
    <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=123397401011758&amp;xfbml=1"></script><fb:comments href="example.com" num_posts="2" width="400"></fb:comments>
  </div>
</div>

</div></body>

<script>
$(function() { 
  $(".give.collapsed-section").live('click', function() {
    $("#gift-paging").fadeIn();
    $("#gifts").animate({ top: 0, marginBottom: -10 });

    var r = $(this).find(".give-browse");
    var sized = r.find(".sized");
    r.animate({
      height: sized.data('height')
    }, 1000, function() {
      r.css('height', null);
    });

    $(this).find(".browse-more").fadeOut(500);
    $(this).find(".more-options").fadeIn(2000);
    $(this).removeClass("collapsed-section");
  });

  if ($.fn.touchScroll)
    $(".scrollable").touchScroll();

  $("#gift-details").bind('changed', function() {
    var el = $("#gift-details .big-pic:not(.clipped)").addClass('bordered-photo');
    clip(el);
  });

  $(".gallery a[title!='']").qtip({
    position: {
      my: 'bottom center',
      at: 'top center',
      adjust: { y: 12 }
    }, style: {
      classes: 'ui-tooltip-shadow ui-tooltip-jtools'
    }
  });
  $(".to-share p").qtip({
    content: { text: 'Click to share this on Twitter or Facebook' },
    position: {
      my: 'bottom center',
      at: 'top center',
      adjust: { y: 12 }
    }, style: {
      classes: 'ui-tooltip-shadow ui-tooltip-jtools'
    }
  });

  $(".stories .impact").live('hover', function(ev) {
    if (ev.type == 'mouseover') {
      var ph = $(this).find('.impact-photo');
      moveit(ph);
    } else {
    }
  });
  function moveit(ph) {
    $(ph).find("img").stop().animate({
      width: 400, height: 400 /*,
      left: -50, top: -25 */
    }, 5000);
  }

/*
  var scr = $(".stories .right").scrollable({
    circular: true
  });
  var nav = scr.navigator();
  var stories = scr.data("scrollable");
  $(".stories .left a").live('click', function() {
    var i = $(this).index();
    var dist = Math.abs(i - stories.getIndex());
    stories.seekTo(i, dist * 200 + (dist < 3 ? 300 : 0));
    // start animating? moveit($(".stories .impact:eq(" + i + ") .impact-photo"));
    return false;
  });
*/

  function skin_for(n) {
    var c = [];
    $("#header select option").each(function() {
      c.push('user-style-' + $(this).val());
    });
    $("#header select").val(n);
    $("body").removeClass(c.join(' '));
    $("body").addClass('user-style-' + n);
  }
  skin_for('charity');

  $("header select").live('change', function() {
    skin_for($(this).val());
  });
});

</script>
<script src="jquery.qtip.pack.js"></script>
<script src="jyoutube.js"></script>

</html>

<?

function draw_styles() {
  
?>
<style>
body.user-style-zulily {
  background-image: url(https://www.zulily.com/skin/frontend/zulily/default/images/zulily-bkgd.gif);
}
body.user-style-zulily {
  background-position: left 40px;
  x-background-attachment: fixed;
  background-color: #C0DEED;
  color: #333;
}
body.user-style-zulily .page {
  -webkit-border-radius: 10px;
  -moz-border-radius: 10px;
  border-radius: 10px;
}
body.user-style-zulily #header {
  xwidth: 1050px; 
}
body.user-style-zulily #header .tab-bar {
  border-radius: 0 0 10px 10px;
}
body.user-style-zulily .top-bar {
  display: block;
  width: 100%;
  background: #22404a;
}
body.user-style-zulily .appeal {
  background: transparent;
  text-shadow: black 1px 1px 1px;
  color: white;
  height: 140px;
}
body.user-style-zulily .appeal .msg {
  width: 480px;
  margin: 30px;
}
body.user-style-zulily .stats {
  background: rgba(233, 246, 198, 0.9);
  border-radius: 10px 10px 0 0;
  -moz-border-radius: 10px 10px 0 0;
}

body.user-style-kenny {
  background: #282422;
  background-image: -webkit-gradient( linear, left top, right bottom, color-stop(0, #282422), color-stop(1, #181A20) );

}
body.user-style-kenny #header .tab-bar {
  border-bottom: 1px solid #68A;
}
body.user-style-kenny .appeal {
  background: black url(http://kennyg.s3.amazonaws.com/media/03/07/large.r6710t368q2n.jpg) no-repeat -115px -5px;
  height: 270px;
  color: #f0f7ff;
}
body.user-style-kenny .appeal h1 {
  color: #FEC;
}
body.user-style-kenny .stats {
  position: absolute;
  left: 370px;
  width: 585px;
  top: -65px;
  background: #8AB;

  border-radius: 10px 0 0 10px;
  -moz-border-radius: 10px 0 0 10px;
}
body.user-style-kenny .stats .share {
  position: absolute;
  top: -40px;
  left: 350px;
  width: 250px;
}
body.user-style-kenny .signature {
  position: absolute;
  left: 560px;
  top: 143px;
}

body.user-style-charity #header .tab-bar {
  border-bottom: 1px solid #68A;
}
body.user-style-charity .appeal {
  height: 259px;
  background: url(pardada.png) no-repeat 0 0;
}
body.user-style-charity .appeal .msg {
  width: 720px;
  padding-right: 10px;
}
body.user-style-charity .appeal h1 {
  font-size: 22pt;
  color: white;
  text-shadow: black 1px 1px 1px;
}
body.user-style-charity .stats,
body.user-style-charity .more-options a,
body.user-style-charity .give .perc {
  display: none;
}

.only-for { display: none !important; }
body.user-style-zulily .for-zulily {
  display: block !important;
}
body.user-style-kenny .for-kenny {
  display: block !important;
}
body.user-style-charity .for-charity {
  display: block !important;
}


.page h2 {
  font-size: 18pt;
  padding: 18px 0 6px 16px;
  font-weight: bold; 
  color: #2B4E64;
}

.stats {
  font-weight: bold;
  padding: 6px 10px 4px 20px;
  background: #f8f8f8;
  color: #234;
}
.stats h3 {
  color: #444;
  background: white;
  border: 1px solid #ddd;
  padding: 8px 10px;
  margin-bottom: -20px;
  position: relative;
  top: -12px;
  left: -2px;
  border-radius: 10px;
  box-shadow: rgba(0,0,0,0.3) 2px 2px 4px;
  float: left;
  width: 265px;
}
.stats .stat {
  display: block;
  float: left;
  font-size: 12pt;
  float: left;
  width: 130px;
  padding: 5px 0 5px 20px;
}
.stats .stat label {
  font-size: 8pt;
}
.stats .stat b {
  font-size: 24pt;
  margin: -5px 5px -5px -5px;
  float: left;
}
.stats .meter {
  width: 290px;
  float: left;
  margin: 5px 0 0 -10px;
}
.meter {
  height: 24px;
  position: relative;
  background: #eee;
  border: 1px solid #ccc;
  -moz-border-radius: 8px;
  -webkit-border-radius: 8px;
  border-radius: 8px;
  padding: 1px;
  -webkit-box-shadow: inset 0 -1px 1px rgba(255, 255, 255, 0.3);
  -moz-box-shadow: inset 0 -1px 1px rgba(255, 255, 255, 0.3);
  box-shadow: inset 0 -1px 1px rgba(255, 255, 255, 0.3);
}
.meter > span {
  display: block;
  height: 100%;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  background-color: #2BC253;
  background-image: -webkit-gradient( linear, left bottom, left top, color-stop(0, #2BC253), color-stop(1, #54F054) );
  background-image: -moz-linear-gradient( center bottom, #2BC253 37%, #54F054 69% );
  -webkit-box-shadow: inset 0 2px 9px rgba(255, 255, 255, 0.3), inset 0 -2px 6px rgba(0, 0, 0, 0.4);
  -moz-box-shadow: inset 0 2px 9px rgba(255, 255, 255, 0.3), inset 0 -2px 6px rgba(0, 0, 0, 0.4);
  box-shadow: inset 0 2px 9px rgba(255, 255, 255, 0.3), inset 0 -2px 6px rgba(0, 0, 0, 0.4);
  position: relative;
  overflow: hidden;
}
.meter > span::after, .animate > span > span {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-image: -webkit-gradient(linear, 0 0, 100% 100%, color-stop(.25, rgba(255, 255, 255, .2)), color-stop(.25, transparent), color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .2)), color-stop(.75, rgba(255, 255, 255, .2)), color-stop(.75, transparent), to(transparent) );
  background-image: -moz-linear-gradient( -45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent );
  z-index: 1;
  -webkit-background-size: 50px 50px;
  -moz-background-size: 50px 50px;
  -webkit-animation: move 2s linear infinite;
  -webkit-border-top-right-radius: 8px;
  -webkit-border-bottom-right-radius: 8px;
  -moz-border-radius-topright: 8px;
  -moz-border-radius-bottomright: 8px;
  border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
  -webkit-border-top-left-radius: 20px;
  -webkit-border-bottom-left-radius: 20px;
  -moz-border-radius-topleft: 20px;
  -moz-border-radius-bottomleft: 20px;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
  overflow: hidden;
}


.section { 
  overflow: hidden; 
  position: relative;
}
.collapsed-section {
  border-bottom: 1px dashed #E0E7F0;
  padding-bottom: 5px;
}
.section-end {
  display: block;
  float: right;
  padding: 2px 10px;
  position: absolute;
  bottom: 3px;
  right: 10px;
  background: #eee;
  font-size: 8pt;
  cursor: pointer;
}

.gallery { overflow: hidden; }
.gallery .gallery-left { 
  width: 250px;
  position: absolute;
  padding: 10px 10px 10px 20px;
}

.gallery .gallery-right { 
  width: 700px;
  padding: 15px 0;
  position: relative;
}
.gallery .gallery-right .shadowed {
  margin: 0 20px 20px 0;
  float: left;
}

.impact { float: left; margin-right: 10px; }
.impact .impact-photo {
  border: 1px solid black;
  overflow: hidden;
  width: 400px;
}
.impact .impact-photo img {
  margin: 1px;
  left: 0px;
  top: 0px;
}

.impact h3 { 
  font-size: 14pt;
  color: #2B4E64;
}
.impact p {
  margin: 8px 0;
}
.impact .gave { float: left; width: 260px; margin-bottom: 8px; }
.impact .gave img { float: left; border: 1px solid black; }
.impact .gave p { margin: 0 0 0 50px; }

.stories .scrollable { height: 410px; }
.stories .impact-story { 
  margin-left: 420px; 
  width: 270px;
}


.gallery .gallery-left .donation {
  display: block;
  float: left;
  position: relative;
}
.gallery .gallery-left .recip { 
  border: 1px solid #888; 
  width: 110px; height: 90px;
  margin: 6px;
  background: #eee;
  opacity: 0.8; -webkit-transition: opacity 0.3s linear;
}
.gallery .gallery-left .recip:hover { opacity: 1; }
.gallery .gallery-left .smaller { width: 48px; height: 40px; }


.give { 
  padding-right: 10px;
}
.give .gift-browser-panel {
  width: 710px;
}
.give .gift-details {
  padding-left: 0;
}

.give .gallery-left {
  height: 480px;
}
.give .gallery-right {
  padding: 0px;
  width: 710px;
  xborder-left: 1px solid #E0E7F0 inset;
  clear: right;
  background: white;
}
.give .gallery-right .gifts {
  left: -10px;
  top: -50px;
  margin-bottom: -65px;
}
.give .gallery-right .gift-browser-menu {
  display: none;
}
#gift-browser {
 height: 100px;
}
#gift-details {
  background: #ffe;
  border: 1px solid #ddc;
}
.bordered-photo { background: white;
  padding: 3px;
  border: 1px solid #BFBFBF;
  background-color: white;
  -webkit-box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  -moz-box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  -webkit-transition: -webkit-transform 0.5s ease-in;
  -webkit-transition: -webkit-box-shadow 0.5s ease-in;
}


.give .left p {
  margin: -10px 0 12px 0;
}
.give .to-share p:hover {
  border: 2px dashed #e7e7e7;
  border-radius: 12px;
  cursor: pointer;
}
.give .to-share p {
  border: 2px solid white;
  padding: 8px;
  color: #444;
}

.gift-page .page-title {
  font-size: 20pt;
  color: #2B4E64;
  margin-top: 14px;
}

.progress-widget h3 {
  display: none;
}

.social .gallery-left, .social .gallery-right {
  width: 45%;
  padding: 10px 40px;
}
.social .gallery-left .avatar {
  float: left;
  width: 40px; height: 40px; border: 1px solid #444;
  margin-right: 6px;
}
.social .gallery-left td {
  font-size: 12pt;
}
.social h2 {
  padding: 8px 0 15px 0;
}

/* position and dimensions of the navigator */
.navi {
  width: 240px;
  margin: 0 auto;
  height:20px;
  margin-top: 8px;
  text-align: center;
}
.navi a {
  width:8px;
  height:8px;
  float:left;
  margin:3px;
  background:url(navigator.png) 0 0 no-repeat;
  display:block;
  font-size:1px;
}
.navi a:hover { background-position:0 -8px; }
.navi a.active { background-position:0 -16px; }

.shadowed {
  position: relative;
  background: #ffffff; /* old browsers */
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;
  -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 60px rgba(0, 0, 0, 0.1) inset;
  -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.06) inset;
}

  .shadowed:after { 
z-index: -1; 
position: absolute; 
background: transparent; 
width: 70%; 
height: 55%; 
content: ''; 
right: 10px; 
bottom: 10px; 
transform: skew(7deg) rotate(4deg);
-webkit-transform: skew(7deg) rotate(4deg);
-moz-transform: skew(7deg) rotate(4deg);
box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); 
-webkit-box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); 
-moz-box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); }

.shadowed:before { 
z-index: -2; 
position: absolute; 
background: transparent; 
width: 70%; 
height: 55%; 
content: ''; 
left: 10px; 
bottom: 10px; 
transform: skew(-7deg) rotate(-4deg); 
-webkit-transform: skew(-7deg) rotate(-4deg); 
-moz-transform: skew(-7deg) rotate(-4deg); 
box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); 
-webkit-box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); 
-moz-box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); }

.overflow { overflow: visible; }
</style>
<?
}

function draw_impact($the_story) {
?><div class="impact">
    <div class="impact-photo shadowed">
      <? draw_thumbnail($the_story->blog_id, $the_story->post_image, 500,500); ?></div>

  <div class="impact-story"><div class="gave">
    <img src="http://dev1.seeyourimpact.com/files/avatars/14/ecbe22277a16c8125c4693ab604af50f-bpthumb.jpg" class="donor avatar user-14-avatar" width="40" height="40">
    <p><b>Steve</b> gave <b>Asunilo-da</b> a big field of crops.</p>
  </div>
  <h3><?= xml_entities($the_story->post_title) ?></h3>
  <p><?= xml_entities($the_story->post_excerpt) ?></p>
  <p>[Share buttons]</p>
</div></div><?
}


