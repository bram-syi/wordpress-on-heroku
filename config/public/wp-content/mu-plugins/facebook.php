<?
/*
Plugin Name: Facebook
Plugin URI: http://www.seeyourimpact.com/
Version: 1.0
Author: Yosia Urip
Description: all about facebook
Author URI: http://www.seeyourimpact.com/
Instructions:
 */

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/mu-plugins/syi-facebook.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/mu-plugins/syi-log.php');

if(is_fb_connect_enabled()) {
  add_action('get_header', 'process_fb_connect');
}

define('FB_GRAPH_URL','https://graph.facebook.com/');
define('FB_PERMISSIONS', 'publish_stream,email');

function get_fb_user($uid) {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
  global $bp;

  $session = get_user_meta($uid,'fb_session',true);
  return json_decode($session, false);
}

function is_fb_connect_enabled() {
  return get_blog_option(1, 'fb_connect') == 1;
}

function display_fb_publish_button($cartID = 0, $img = '/wp-content/templates/facebook.png') {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));

  global $fb_session;
  global $cartID;

  // Must have a Facebook session to publish through app
  if ($fb_session == null) return false;

  ?><a href="<?= add_query_arg('fb_publish',1)?>" class="share-button"><img src="<?=$img?>" /></a><?
  return true;
}

function display_fb_publish_options($cartID = 0) {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));

  global $cartID;
  global $fb_session;
  global $bp;

  if (!is_fb_connect_enabled()) return;

  $user_id = $bp->loggedin_user->id;

  $pub_thanks = get_user_meta($user_id, 'fb_publish_thanks', true);
  $pub_story = get_user_meta($user_id, 'fb_publish_story', true);
  $first = !$pub_thanks && !$pub_story;

  // Don't show if they've already chosen both boxes.
  if ($fb_session != NULL && $pub_thanks && $pub_story)
    return;

  ?><p><?

  if (is_fb_published(get_donation_from_cart($cartID),11,0)) {
    ?>This donation has been published on your Facebook wall. <?
  }

  if ($first) {
    ?><b>Share an update</b> on Facebook:</p>
    <div class="share-left"><?
    draw_check_option('publish_thanks', " when I make a donation", true);
    draw_check_option('publish_story', " when I receive an Impact Story", true);
    $label = "Share on Facebook";
    $id = "facebook-share";
  } else {
    ?>You've chosen to automatically publish to Facebook:</p>
    <div class="share-left"><?
    draw_check_option('publish_thanks', " when you make a donation", $pub_thanks);
    draw_check_option('publish_story', " when you receive an impact story", $pub_story);
    $label = "Update sharing";
    $id = "facebook-update";
  }


  ?></div><div class="share-right">
  <button name="fb_publish" value="1" id="<?= $id ?>" class="fb-connect button facebook-button medium-button ev">
    <img src="<?= __C('images/facebook_24.png') ?>" width="24" height="24" alt=''/> <?=$label ?></button>
    </div><?
  }

  function process_publish_cart($cartID) {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    global $current_user;
    global $fb_session;

  // Only check if this is a click on the fb publish button
    $publish = $_REQUEST['fb_publish'];
    if (!$publish)
      return;

  $resend = FALSE; // TODO: allow resends
  if ($_POST) {
    $pub_thanks = $_REQUEST['publish_thanks'];
    $pub_story = $_REQUEST['publish_story'];

    get_currentuserinfo();
    update_user_meta($current_user->ID, 'fb_publish_thanks', $pub_thanks == 1);
    update_user_meta($current_user->ID, 'fb_publish_story', $pub_story == 1);
  }

  $url = $_SERVER['SCRIPT_URI'];
  if (!empty($_SERVER['QUERY_STRING']))
    $url .= "?" + $_SERVER['QUERY_STRING'];
  $end_url = add_query_arg('fb_publish', NULL, $url);

  if (!empty($cartID)) {
    df("PUBLISHING TO FACEBOOK WALL -- MANUAL ON THANK YOU PAGE");
    $donationID = get_donation_from_cart($cartID);
    $fbp = fb_publish_donation($donationID, 1, 0, 0, $resend);
    if(get_blog_option(1,'fb_debug') == 1)
      debug(print_df(true),true,($fbp!=1?"ERROR - ":"")."FB PUBLISH - MANUAL");
  }

  wp_redirect( $end_url );
  die();
}

