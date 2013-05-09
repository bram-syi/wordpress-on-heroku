<?
/*
Plugin Name: Pledges
Plugin URI: http://seeyourimpact.org/
Description: Enable user to make pledge for a campaign
Author: Yosia Urip
Version: 1.0

Author URI: http://seeyourimpact.org/
*/

define(SUBMIT_PLEDGE_TXT,'Submit your pledge');
define(UPDATE_PLEDGE_TXT,'Save');
define(CLOSED_PLEDGE_META,'pledge_closed');
define(CLOSED_PLEDGE_TXT,'<h2>Thanks for your support!</h2>The pledge drive is closed now.');

function draw_pledge_box($args = NULL) {
  global $post, $bp;

  $title = "What will you pledge?";

  $id = 'pledge';
  if (is_array($args))
    extract($args);

  if (empty($event_id))
    $event_id = $post->ID;
  if (!empty($id))
    $id = "id=\"$id\"";

  $closed = $closed || $campaign->closed;
  $end = strtotime($campaign->end_date);
  if (!empty($end)) {
    $now = time();
    $diff = $end - $now;
    $days = ceil($diff / 86400);
  }

  update_campaign_stats($event_id);
 
  ?><div <?=$id?> class="pledge-form widget <? if ($closed) echo 'closed'; ?>"><?

  if ($campaign != NULL) {
    $r = ceil(should_include_tips($campaign->id) ? $campaign->raised + $campaign->tip : $campaign->raised);
    if ($r == 0)
      $pct = intval($campaign->pledge_count) * 4;
    else {
      $g = eor($campaign->goal, 250); // DEFAULT GOAL: 250 so we don't round by zero
      $pct = ($r / $g) * 100;
    }
    if ($pct <= 0) 
      $pct = 0;
    else if ($pct >= 100) 
      $pct = 100;
    else if ($pct < 4)
      $pct = 4;
    else if ($pct > 98)
      $pct = 98;
    $pct = ceil($pct);

    $r = intval($r); // round to int

    if ($r == 0 && $campaign->pledge_count > 0) {
      $raised = '<b>' . plural($campaign->pledge_count, '</b> pledge');
    } else {
      $raised = "<b>$$r</b> raised";
    }

    if (!$no_stats) {
      ?>
        <div class="stats">
          <div class="values">
              <span class="left"><?= $raised ?></span>
              <span class="right"><b>$<?= $campaign->goal ?></b> <?= ($r >= $campaign->goal) ? 'goal' : 'needed' ?></span>
          </div>
          <div class="progress"><span class="progress-bar" style="width: <?=$pct?>%;"></span></div>
        </div>
      <?
    }
  }
        /* <span class="right"><b<?= plural($days, 'day')?></b> left</span> */


  if (empty($campaign->please))
    $campaign->please = "Please help me reach my goal!";
  ?><div class="pledge-goal box"><?= $campaign->please ?></div><?

  if(!$no_button) {
    if ($give_any || $closed) {
      syi_giveany_widget(array(
        'title' => '', 'message' => ''
      ));
    } else {
      ?><a style="display:block;margin:16px auto;" href="<?= $campaign->guid ?>pledge" class="button orange-button big-button">Donate</a><? 
    }
  }

  ?></div><?
}

function draw_pledge_form($campaign) {
  ?>
  <h1>Help me make a difference!</h1>
  <p>
    <? if ($campaign->books_goal > 0 && $campaign->closed) { ?>
      I read <b><?=$campaign->books_goal?> books</b> during this readathon.
      With your donation, every book I finished will help a child in India have a chance to read, too.
    <? } else if ($campaign->books_goal > 0) { ?>
      My goal is to read <b><?=$campaign->books_goal?> books</b> during this readathon.
      With your donation, every book I finish gives a child in India the chance to read, too.
    <? } else { ?>
      With your donation, every book I read gives a child in India the chance to read, too.
    <? } ?>
  </p>

  <p><b>100%</b> of your gift will be used to reach more children through Pratham's Read India program.</p>

  <form action="<?= get_permalink($campaign->id) ?>/pledge" method="POST" class="standard-form">
    <input type="hidden" name="event_id" value="<?= $event_id ?>">
    <? wp_nonce_field('submit_pledge', 'pledge'); ?>
    <? draw_pledge_options($campaign, FALSE); ?>
  </form>
<?
}

