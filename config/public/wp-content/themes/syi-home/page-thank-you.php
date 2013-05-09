<?
function show_thankyou_sidebar() {
  echo '<div class="sidebar-panel charity-panel">';
  draw_promo_content("pay-sidebar", 'h2', true);
  echo '</div>';
}

global $cartID;
global $current_user;
global $bp;
  
$cartID = decrypt_cart($_REQUEST['cid']);
if ($cartID == 0)
  wp_die('No cart');
else { //force the new thank you page;
  $thankyou_url = get_thankyou_url($cartID);
  if(strpos($thankyou_url,'thank-you')===false) {
    wp_redirect($thankyou_url);
    die();  
  } 
//  die(get_thankyou_url($cartID));
}


remove_action('widgets_init', 'syi_widgets_init');
remove_action('wp_footer', 'syi_widgets_fb_init');
remove_action('syi_pagetop', 'draw_the_crumbs', 0);
add_action('get_sidebar', 'show_thankyou_sidebar');

$cart = new SYICart($cartID);
$payment = $cart->getPayment();
switch ($cart->status) {
  case 'paid': // OK
    dc($cartID,"User gets to thank you page");
    break;
  default: // TODO: some error happened; cart is not checked out
    dc($cartID,"ERROR: cart is not paid but gets to thank you ");
/* Steve: commenting until explained
    wp_redirect( get_site_url(1, "/cart/?cart=$cartID") );
    exit();
*/
    break;
}
process_publish_cart($cartID);

if( is_user_logged_in() ) {
  if($current_user->ID == get_cart_user($cartID)) {
    wp_redirect('/members/'.$current_user->user_login.'/?cid='.urlencode($_REQUEST['cid']));    	  
	exit();
  }
}


get_header();
ga_track_donation($current_user);

$msg = "I just donated. In about two weeks, I'll meet the life I changed!";

////////////////////////////////////////////////////////////////////////////////
    
the_post();

?>
<article class="type-page">
<div style="width:220px; font-size:13px; margin-top: 20px; margin-right: -50px;" class="right sidebar">
  <img class="pic" style="border: 1px solid #ccc; display:block; margin:0px 15px 20px 10px;" src="/wp-content/images/thank_you.jpg" alt="" width="196" />
  <div class="widget promo-widget"><div class="interior">
    <? draw_promo_content('thanks-matching', 'h3'); ?>
  </div></div>
</div>
<div style="width:420px;" class="left">
<h1 style="text-transform: uppercase;"><? the_title(); ?></h1>
<? the_content(); ?>
<? draw_promo_content('thanks-share', 'h3'); ?>

<form action="<?= add_query_arg('fb_publish',1)?>" method="POST">
<input type="hidden" name="resend" value="1" />
<section style="margin-top:20px;">
  <?
  $url = urlencode(site_url());
  $msg = urlencode("$msg " . site_url());
  $img = '/wp-content/templates/facebook.png';
  if (!display_fb_publish_button($cartID, $img)) {
    ?><a class="share-button" href="http://www.facebook.com/sharer.php?t=<?=$msg?>&u=<?=$url?>" title="Share this on Facebook"><img src="<?=$img?>" alt="Share on Facebook"/></a><?
  }
  ?>
  <a class="share-button" href="http://twitter.com/home/?status=<?=$msg?>" title="Share this on Twitter"><img src="/wp-content/themes/syi/images/SocialMediaBookmarkIcon/buttons/twitter.png" alt="Share on Twitter"/></a>
</section>
<? display_fb_publish_options($cartID); ?>
</form>
<div style="margin-top: 40px; font-size:10pt;">
<?
$user_id = get_cart_user($cartID);
$user = get_userdata($user_id);
$msg = '<p>Your contact information is:</p>';
if (!empty($user)) {
  if (!get_user_meta($user_id, 'no_thanks_email', true))
    $msg = '<p>We\'ve sent a thank-you e-mail to:</p>';
} else if (is_user_logged_in()) { 
  $user = $bp->loggedin_user;
}
if (!empty($user)) {
  echo $msg;

  $name = "$user->first_name $user->last_name";
  $email = $user->user_email;
  ?>
  <p style="padding-left:30px;"><b><?= esc_html($name)?> (<?= esc_html($email) ?>)</b></p>
  <? if (is_user_logged_in()) { ?>
  <p>If this isn't the best way to reach you, please <a href="<?= get_settings_url() ?>"><u>update your account</u></a> with new contact information.</p>
  <? } else { ?>
  <p>If this isn't the best way to reach you, please <a href="<?= wp_login_url() ?>"><u>create a member profile</u></a> to update your contact information.</p>
  <? }
}

 ?>
<p style="margin-top:20px; color:#444;">Questions or comments about your donation?  We're always available at <a href="mailto:contact@seeyourimpact.org"><b>contact@seeyourimpact.org</b></a>!</p>
</div>
</div>
</article>

<? if (is_live_site()) { 
  $total_amt = get_cart_total($cartID);
  $tip_amt = get_tip($cartID);
?>
  <iframe src="http://seeyourimpact.go2jump.org/SL1?amount=<?=$total_amt?>" scrolling="no" frameborder="0" width="1" height="1"></iframe>
  <iframe src="http://seeyourimpact.go2jump.org/GL5?amount=<?=$tip_amt?>" scrolling="no" frameborder="0" width="1" height="1"></iframe>
<? } ?>

<? get_footer(); 
/*
function get_settings_url() {
  global $bp;
  return $bp->loggedin_user->domain . "profile/settings";
}
*/
?>
