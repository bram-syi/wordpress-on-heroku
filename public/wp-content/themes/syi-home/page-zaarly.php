<?php

include_once(ABSPATH . 'a/api/api.php');
include_once(APIPATH . '/donation.php');

// add_action( 'all', create_function( '', 'var_dump( current_filter() );' ) );

global $NO_SIDEBAR, $NO_PADDING, $header_file, $post, $GIFTS_EVENT, $GIFTS_LOC, $typekit;
$NO_SIDEBAR = true;
$NO_PADDING = true;
$header_file = "branded";

global $fr, $post;
$fr = get_campaign_stats($post->ID);

$typekit = "mgz5cxu";

wp_enqueue_style("zaarly", SITE_URL . "/themes/zaarly/css/style.css");

remove_action('syi_pagetop', 'draw_the_crumbs', 0);
add_action('modify_team_context', 'zaarly_context');
add_action('branded_header', 'draw_zaarly_header');

get_header();

if (have_posts()) the_post();

?>

    	<div class="container cf">

	    	<div class="main-contents cf">

		    	<div class="main-col left">

			    	<? draw_zaarly_progress(); ?>

			    	<section class="mid cf">

				    	<div class="photo-col left">

					    	<img src="<?=SITE_URL?>/themes/zaarly/images/harika-img.png" alt="harika-img" width="154" height="155" />

				    	</div>

				    	<div class="brief left">

					    	<img src="<?=SITE_URL?>/themes/zaarly/images/harika-txt.png" />

<? the_content(); ?>

<? if (current_user_can("editor")) { ?>
  <div class="admin-actions"><? edit_post_link( 'edit this page', '<span class="edit-link">', '</span>'); ?></div>
<? } ?>

				    	</div>

			    	</section><!-- // section mid-->

			    	<section class="bottom cf">

<?
draw_zaarly_listing('janegphotography', 'jane', 'Jane Gershovich', 'photographer', 'I capture memories of your happiest moments.');
draw_zaarly_listing('FoodWiseNutrition', 'autumn', 'Autumn Hoverter', 'nutritionist', 'I help you find happiness and health in your diet');
draw_zaarly_listing('dodorific', 'jigna', 'Jigna Patel', 'baby clothing design', 'I outfit your little ones in the cutest organic fashions');
draw_zaarly_listing('comeshakeitoff', 'sheree', 'Sheree James', 'dance fitness instructor', 'I empower women through dance and fitness');
draw_zaarly_listing('rustysfamous', 'rusty', 'Rusty Federman', 'famous cheesecake', 'I make gourmet cheesecakes inspired by my mother');
// REMOVED   draw_zaarly_listing('kirchofffitness', 'chris', 'Chris Kirchoff', 'personal trainer', 'I motivate you to reach goals with fitness bootcamps');
draw_zaarly_listing('timetoswim', 'emily', 'Emily Weber', 'swim instructor', 'I coach and teach you and your kids the art of swimming');
draw_zaarly_listing('adina', 'adina', 'Adina DeSantis', 'health &amp; nutrition', 'I build solid bodies through holistic health');
// REMOVED    draw_zaarly_listing('corey', 'coreyst', 'Corey St. John', 'furniture maker', 'I furnish your home with pieces hand-made from reclaimed wood');
draw_zaarly_listing('ultimateresults', 'corey', 'Corey Galusha', 'personal trainer', 'I train you to build strength and endurance with perfect technique');
draw_zaarly_listing('bluebird', 'bluebird', 'Tonia Hume', 'Sugar free baking', 'I bake with health in mind');
draw_zaarly_listing('coreenmarie', 'coreenmarie', 'Coreen Cobley', 'Organic beauty products', 'I make you feel beautiful inside and out');
draw_zaarly_listing('tinysuperheroes', 'tinysuperheroes', 'Robyn Rosenberger', 'Handmade capes for kids', 'I turn kids into Superheroes');
draw_zaarly_listing('theodorresaromatics', 'theodorresaromatics', 'Lisa Kin', 'Hand blended teas', 'I craft teas for every mood');
draw_zaarly_listing('reisaudio', 'reisaudio', 'Lewis Zhou', 'Custom speakers', 'I build custom wooden speakers');
draw_zaarly_listing('spotonfoods', 'spotonfoods', 'Jamie Moskowitz', 'Spot on foods', 'I make indulgent treats');
?>

			    	</section><!-- // section bottom-->

		    	</div><!-- // main-col -->

		    	<aside class="right cf">

			    	<section class="aside-list">

				    	<div class="element expander2 fast collapser">
				    		<h3>What is Zaarly?</h3>
				    		<div class="element-content if-expanded">

				    			<p>Zaarly is a local marketplace enabling people to make money doing what they love. Every Storefront Owner is a hand selected expert at their craft who believes in their local economy.
				    			</p>
				    			<p>
We believe that the best work you'll ever see comes from people who truly love what they do. So we created a marketplace for customers to find and connect with their favorite Storefront Owners who deliver delightful experiences along with their custom creations.
								</p>
							</div><!-- // element-content -->
				    	</div><!-- // element -->

				    	<div class="element expander2 fast collapser">
				    		<h3>How does it work?</h3>
				    		<div class="element-content if-expanded">

				    			<p>Click on a Zaarly Storefront Owner to explore their story and offerings. Choosing a service will direct you to Zaarly to complete your order. Place your order and the Storefront Owner will reply to work out details of your custom order. You will receive personalized service and help a global recipient at the same time.
								</p>
							</div><!-- // element-content -->
				    	</div><!-- // element -->

				    	<div class="element expander2 fast collapser">
				    		<h3>Where does my money go?</h3>
				    		<div class="element-content if-expanded">

				    			<p>Place your order and Zaarly will donate 5% to a select group of the SeeYourImpact charity network. As a donor, SeeYourImpact.org will send you information about who you helped in the process along with their story and exact gift you gave to them just by purchasing.
								</p>
							</div><!-- // element-content -->
				    	</div><!-- // element -->

				    	<div class="element expander2 fast collapser">
				    		<h3>Why should I participate?</h3>
				    		<div class="element-content if-expanded">
				    			<p>Support your local economy while helping global neighbors in need. Do two good deeds with one click. Now that’s a good deal.
								</p>
							</div><!-- // element-content -->
				    	</div><!-- // element -->


			    	</section> <!-- // list -->

			    	<section class="aside-bottom">

				    	<h3>Thanks to:</h3>

				    	<div class="aside-bottom-list">