function draw_pledge_options($campaign, $error = NULL) {
  $pledge_url = add_query_arg('pay','pledge');
  $no_pledge_url = remove_query_arg(array('pay', '_pjax'));
  $closed = TRUE; // $campaign->closed;
  $name = get_displayname($campaign->owner, TRUE);

  $can_donate = TRUE;
  $can_pledge = $campaign->theme == 'readathon' && !$closed;

  $pledge = get_my_pledge_for_event($campaign->id);
  if ($pledge->amount > 0 && $can_pledge)
    $can_donate = FALSE;

  $pledge->name = trim(eor($_REQUEST['name'], $pledge->name));
  $pledge->email = trim(eor($_REQUEST['email'], $pledge->email));
  $pledge->amount = trim(eor($_REQUEST['pledge-amount'], $pledge->amount));
  if (strpos($pledge->amount, "$") === 0)
    $pledge->amount = substr($pledge->amount, 1);

  $l2 = "Give a gift:";

  $focused = "focused";
  if (!empty($error)) { 
    ?><div class="error"><?= $error ?></div><?
  }

  ?>

  <div style="margin: 0 -20px; position: relative;">
  <? if ($can_pledge) { $l2 = "Donate a specific amount right now." ?>
    <div style="position: absolute; left: 294px; top: 50px;">- or -</div>
    <div class="amount-row pledge-option">
      <label class="instructions" for="pledge-amount">Pledge an amount for each book I finish.</label>
      <label for="pledge-amount">$</label>
      <input type="text" id="pledge-amount" class="amount <?=$focused?>" name="pledge-amount" size="5" maxlength="6" value="<?= esc_attr($pledge->amount)?>">
      <label class="per-unit" for="pledge-amount"> per <?= xml_entities($campaign->unit) ?></label>
      <button class="button big-button orange-button" type="submit" name="go" value="go">Pledge</button>
    </div>
  <? } ?>
  <div class="amount-row donate-option">
    <label class="instructions" for="amount"><?=$l2?></label>
    <label for="amount">$</label>
    <input type="text" id="amount" class="amount <?=$focused?>" name="amount" size="5" maxlength="10" value="<?= esc_attr($_REQUEST['amount'])?>">
    <button class="button big-button orange-button" type="submit" name="go" value="go">Donate</button>
  </div>
  <? if (!$can_pledge) { ?>
    <div class="amount-row pledge-option" style="background: white; text-align:left;">
    Thanks so much...
    <p style="font-size: 12pt; margin-top: 10px;">Your gift will change a child's life in India!</p>
    </div>
  <? } ?>
  </div>
<?
}



function update_pledge_amount($event_id,$user_id=0,$email='',$amount=0) {
  global $wpdb;

  $wpdb->query($sql = $wpdb->prepare(
    "UPDATE pledges SET paid=paid+%f, date_updated=NOW()
    WHERE event_id=%d AND (user_id=%d OR email=%s)",
    $amount, $event_id, $user_id, $email));
}

function get_pledge_list($event_id) {
  global $wpdb;	

  return $wpdb->get_results($sql = $wpdb->prepare(
    "SELECT *,'per book' as unit FROM pledges WHERE event_id=%d ORDER BY date_updated DESC", $event_id));

}

