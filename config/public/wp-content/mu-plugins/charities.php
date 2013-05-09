<?php
/*
Plugin Name: Charity Shortcodes
Plugin URI: http://www.seeyourimpact.org
Description: Adds shortcodes for charity posts
Version: 1.2
Author: Steve Eisner, Yosia Urip
Author URI: http://www.seeyourimpact.org
*/

include_once(ABSPATH . '/wp-includes/syi/syi-includes.php');
include_once(ABSPATH . '/a/api/partner.php');

// [charity foo="foo-value"]
function sponsor_func($atts, $content=null, $code="") {
    global $blog_id;
    global $wpdb;
    
    if (!is_single())
       return "";

    extract(shortcode_atts(array(
        'charity' => '',
        'gift' => '',
    ), $atts));
    
    if ($charity == '')
        $bid = $blog_id;  // Use THIS blog.
    else
        $bid = $wpdb->get_var("SELECT blog_id FROM wp_blogs WHERE domain like '$charity.%'");

    $bt = $wpdb->blogs;
    $tmp_path = $wpdb->get_var( $wpdb->prepare("SELECT path FROM $bt WHERE blog_id = %d", $bid) );
    $tmp_domain = $wpdb->get_var( $wpdb->prepare("SELECT domain FROM $bt WHERE blog_id = %d", $bid) );
    
    if ($bid > 1)
    {
        $name = get_blog_option ( $bid, "blogname");

        $list = new GiftList();
        $list->blogId = $bid;
        $list->getActiveGifts();
        
    for ($i = 0; $i < count($list->gifts); $i++)
        {
        if ($list->gifts[$i]["txtGiftQuantity"] <= 0)
           continue;

            $html = $list->gift2html($list->gifts[$i]);

            $site = explode('.', $tmp_domain); // Gets first word of URL from this
        $s = "<a class=\"charity-partner\" href=\"http://$tmp_domain$tmp_path\">";
            $s .= '<img src="' . get_charity_thumb($bid, $site[0]) . '" />';
            $s .= "<p>This story comes from SeeYourImpact's partner <span class=\"charity-name\">$name</span>.</p>";
            $s .= '<p><span class="cause">' . get_blog_option( $bid, "blogdescription"). ':</span> a donation of just <span class="price">$' . $html['gift_cost'] . '</span> will fund ' . $html['gift_name'] . '!</p>';

        $s .= '<p><u>Learn more</u>&nbsp;&raquo;</p>';
            $s .= '<div class="cleared"></div>';
        $s .= '</a>';
            
            return $s;
        }
    }
    
    return '';
}
add_shortcode('partner', 'sponsor_func');

function hid($name, $value)
{
   return '<input type="hidden" name="' . $name . '" value="' . $value . '">';
}

function impact_card_func($atts, $content=null, $code="") {
    global $blog_id;
    global $wpdb;
    
    extract(shortcode_atts(array(
        'amounts' => ''
    ), $atts));

    $site = get_current_site();

    $return_url="http://".$_SERVER['HTTP_HOST']."/".RETURN_URL;
    $cancel_return_url="http://".$_SERVER['HTTP_HOST']."/".CANCEL_RETURN_URL;
    $notify_url="http://".$_SERVER['HTTP_HOST']."/".NOTIFY_URL;

    return '<form action="' . FORM_ACTION . '" method="post">' .
           hid('cmd', "_oe-gift-certificate") .
       hid('business', BUSINESS_ID) .
       hid('lc', 'US') .
       hid('currency_code', 'USD') .
       hid('no_note', '1') .
       hid('no_shipping', '2') .
       hid('shopping_url', "http://$site->domain/gifts/redeem") .
       hid('style_color','BLU') .
       hid('currency_code', 'USD') .
       hid('bn', "PP-GiftCertBF:btn_giftCC_LG.gif:NonHosted") .
       hid('cancel_return', $cancel_return_url) .
       hid('notify_url', $notify_url) .
       hid('return', "http://$site->domain/pay/thank-you") .
       hid('tax', '0') .
       hid('logo_url', 'https://www.seeyourimpact.org/wp-content/images/paypal-gc.gif') .
       '<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_giftCC_LG.gif" border="0" name="submit" alt=""></form>';
}
//add_shortcode('certificate', 'impact_card_func');
add_shortcode('certificate', 'impact_card_syi');
add_shortcode('impact-card', 'impact_card_syi');