<?
$donations = DonationApi::get(array( 
  'fr_id' => $post->ID
));

if (count($donations) == 0) {
?>
  <div class="element cf"><i>Be the first to donate!</i></div>
<?
}

// User images will be displayed as small round circles
$geom = array('w_33','h_33','c_fill','g_faces','r_max');

foreach ($donations as $d) { 
  $date = strtotime($d->date);
  if ($date !== FALSE)
    $date = date('M j', $date);

  $user_image= SITE_URL . "/wp-content/images/no-photo.jpg";
  if (isset($d->donor_data)) {
    $j = json_decode($d->donor_data);
    if ($j && (isset($j->user_image)))
      $user_image = $j->user_image;
  }

  $pic = image_src($user_image, $geom);

?>
  <div class="element cf">
    <div class="icon left" style="background-image: url(<?=$pic?>);"></div>
    <!-- <? if ($date) { ?><div class="upper left"><?= $date ?></div><? } ?> -->
    <div class="lower left"><strong><?= $d->first ?></strong> helped <strong><?= $d->partner_name ?></strong></div>
  </div>
<? } ?>

				    	</div>

			    	</section>

		    	</aside>

	    	</div><!-- // main-contents -->


    	</div><!-- // container -->

<script>
$(function() {
  var p = $('aside .aside-bottom');

  function rszpw() {
    p.css({ position: 'relative', top:'auto', height: 'auto' });
    var h = $('.main-col').height();
    var i = p.find('>.aside-bottom-list');
    var pos = p.position();
    if (pos == null)
      return;
    var h2 = p.find('>h3').height();
    var b = pos.top + i.height() + h2;
    if (b > h) {
      p.css({ position: 'absolute', top: pos.top, bottom: 0, height: 'auto', 'overflow-y':'scroll' });
    } else {
      p.css({ position: 'relative', top:'auto', height: i.height() + h2, background:'transparent', 'overflow-y':'hidden' });
    }
  }
  p.on('rszpw', rszpw).trigger('rszpw');
  $(window).on('resize', function() { p.trigger('rszpw'); });

  // Automatically handle expand and collapse clicks
  $(".expander2").live('click', function() {
    var panel = $(this).closest('.collapser');
    var ifc = panel.find('.if-collapsed');
    var ife = panel.find('.if-expanded');

    if (panel.hasClass('expanded')) {
      if (!$(this).hasClass('expand')) {
        ifc.show();
        ife.hide();
        panel.removeClass('expanded');
      }
    } else {
      if (!$(this).hasClass('collapse')) {
        ife.show();
        ifc.hide();
        panel.addClass('expanded');
      }
    }
    set_focus(panel);
    p.trigger('rszpw');
  });

});
</script>

<?

get_footer();




function zaarly_context() {
  return (object)array(
    'campaign_header' => TRUE
  );
}


function draw_zaarly_header() {
?>
  <div class="banner">
    <img src="<?=SITE_URL?>/themes/zaarly/images/banner-img.jpg" alt="Zaarly + see your impact" width="996" height="332" />
  </div>
<?
}


function draw_zaarly_progress() {
  global $fr;

  $raised = number_format($fr->raised, 0);
  if ($raised < 500)
    return;

  $goal = number_format($fr->goal, 0);
  $pct = round($fr->raised/$fr->goal, 2) * 100;
?>
  <section class="top cf">
    <p>We've raised <strong>$<?= $raised ?></strong> of our <strong>$<?= $goal ?></strong> goal!</p>
    <div class="progress-bar left">
      <? if ($pct > 0) { ?><div class="progress-bar-percentage" style="width: <?=$pct?>%;"></div><? } ?>
      <span class="value"><?= plural($fr->donors_count, 'purchase') ?></span>
    </div>
    <div class="percentage left">
      <?=$pct?>&#37;
    </div>
  </section><!-- section top -->
<?
}


function draw_zaarly_listing($url, $img, $name, $kind, $tagline) {
?>
  <a href="http://www.zaarly.com/<?= $url ?>?zti=678&campaign=syi" class="person cf">

    <div class="avatar-col left">
      <div class="avatar">
        <img src="<?=SITE_URL?>/themes/zaarly/images/avatar-<?=$img?>.png" alt="avatar-autumn" width="105" height="104" />
      </div>
    </div>
    <div class="details-col left">

      <div class="name"><?= xml_entities($name) ?></div>
      <div class="sub"><?= xml_entities($kind) ?></div>
      <div class="meta"><?= xml_entities($tagline) ?></div>

      <div class="sample-images">
        <img src="<?=SITE_URL?>/themes/zaarly/images/small-<?=$img?>-1.png" alt="illustration" />
        <img src="<?=SITE_URL?>/themes/zaarly/images/small-<?=$img?>-2.png" alt="illustration" />
      </div>

      <div class="link-button">See all my listings!</div>

    </div><!-- // details-col -->

  </a><!-- // single person wrap -->

<?
}