function fb_disconnect() {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
  global $wpdb;

  if (wp_verify_nonce($_REQUEST['_wpnonce'], 'disconnect-me')
    && is_user_logged_in()) {
    $user_id = get_current_user_id();
    $rows = $wpdb->get_results("select * from wp_usermeta where user_id = $user_id and meta_key like 'fb%' and meta_key not in('fb_archive', 'fb_id'");
    $archive = array('archived' => gmdate('c')); // iso-8601 date string 2012-12-04T22:40:23+00:00
    foreach ($rows as $row) {
      $json = json_decode($row->meta_value);
      $archive[$row->meta_key] = $json ? $json : $row->meta_value;
      $wpdb->query("delete from wp_usermeta where umeta_id = $row->umeta_id");
    }

    add_user_meta($user_id, 'fb_archive', json_encode($archive));
  }

  wp_logout();
  wp_redirect( remove_query_arg(array('fb_disconnect', '_wpnonce')) );
  exit;
}

function update_fb_session() {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
  global $wpdb;

  $user_id = get_current_user_id();

  if (!$user_id) {
    SyiFacebook::trace("no current user");
    $facebook = new SyiFacebook();
    $me = (object)$facebook->api('/me');
    if (!$me) {
      error_log("unable to retrieve user info from open graph");

    }
    else {
      $existing_users = $wpdb->get_results( $wpdb->prepare(
        "select user_id as ID from wp_usermeta where meta_key = 'fb_id' and length(meta_value) > 0 and meta_value = %s", $me->id
      ));
      if (count($existing_users) == 0) {
        // yes, return an array on success, or a "false" on failure
        SyiFacebook::trace("we do not know about this FB id: $me->id");
        $user = createWpAccount($me->email, $me->first_name, $me->last_name);
        if ($user === false) {
          global $error_wp_signin;
          error_log("could not create account: $error_wp_signin");
        }
        else {
          $user_id = $user[1];
          if (!$user_id) {
            error_log("createWpAccount() failed using json: $json");
          }
        }
      }
      else {
        SyiFacebook::trace("we do know about this FB id: $me->id");
        // just grab the lowest user ID
        function id_compare($a, $b) {
          if ($a->ID == $b->ID) {
            return 0;
          }
          else {
            return $a->ID < $b->ID ? -1 : 1;
          }
        }

        usort($existing_users, 'id_compare');
        $user_id = $existing_users[0]->ID;
      }

      if ($user_id) {
        SyiFacebook::trace("updating user $user_id");
        $facebook->setExtendedAccessToken();
        update_user_meta($user_id, 'fb_id', $me->id);
        update_user_meta($user_id, 'fb_access_token', $facebook->userToken());
        wp_update_user(array(
          'ID' => $user_id,
          'user_email' => $me->email,
        ));
        auto_wp_login($user_id);
      }
      else {
        error_log("no user_id was created for user attempting to login");
      }
    }
  }

  $uri = remove_query_arg(array('session','perms','selected_profiles','installed','state','code'));
  wp_redirect( $uri );
}

function process_fb_connect() {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
  df('-process fb connect function-');

  if(isset($_GET['fb_disconnect'])){ // User disconnects
    fb_disconnect();
    return;
  }
  if (isset($_REQUEST['code']) && isset($_REQUEST['state'])) {
    // visitor has logged in via facebook, and gone through facebook.com's auth
    // and permission flow, so we need to do more
    update_fb_session();
  }
}

function can_fb_connect() {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));

  global $bp;

  if ($bp->loggedin_user->id == 1)
    return FALSE; // Admin can't connect

  return is_fb_connect_enabled() && !is_fb_connected();
}

function is_fb_connected() {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
  global $fb_session;
  return !empty($fb_session);
}