function impact_card_syi() {
  return '<a class="charity-link donate-now" href="'.get_bloginfo("url").'/payments/confirm.php?gift=1"><u>Buy an Impact Card</u></a>'; 
}

function custom_aa_userlist_empty($default) 
{
   return '<p>We are always looking for volunteer help to assist with the site.  <a href="mailto:contact@seeyourimpact.org">Contact SeeYourImpact</a> to find out more!';
}
function custom_aa_user_template($default) 
{
   return '<div class="{class}" style="width:120px; overflow:hidden;">{user}</div>';
}
add_filter('aa_userlist_empty', 'custom_aa_userlist_empty');
add_filter('aa_user_template', 'custom_aa_user_template');

function number2text($number, $lowercase = false) {  
    if ($number == 0) $string = "Null";  
    else if ($number == 1) $string = "One";  
    else if ($number == 2) $string = "Two";  
    else if ($number == 3) $string = "Three";  
    else if ($number == 4) $string = "Four";  
    else if ($number == 5) $string = "Five";  
    else if ($number == 6) $string = "Six";  
    else if ($number == 7) $string = "Seven";  
    else if ($number == 8) $string = "Eight";  
    else if ($number == 9) $string = "Nine";  
    else if ($number == 10) $string = "Ten";  
    else if ($number == 11) $string = "Eleven";  
    else if ($number == 12) $string = "Twelve";  
    else $string = $number;  
  
    if ($lowercase === true) return strtolower($string);  
    else return $string;  
}