function draw_pledge_list($args = NULL) {
  global $post, $wpdb, $edit;

//pre_dump($post);
  $event_id = $post->ID;
  if (is_array($args))
    extract($args);

  $pledges = get_pledge_list($event_id);
  if (count($pledges) == 0) return;

  ?><div class="pledge-table"><?
  if (!$raw) {
    ?><div class="pledge-title pledge-row">My supporters</div><?
  }

  foreach ($pledges as $pledge) {
    ?><div class="pledge-row pledge-id-XX fields" id="pledge-<?= $pledge->id ?>"><?
    $amount = "$pledge->message $pledge->unit";
    ?>
    <div class="right pledge-amount"><?= xml_entities($amount) ?> 
      <? if ($edit) { ?> - 
        <strong>due</strong>:
        $ <input type="text" name="due_<?= $pledge->id ?>" value="<?= doubleval($pledge->due) ?>" size="2" style="width:40px; text-align:right;" />
      <? } ?>
    </div>
    <div class="pledge-name"><?= xml_entities($pledge->name) ?> 
      <? if ($edit) { 
        if ($pledge->paid > 0) {
          ?><span class="pledge-paid">donated</span><?
        }
        ?><div style="font-size:12px;"><?= xml_entities($pledge->email) ?></div><?
      } ?>
    </div>
    </div><?
  }  
  if (!$raw) {
    ?><div class="pledge-row pledge-all hidden">see all</div><?
  }
  ?></div><?
}

function pledge_shortcode($args) {
  return shortcode_widget('draw_pledge_box', $args);
}
function pledge_list_shortcode($args) {
  return shortcode_widget('draw_pledge_list', $args);
}
add_shortcode('pledge', 'pledge_shortcode');
add_shortcode('pledge_form','pledge_shortcode');
add_shortcode('pledge_list','pledge_list_shortcode');
add_shortcode('pledge_table','pledge_list_shortcode');

// Draw a header bar that can be used by client script to display the 
// current pledge.  This can't draw MY pledge info because this is likely
// to be drawn inside a static/cached page
function draw_pledge_info($event_id) {
?>
  <div id="pledge-info" class="pledge-info hidden">
    You've pledged <span id="your-pledge"></span>.
    Thank you for your support!
  </div>
<?
}


function get_my_pledge_for_event($event_id) {
  global $bp, $wpdb, $pledge_id;
  get_pledge($event_id);

  $pledge = $wpdb->get_row($sql = $wpdb->prepare(
    "SELECT * FROM pledges WHERE id=%d", 
    $pledge_id));

  $p = new stdClass;
  $p->event_id = $event_id;
  $p->unit = "per book";

  if ($pledge != NULL) {
    $p->name = $pledge->name;
    $p->email = $pledge->email;
    $p->user_id = 0;
  } else if (is_user_logged_in()) {
    $user = $bp->loggedin_user;
    $p->name = $user->userdata->display_name;
    $p->email = $user->userdata->user_email;
    $p->user_id = $user->id;
  }
  $amount = $pledge->message;
  if(!empty($amount)) $p->amount = $amount;
  return $p;
}


function pledge_list_sc($atts,$content='') {
  global $post, $wpdb;
  $sql = $wpdb->prepare("SELECT * FROM pledges WHERE event_id=%d",$post->ID); 
  $pledges = $wpdb->get_results($sql);
  if (is_array($pledges))
    foreach ($pledges as $pledge) {
      pre_dump($pledge);
    }
}