function display_fb_connect_offer() {
  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
  global $bp;
  global $fb_session;

  get_currentuserinfo();
  $connected = !empty($fb_session);

  ?><div class="profile-fb-info"><?
  if ($connected) {
    $fbme = get_fb_user_info($fb_session);

      if(empty($fbme)) { //dev machine
        ?>
        <img src="<?= get_fb_avatar_url($bp->loggedin_user->id); ?>" width="50" height="50" alt="" style="border:1px solid #ccc; float:left; margin-right:10px;"/>You're connected to Facebook <br/>
        <?
      } else {
        ?>
        <img src="<?= $fbme['picture'] ?>" width="50" height="50" alt="" style="border:1px solid #ccc; float:left; margin-right:10px;"/>You're connected to Facebook<br/>as <a class="fb-name" target="_new" href="<?=$fbme['link']?>"><?=$fbme['name']?></a>
        <?
      }
      ?>
      <div id="fb-connected-pref">
        <a href="<?= wp_nonce_url(add_query_arg('fb_disconnect',1), 'disconnect-me') ?>" onclick="return confirm('You will no longer be able to log in using Facebook.  Are you sure?')"><u class="disconnect">Disconnect from Facebook</u></a>
      </div>
      <?
    } else {
      display_fb_login();
    }
    ?></div><?

    return $connected;
  }

  function get_fb_avatar_url($user_id,$get='') {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    $fb_id = get_user_meta($user_id, 'fb_id', true);
    if (empty($fb_id))
      return NULL;

    switch ($get) {
      case '?type=small':
      $size = "w_50,h_50";
      break;
      default:
      case '?type=large':
      $size = "w_250,h_250";
      break;
    }

    return "http://res.cloudinary.com/seeyourimpact/image/facebook/$size,c_thumb,g_faces/$fb_id.jpg$get";
  }

  function display_fb_login($small = false, $msg = "Log in with Facebook", $promo = false) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    global $current_user;

    if (!is_fb_connect_enabled())
      return false;
    if ($current_user->ID == 1) {
      ?><div><img style="vertical-align:middle;" width="24" height="24" src="<?= __C('images/facebook_24.png') ?>"> Admin can't connect via Facebook</div><?
      return false;
    }
    $facebook = new SyiFacebook();
    $loginUrl = $facebook->getLoginUrl(array(
      'scope' => FB_PERMISSIONS
      ));

    if ($small !== FALSE)  {
      if ($small == "connect")
        $fb_login_img = __C('images/fb_connect.png');
      else
        $fb_login_img= __C('images/fb_login_sm.png');
      ?><a class="fb-connect" href="<?=$loginUrl?>"><img src="<?=$fb_login_img?>" height="22" alt=''/></a><?
    } else {
      $fb_login_img= __C('images/fb_login.png');
      ?><a class="fb-connect button facebook-button medium-button" href="<?=$loginUrl?>"><img src="<?= __C('images/facebook_24.png') ?>" width="24" height="24" alt=''/> <?= $msg ?></a><?
      if(!empty($_GET['fb_error'])) echo '<div class="error">'.$_GET['fb_error'].'</div>';
    }
  }

  function get_public_fb_user_info() {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    //TO DO: display publicly available fb info, verify signin to the right acct
  }

  function auto_wp_login ($user_id) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    $user = get_userdata($user_id);
    if(!empty($user->user_login)) {
      wp_set_current_user($user_id, $user->user_login);
    // Steve: 2nd param = as if "remember me" is checked
    // 3rd param = false so that user is AUTH'd for HTTP
    // even if this is an HTTPS page
      wp_set_auth_cookie($user_id, true, false);
      wp_set_auth_cookie($user_id, true, true);
      do_action('wp_login', $user->user_login);
    }
  }

  function print_df($return=false) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    global $df;
    if($return) return '<pre>-'.print_r($df,true).'-</pre>';
    echo'<pre>-'.print_r($df,true).'-</pre>';  exit();
  }
  function df($note) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    global $cartID;
    global $df;
    $df.="\n".$note;
    if(!empty($cartID)) {
      dc($cartID, $note);
    }
  }

  function get_fb_user_info($session='') {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    $facebook = new SyiFacebook(get_current_user_id());
    $fbme = $facebook->api('/me');
    $fbme['picture'] = FB_GRAPH_URL . $fbme['id'] . '/picture';
    if(empty($fbme['error'])) { return $fbme; }
    return false;
  }

  function fb_query_user($fbid, $fields) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    if (is_array($fields))
      $fields = implode(',', $fields);

    $fql = "select $fields from user where uid=$fbid";
    $param = array(
      'method' => 'fql.query',
      'query' => $fql,
      'callback' => ''
      );
    $facebook = new SyiFacebook(get_current_user_id());
    return $facebook->api($param);
  }

  function search_user_fb_session($session) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    global $wpdb;

    $fbme = get_fb_user_info($session);
    $user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM wp_usermeta
      WHERE meta_key='fb_id' AND meta_value=%s", $fbme['id']));
    df('search fb session with fb id: '.$fbme['id'].' found user#'.$user_id);

  //  $user_id=email_exists($fbme['email']);
  //  df('search fb session with email: '.$fbme['email'].' found u#'.$user_id);

    return $user_id;
  }

  function get_fb_session($user_id) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    return json_decode(get_user_meta($user_id,'fb_session',true),true);
  }

  function is_fb_session_valid($session) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    if (empty($session))
      return false;
    $fbme = get_fb_user_info();
    return !empty($fbme) && empty($fbme['error']);
  }

  function is_fb_published($donationID,$typeID,$postID) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    global $wpdb;

    $published = $wpdb->get_var($wpdb->prepare(
      "SELECT notificationID FROM notificationHistory
      WHERE success=1 AND mailType=%d
      AND donationID=%d AND postID = %d",
      $typeID, $donationID, $postID));

    return $published;
  }