function build_thankyou_page() {
  global $wpdb, $post;
  $payment = NULL; $donation = NULL; $donationGiver = NULL;
  $tweet_this_hide = get_post_meta($post->ID,'tweet_this_hide',true);
  if (!$tweet_this_hide) {
    add_post_meta($post->ID,'tweet_this_hide','true',true);
  } elseif ($tweet_this_hide=='false') {
    update_post_meta($post->ID,'tweet_this_hide','true');  
  }
  
  if (isset($_REQUEST['cm']) || isset($cm)) {

    if(isset($_REQUEST['cm'])){
      $custom = decrypt($_REQUEST['cm']);
      $paymentMethod = $_REQUEST['pm'] or 'PP';
    }else{
      $custom = decrypt($cm);
      $paymentMethod = $pm or 'PP';
    }

    $payment = $wpdb->get_row( $wpdb->prepare("SELECT * FROM payment WHERE id = %d",intval($custom)),ARRAY_A );  

    if ($payment == NULL)
      return;
    
    $cart = unserialize($payment['cart']);  

    $donation = $wpdb->get_row( $wpdb->prepare("SELECT * FROM donation WHERE paymentID = %d",intval($payment['id'])), ARRAY_A);  

    if ($donation != NULL)
     $donationGiver = $wpdb->get_row( $wpdb->prepare("SELECT * FROM donationGiver WHERE ID = %d",intval($donation['donorID'])), ARRAY_A);
            
    list($tip, $notifyme, $giftid, $referral, $discount, $code, $mg, $recipientDonorId) = preg_split('/\|\|/',$cart['custom']);

      //////////////////////////////////////////////////////////////////////////

    $total = ($cart['amount_1'] * $cart['quantity_1']) + ($cart['amount_2'] * $cart['quantity_2']) - $cart['discount_amount_cart'];
    $gift = $wpdb->get_row($wpdb->prepare("SELECT * FROM gift WHERE id=%d",$giftid));

    $DONOR_NAME = trim($donationGiver['firstName']);
    $THANKS = empty($DONOR_NAME) ? "Thank you" : "Thank you, $DONOR_NAME";
    $DONOR_EMAIL = trim($donationGiver['email']);
    $DONATION_AMOUNT = as_money($cart['amount_1'] * $cart['quantity_1']);
    $GIFT_NAME = $gift->displayName;
    $GIFT_NAME_PLURAL = $gift->pluralName;
    $CHARITY_NAME = $cart['os0_1'];
    $CHARITY_MAIN = $wpdb->get_var("SELECT domain FROM wp_blogs WHERE blog_id = 1");
    $CHARITY_SUB = $wpdb->get_var( $wpdb->prepare( "SELECT domain FROM wp_blogs WHERE blog_id = %d", $gift->blog_id));

    $CHARITY_SITE = str_replace(".$CHARITY_MAIN",'',$CHARITY_SUB);
    $CHARITY_SUB = "http://$CHARITY_SUB";
    $CHARITY_MAIN = "http://$CHARITY_MAIN";

    $PAYMENT_METHOD = array();
    if ($cart['discount_amount_cart'] > 0) 
      $PAYMENT_METHOD[] = 'impact card'; 
    if ($paymentMethod == '')
      $paymentMethod = 'PP';
    if ($paymentMethod != "GC") {
      $paymentMethods = array('PP'=>'PayPal','GG'=>'Google Checkout','CC'=>'Credit Card','AM'=>'Amazon');
      $PAYMENT_METHOD[] = $paymentMethods[$paymentMethod]; 
    }
    $PAYMENT_METHOD = implode(' and ', $PAYMENT_METHOD);

    $TIP_AMOUNT = $cart['quantity_2'] > 0;

    if (!empty($mg) && strlen($mg) == 10) {
      $MATCHING_NOTE = 'And thanks to our sponsors, your donation has been doubled!';        
    }

    if ($cart['gift'] == 1) { //GC Purchase 
      $recipient = $wpdb->get_row($wpdb->prepare("SELECT CONCAT(firstName,' ',lastName) as name, email, notes FROM donationGiver WHERE ID=%d", $recipientDonorId), ARRAY_A);

      $RECIPIENT = $recipient['name'];

      $RECIPIENT_EMAIL = trim($recipient['email']);
      if (!empty($RECIPIENT_EMAIL))
        $RECIPIENT .= " ($RECIPIENT_EMAIL)";

      $RECIPIENT_MESSAGE = trim(htmlspecialchars($recipient['notes']));
      if (!empty($RECIPIENT_MESSAGE))
        $RECIPIENT .= " with this message:\n\n<strong>\"$RECIPIENT_MESSAGE\"</strong>";
      else
        $RECIPIENT .= ".";

      $template .= "Your $DONATION_AMOUNT Impact Card will be sent to $RECIPIENT\n\n";
      $template .= "It can be redeemed at any charity on <a href=\"http://seeyourimpact.org/\">SeeYourImpact.org</a>! ";
      $template .= "For information about Impact Card redemption, please visit: <a style=\"text-decoration:underline;\" href=\"http://seeyourimpact.org/redeem\">seeyourimpact.org/redeem</a>.";

    } else {
      $WHEN = "Within a couple of weeks";

      $GIFT_AMOUNT_NAME = $GIFT_NAME;
      $BENEFICIARY = " for a beneficiary";
      if ($cart['quantity_1'] > 1) {
        if($GIFT_NAME_PLURAL != ''){
          $GIFT_AMOUNT_NAME = $GIFT_NAME_PLURAL;
        } else {
          $GIFT_AMOUNT_NAME = trim(number2text($cart['quantity_1'],true).' '
          .
          str_replace(
            array(" a "," an "," set"," year"," month"),
            array(" "," "," sets"," years"," months"),
            " ".strtolower($GIFT_NAME)
          )
          .(
            strpos(strtolower($GIFT_NAME)," of ") !== FALSE ? '':
            strpos(strtolower($GIFT_NAME)," set") !== FALSE ? '':
            strpos(strtolower($GIFT_NAME)," year") !== FALSE ? '':
            strpos(strtolower($GIFT_NAME)," month") !== FALSE ? '':'s'
          ));
        }

        if ($gift->towards_gift_id == 0) {    
          $BENEFICIARY = " for beneficiaries";
        }
      }
     
      if ($gift->towards_gift_id > 0) {  
        $aggGift = $wpdb->get_row($wpdb->prepare("SELECT * FROM gift WHERE id=%d",$gift->towards_gift_id));          
        $AGGREGATE_NAME = $aggGift->displayName;            
        $AGGREGATE_AMOUNT = as_money($aggGift->unitAmount);
        
        $WHAT = "Your gift of $DONATION_AMOUNT will contribute toward $AGGREGATE_NAME $BENEFICIARY";
        $WHEN = "The beneficiary of your gift will be selected when donations reach $AGGREGATE_AMOUNT. $WHEN";

      } else {
        $WHAT = "Your gift of $DONATION_AMOUNT will support $GIFT_AMOUNT_NAME";
      }

      $template .= "$WHAT at $CHARITY_NAME. $MATCHING_NOTE\n\n";
      if ($gift->varAmount == 0) {
        $template .= "$WHEN, we'll send you a photo and the story of your gift's impact.  "
          ."(<a href=\"$CHARITY_SUB/stories\" target=\"_blank\"><u>Click here to see stories from other donations</u></a>.)";
      } else {
        $template .= "<a href=\"$CHARITY_SUB/stories\"><u style='text-decoration:underline;'>Click here</u> to see the latest updates from this project.</a>";
      }
    }

    $wallMsg = "I support $CHARITY_NAME at SeeYourImpact.org. Choose a cause-change a life. ";

?>

<div style="padding-top: 20px;" id="thank-you">
  <div style="float: left; width: 400px;">
    <a href="<?= $CHARITY_SUB ?>"/><img class="pic" style="padding:3px; background:#fff; margin-bottom: 5px;" src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="375" height="250" /></a>
    <? if (!empty($CHARITY_SUB)) { ?>
      <a href="<?= $CHARITY_SUB ?>"/><img class="pic" style="padding:3px; background:#fff; margin-right: 2px;" src="<?= $CHARITY_MAIN ?>/wp-content/charity-images/charity-<?=$CHARITY_SITE?>.jpg" alt="" width="180" height="80" /></a>
      <a href="<?= $CHARITY_SUB ?>"/><img class="pic" style="padding:3px;background:#fff;" src="<?= $CHARITY_MAIN ?>/wp-content/charity-images/default.jpg" alt="" width="180" height="80" /></a>
      <? if($CHARITY_NAME != '') { ?>
      <br/>
      <a style="text-decoration:underline;" href="<?= $CHARITY_SUB ?>/about"/>Read more about <?= $CHARITY_NAME ?>, a SeeYourImpact network partner</a>.
      <? } ?>
    <?  } ?>
  </div>
  <div style="margin-left: 410px; word-wrap:break-word;">
    <h1 style="text-transform: uppercase;"><?= $THANKS ?>!</h1>
    <div>
    <?= nl2br($template); ?>


    <? if ($giftid>1 && FBCONNECT_ENABLED) { ?>
      <br/><br/>
      <?= display_fbconnect_button($donationGiver,$donation); ?>
      <br/><br/><br/><br/>
    <? } ?>

    <? draw_promo_content('thanks-matching', 'h3'); ?>
    </div>

  </div>
  <br style="clear:both;" />
    <p>Please spread the word about <?=($CHARITY_NAME==''?'SeeYourImpact.org':$CHARITY_NAME)?>:</p>
    <p>
    <a href="http://www.facebook.com/sharer.php?t=<?= urlencode($wallMsg) . $cart['blog_url'] ?>&u=<?= $cart['blog_url'] ?>"><img src="/wp-content/images/facebook_24.png" width="24" height="24" alt=""/></a>
    <a href="http://www.twitter.com/home/?status=<?= urlencode($wallMsg) . $cart['blog_url'] ?>"><img src="/wp-content/images/twitter_24.png" alt=""/></a>
    </p>

</div>

<?
  }
}