function insert_pledge($event_id, $amount, $user_id, &$result) {
  global $bp, $wpdb, $pledge_id;
  if ($user_id == 0)
    $user_id = $bp->loggedin_user->id;

  $visitor_id = 0;
  $event_id = intval($event_id);

  $user = get_userdata($user_id);
  $email = $user->user_email;
  $name = get_displayname($user_id);

  debug(get_campaign_permalink($event_id) . "\n\n" . print_r(func_get_args(), TRUE) . "\n\n" . print_r(get_userdata($user_id), TRUE), true, "New pledge");

  if ($event_id == 0 || $user_id == 0) {
    $result = cart_error("Sorry, we are unable to process your pledge.");
    return FALSE;
  }

  $amount = trim($amount);
  if (is_numeric($amount))
    $amount = as_money(round($amount, 2));
  else if (empty($amount)) {
    $result = cart_error("Please enter the amount you'd like to pledge.");
    return FALSE;
  }

  $pledge_id = get_pledge($event_id);
  
  // Fetch that pledge
  $pledge = $wpdb->get_row($sql = $wpdb->prepare(
    "SELECT * FROM pledges WHERE id=%d OR
    (event_id=%d AND ((user_id = %d AND user_id > 0) OR email=%s)) LIMIT 1",
    $pledge_id, $event_id, $user_id, $email));
 
  // Update the table row
  if (!empty($pledge)) {
	$wpdb->query($sql = $wpdb->prepare(
	  "UPDATE pledges SET name=%s, email=%s, message=%s, visitor_id=IF(visitor_id=0,id,visitor_id),
	  date_updated=NOW(),
	  date_created = IF(date_created='0000-00-00 00:00:00',NOW(),date_created)
	  WHERE id=%d", $name, $email, $amount, $pledge->id));
    $pledge_id = $pledge->id;
	$visitor_id = $pledge->visitor_id;
  } else {
	$wpdb->query($sql = $wpdb->prepare(
	  "INSERT INTO pledges 
	  (name,email,message,user_id,event_id,status, date_created, date_updated) 
	  VALUES (%s,%s,%s,%d,%d,'promised', NOW(), NOW())", 
	  $name, $email, $amount, $user_id, $event_id));
	  $pledge_id = $wpdb->insert_id;
	  $visitor_id = $pledge_id;
	  $wpdb->query($sql = $wpdb->prepare(
		"UPDATE pledges SET visitor_id=%d WHERE pledge_id=%d", $visitor_id, $pledge_id));
  }

  // Flush the cache
  update_campaign_stats($event_id);
  if (function_exists('wp_cache_post_change'))
    wp_cache_post_change($event_id);

  // NOTE: We no longer use the PLEDGE cookie.

  return TRUE;
}

function update_pledges($event_id, $dues) {
  global $wpdb;

  foreach ($dues as $pledge_id => $due) {
    $sql = $wpdb->prepare("UPDATE pledges SET due=%d WHERE event_id=%d AND id=%d",
	    $due,$event_id,$pledge_id);
    $wpdb->query($sql);
  }	  

  return true;
}

function get_pledge_user_id($visitor_id, $event_id) {
  global $wpdb;
  if (!empty($visitor_id)) {
	 $where .=' AND visitor_id=%d AND user_id > 0';
  }
  if (!empty($event_id)) {
	 $where .=' AND event_id=%d ' ;
  }
  
  return $wpdb->get_row($wpdb->prepare("SELECT * FROM pledges 
    WHERE 1 $where  LIMIT 1", $id, $event_id));
}

function get_pledge($eid=0) {
  global $event_id, $current_user, $wpdb, $pledge_id;
  if (empty($eid)) $eid = $event_id; // when eid is passed (cart page)
  if (empty($eid)) return; // no event_id to get pledge
  $pid = 0;

  if (is_user_logged_in()) { // get pledge by user_id and event_id
    get_currentuserinfo();
    $uid = $current_user->ID;
    $sql = $wpdb->prepare("SELECT id FROM pledges WHERE event_id=%d AND user_id=%d",$eid,$uid);
	$pid = $wpdb->get_var($sql);

  } else if(isset($_COOKIE['PLEDGE']) && $_COOKIE['PLEDGE']!='0|0') {
	$cookie = $_COOKIE['PLEDGE'];
    $cookie = explode('|',$cookie);
    $uid = intval($cookie[0]);
    $vid = intval($cookie[1]);  
    if($uid!=0 || $vid!=0) {
      $sql = $wpdb->prepare("SELECT id FROM pledges WHERE event_id=%d AND (user_id=%d OR visitor_id=%d)",$eid,$uid,$vid);
      $pid = $wpdb->get_var($sql);
    }
  } 

  return $pid;
}


add_action('wp_logout','clear_pledge_cookie');

function clear_pledge_cookie() {
  @setcookie('PLEDGE', "", time() - 60*60*24, '/', IS_LIVE_SITE ? '.seeyourimpact.org' : '.seeyourimpact.com');	
}

?>