////////////////////////////////////////////////////////////////////////////////


  function get_1st_gift($donationID) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    global $wpdb;

    $gift_id = $wpdb->get_var($wpdb->prepare(
      "SELECT g.id FROM donation d
      JOIN donationGifts dg ON dg.donationID = d.donationID
      JOIN gift g ON g.id = dg.giftID
      WHERE d.donationID = %d
      ORDER BY (g.id != 50) DESC
      LIMIT 1",
      $donationID));

    return $gift_id;
  }

  function fb_can_publish($user_id, $type) {
    SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));
    if ($user_id < 0)
      return false;
    if (!get_blog_option(1, 'fb_connect'))
      return false;
    return get_user_meta($user_id, "fb_publish_$type", true) == 1;
  }

  function fb_publish_donation($donationID, $blogID=1, $postID=0, $giftID=0,
      $resend=false){

  SyiLog::log("facebook", "legacy: ".json_pretty(func_get_args()));

  //11 single thank you
  //13 aggregate thank you
  //12 single post
  //14 aggregate post
    global $fb_session;
    global $wpdb;
    global $GIFTS_EVENT;

    df('--fb publish donation function--');

  //get donation
    $d = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM donation WHERE donationID = %d", $donationID), ARRAY_A);

    if (empty($d)) { df('donation #$donationID not found'); return -1; }

    df("fb publish for donation#$donationID");
    df("fb publish for story#$postID");
  // Get the user for this donation, by way of the donationGiver
    $user_id = $wpdb->get_var($wpdb->prepare(
      "SELECT user_id FROM donationGiver WHERE ID=%d", $d['donorID']));

    if (empty($user_id)) { df('user not found'); return -1; }

    df("fb publish for user#$user_id");

  // Determine the type of FB story to post.
  //TO DO: create special condition for Aggregate story post
    if ($postID > 0) {
    // NEVER publish unless the user has granted story publish OK
      if (!fb_can_publish($user_id, "story")){
        df('u#'.$user_id.' d#'.$d['donorID'].' not allowing story publish -');
        return -1;
      }
      $typeID = 12;
      $fields = 'fb_storypost_fields';
    } else if ($blogID == 1) {
    // Don't publish unless the user has granted permission -
    // or if "resend" is true, which is the user action of pressing the button
      if (!$resend && !fb_can_publish($user_id, "thanks")) {
      //      df(!fb_can_publish($user_id, "thanks"));
        df('u#'.$user_id.' d#'.$d['donorID'].' not allowing thanks publish -');
        return -1;
      }
    // Publishing from blog 1 must be thankyou
      $typeID = 11;
      $fields = 'fb_thankyou_fields';
    } else  {
      df('no post id #'.$postID.' or blog id #'.$blogID);
      return -1;
    }

  //get fb session user meta
    $fb_session = json_decode(get_user_meta($user_id,'fb_session',true),true);
    df('fb session on user: '.print_r($fb_session,true));

  //get donation contact
    $dc = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM donationContact
      WHERE type='fb_session' AND donationID=%d", $donationID), ARRAY_A);
    $fb_session2 = json_decode($dc['value'],true);

    df('fb session on dc: '.print_r($fb_session2,true));

    if(is_fb_session_valid($fb_session)) {
      df('fb session exists and valid: '.print_r($fb_session,true));

      $fbme = get_fb_user_info($fb_session);
      df('fbme '.print_r($fbme,true));

    if ($fb_session != $fb_session2) { //fb session is diff from dc
      $wpdb->query($wpdb->prepare(
        "UPDATE donationContact SET value = %s
        WHERE type='fb_session' AND donationID=%d",
        json_encode($fb_session),$donationID));
      df('dc set to fb session');
    }
  } else if (is_fb_session_valid($fb_session2)){
    update_user_meta($user_id,'fb_session',json_encode($fb_session2));
    $fb_session = $fb_session2;
    df('fb session set to dc');
  } else { //no user no dc
    df('dc and fb session not found');
    return -1;
  }

  ////////////////////////////////////////////////////////////////////////////////

  // Already published?
  $published = is_fb_published($donationID, $typeID, $postID);
  if($published && !$resend) {
    df("fb has published type #$typeID already");
    return 0;
  }
  /*
     {
     $published = $wpdb->get_var($wpdb->prepare(
     "SELECT notificationID FROM notificationHistory
     WHERE success=1 AND mailType=%d AND donationID=%d",
     $typeID==11?12:11, $donationID));

  //TO DO: create special condition for Aggregate story post

  if(!empty($published)){ //posted thankyou and story
  df('fb published both');
  return 3;
  }else{ //posted thankyou only
  df('fb published one but not the other');
  return 2;
  }
  }
   */

  if ($resend) { df("fb resend type #$typeID"); }

  $fb_publish_args = unserialize(get_blog_option(1, $fields));
  if(!is_array($fb_publish_args)){
    df("error: fb publish args is not array: ".print_r($fb_publish_args,true));
    return;
  }

  // get the gift
  if ($giftID == 0)
    $giftID = get_1st_gift($donationID);

  if ($giftID == 0) {
    df("nothing to publish; publishing a give-any.");
    $giftID = CART_GIVE_ANY;
  }

  df('look for campaign of donation #'.$donationID.' and gift #'.$giftID);
  $GIFTS_EVENT = get_donation_campaign($donationID, $giftID);

  $campaign_url = '';

  if (!empty($GIFTS_EVENT)) {
    $campaign = get_post($GIFTS_EVENT);
    if (!empty($campaign)) {
      $campaign_name = $campaign->post_name;
      $campaign_url = '/support/'.$campaign_name.'/';
      df('adding campaign url to gift links: '.$campaign_url);
    }
  }

  // if an agg. var gift, use the parent gift
  $tg = get_avg_tgi($giftID);
  if (!empty($tg)) {
    $giftID = $tg;
  }

  $gift = $wpdb->get_row($sql = $wpdb->prepare(
    "SELECT g.id, g.displayName, g.pluralName, g.title, g.image,
    g.excerpt, g.description, g.unitAmount, g.varAmount
    FROM gift g WHERE g.id=%d",
    $giftID));
  if(empty($gift)) {df('gift not found: '.$sql); return 0;}

  // TODO: switch gift - for GC, GIVE_ANY, make specialized
  // publish versions.

  $url = site_url();

  ////////////////////////////////////////////////////////////////////////////////

  $replacements = array(
    '[gift_give_link]' => pay_link($giftID, "fb"),
    '[gift_page_link]' => $giftID == CART_GIVE_ANY ? pay_link($giftID, "fb") : details_link($giftID, "fb", $campaign_url),
    '[post_link]' => $giftID == CART_GIVE_ANY ? pay_link($giftID, "fb") : details_link($giftID, "fb", $campaign_url),
    '[gift_name]' => $gift->title,
    '[post_title]' => $gift->title,
    '[gift_description]' => $gift->description,
    '[post_excerpt]' => $gift->description,
    '[gift_price]' => $gift->unitAmount,
    '[gift_picture]' => gift_image_src($giftID),
    '[post_picture]' => gift_image_src($giftID)
    );

  if ($postID > 0) { //storypost replacements
    $post = get_blog_post($blogID, $postID);
    if(empty($post)) {df('post not found #'.$postID); return 0;}

    $replacements['[post_link]'] = $post->guid;
    $replacements['[post_title]'] = $post->post_title;
    $replacements['[post_excerpt]'] = $post->post_excerpt;
    //getExcerpt(strip_shortcodes($post->post_content));

    $img = wp_get_attachment_image_src(get_post_thumbnail_id($postID));
    if(is_array($img) && !empty($img[0]))
      $replacements['[post_picture]'] = $img[0];
    //$post->thumbnail_url;
    //df(print_r($post,true));
  }

  ////////////////////////////////////////////////////////////////////////////////

  //df(print_r(array_keys($replacements),true));
  //df(print_r(array_values($replacements),true));
  //df(print_r($fb_publish_args,true));

  foreach($fb_publish_args as $k=>$v) {
    $fb_publish_args[$k] = str_replace(array_keys($replacements),
      array_values($replacements),stripslashes($v));
  }

  //return ;

  ////////////////////////////////////////////////////////////////////////////////

  if($d->test==0) {
    if (true) {
      df('fb publish failed args: '.print_r($fb_publish_args,true));
      return 0;
    }
  } else {
    df('fb not published -TEST- args: '.print_r($fb_publish_args,true));
    return 0;
  }

  df('fb published: '.print_r($fb_publish_args,true));

  $wpdb->query($wpdb->prepare(
    "INSERT INTO notificationHistory
    (donationID, donationContactID, mailType, sentDate, success,
     donorID, emailTo, emailSubject, emailText, postID)
  VALUES (%d,%d,%d, NOW(),1,%d,'%s','%s','%s',%d)",
  $donationID,$dc['id'],$typeID, $d['donorID'],
  print_r($fb_session,true),
  '(FB wall post)',
  print_r($fb_publish_args,true),
  $postID));

  return 1;

}