add_shortcode('thankyou','build_thankyou_page');


function display_twitter_sharing() {
?>
  <a href="http://twitter.com/home/?status=Meet%20the%20life%20you%20change!%20I%20just%20donated%20at%20http://SeeYourImpact.org" title="Share this on Twitter"><img src="/wp-content/themes/syi/images/SocialMediaBookmarkIcon/buttons/twitter.png"></a>
<?
}

function charity_crumbs($crumbs = NULL, $social = TRUE) {
  global $blog_id, $wpdb;

  $site_url = home_url().'/';
  $prof = $site_url;

  $info = $wpdb->get_results($sql = $wpdb->prepare(
    "SELECT name, location, live FROM charity WHERE blog_id=%d",$blog_id));
  $loc = $info[0]->location;
  $name = $info[0]->name;
  $live = $info[0]->live;

  ?>
  <div class="tabs">
    <?
    charity_tab($prof, 'home', 'give');
    charity_tab($prof, 'about', 'about us');
    charity_tab($prof, 'stories', 'stories');
    ?>
  </div>
  <a href="<?= $site_url ?>" class="left">
    <b class="profile-name"><?= xml_entities($name) ?></b><? if (!$live) { ?><span class="preview">PREVIEW</span><? } ?>
  </a>
  <? if (!empty($loc)) { ?>
    <span class="left info">
      <img src="<?= __C('themes/syi/images/location.gif')?>" width="11" height="15"> <b><?= xml_entities($loc) ?></b>
    </span>
  <? } ?>

  <? if ($social) { ?>
  <span class="right social-icons">
  <span class="left social-icon">
    <a class="twitter-share-button ev" id="twitter-share" href="http://twitter.com/share" data-url="<?=$site_url?>" data-via="SeeYourImpact">Tweet</a>
  </span>
  <span class="left social-icon">
    <fb:like href="<?=$site_url?>" layout="button_count" width="100" show_faces="false"></fb:like>
  </span>
  </span>
  <? }
}

function charity_tab($prof, $component, $label, $display = true) {
  global $post;
  if (!$display) return;

  $current = (isset($post) && ($post->post_name == $component));
  $url = $prof.$component;
  if ($component == 'home') {
    if (is_front_page() || (isset($post) && ($post->post_type == 'event')))
      $current = TRUE;
    $url = $prof;
  } else if ($component == 'stories' && (is_archive() || (isset($post) && ($post->post_type=='post'))))
    $current = TRUE;

//  wp_reset_query();
  ?><a href="<?=$url?>" class="tab tab-<?=$component?><?= $current ? " tab-current" : "" ?>"><?= xml_entities($label)?></a>
  <?
}

function partners_widget() {
  global $wpdb;

  $partners = PartnerApi::get(array(
    'order' => 'name',
    'private' => 0,
    'region' => eor($_REQUEST['tag'], $_REQUEST['region'])
  ));

  ?><section class="box-model"><?
  foreach ($partners as $b) {
    ?>
    <a id="partner-<?=$b->domain?>" href="<?=$b->url?>" class="partner cf">
      <img class="partner-image" width="200" height="90" src="<?= get_charity_thumb($b->blog_id, $b->domain, array(200,90), TRUE) ?>">
      <span class="partner-name"><?= xml_entities($b->name) ?></span>
    </a>
    <?
  }
  ?></section><?
}

function partners_shortcode($args, $content) {
  return shortcode_widget('partners_widget', $args, $content);
}
add_shortcode('partners','partners_shortcode');


function get_charity_thumb($blog_id, $site = NULL, $size = NULL, $url_only = TRUE) {
  if (empty($site)) {
    global $wpdb;
    $site = $wpdb->get_var($wpdb->prepare(
      "SELECT domain FROM charity WHERE blog_id=%d",
      $blog_id));
  }
  $url = __C("charity-images/charity-$site.jpg");

  if (is_array($size)) {
    $url = image_src($url, image_geometry($size));
  }

  return $url;
}


// Keep charity table in sync
add_action('update_option_blogname', 'update_option_blogname');
function update_option_blogname($x) {
  global $wpdb, $blog_id;
  $val = get_option("blogname");
  $val = html_entity_decode($val, ENT_QUOTES | ENT_COMPAT | ENT_HTML401);
  $wpdb->update('charity', array('name' => $val), array('blog_id' => $blog_id), array('%s'));
}

// Keep charity table in sync
add_action('update_option_blogdescription', 'update_option_blogdescription');
function update_option_blogdescription($x) {
  global $wpdb, $blog_id;
  $val = get_option("blogdescription");
  $val = html_entity_decode($val, ENT_QUOTES | ENT_COMPAT | ENT_HTML401);
  $wpdb->update('charity', array('description' => $val), array('blog_id' => $blog_id), array('%s'));
}

function our_gifts_shortcode($args) {
  return shortcode_widget('our_gifts', $args);
}
add_shortcode('our_gifts', 'our_gifts_shortcode');
function our_gifts($args) {
  global $campaign;

  Widget::gift_browser(array(
    'title' => eor($args['title'], " "),
    'tag' => $campaign->tags
  ));
}


