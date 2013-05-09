<?php
/*
Plugin Name: Gift Campaigns
Plugin URI: http://www.seeyourimpact.org
Description: Adds shortcodes for charity gift campaigns
Version: 1.0
Author: Steve Eisner
Author URI: http://www.seeyourimpact.org
*/

require_once(ABSPATH.'/a/api/campaign.php');

function create_campaign_post($user_id, $theme, $content = NULL, $title = NULL) {
  global $bp;

  $p['post_type'] = CAMPAIGN_POST_TYPE;
  $p['post_author'] = $user_id;
  $p['post_content'] = $content;
  $p['post_title'] = $title;
  $p['theme'] = $theme;
  
  return apply_filters('campaign_editor_defaults', $p);
}

function spam_check_content(&$p, $recheck_reason = 'recheck_queue' ) {
    if (!function_exists('akismet_http_post'))
      return "Akismet not enabled";

    if (strlen($p['post_content']) < 80)
      return "We don't check short content (less than 80 chars)";

    $c = array();
    if (!IS_LIVE_SITE || akismet_test_mode() )
      $c['is_test'] = 'true';
    $c['recheck_reason'] = $recheck_reason;
    $c['blog']       = SITE_URL;
    $c['user_ip']    = $_SERVER['REMOTE_ADDR'];
    $c['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $c['referrer']   = $_SERVER['HTTP_REFERER'];
    $c['blog_lang']  = get_locale();
    $c['blog_charset'] = get_option('blog_charset');

    // $c['author'] =  (submitter's name)
    $c['comment_content'] = $p['post_content'];

    global $bp;
    if ($bp->loggedin_user->userdata) {
      $c['comment_author'] = $bp->loggedin_user->userdata->display_name;
      $c['comment_author_email'] = $bp->loggedin_user->userdata->user_email;
    }

    $query_string = '';
    foreach ( $c as $key => $data )
      $query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

    global $akismet_api_host, $akismet_api_port;
    $response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);

    if (isset($response[1]) && $response[1] == TRUE) {
      $p['post_content'] = strip_shortcodes( $p['post_content'] );
      $p['theme'] = 'spam';
    }

   return $response;
}

function start_campaign($p, $existing = 0) {
  $id = $p['ID'];
  if (empty($id))
    $is_new = TRUE;
  else {
    $old_post = get_post($id, 'ARRAY_A');
    $is_new = $old_post['post_status'] != 'publish';
  }
  $p['post_status'] = 'publish';

  $spam = spam_check_content($p);

  $id = wp_insert_post($p);
  if (is_wp_error($id))
    return form_error("There was a problem saving your fundraiser.");

  if ($existing > 0) {
    // End an existing fundraiser
    end_campaign($existing);
  }

  $p['ID'] = $id;
  $p['goal'] = eor($p['goal'], 250);

  update_post_meta($id, 'syi_gift_ids', $p['syi_gift_ids']);
  update_post_meta($id, 'syi_twitter_handle', $p['syi_twitter_handle']);
  do_action('save_campaign_metadata', $p);

  update_campaign_stats($id);
  if ($is_new)
    $cattrs['owner'] = $p['post_author'];
  if (!empty($p['start_date']))
    $cattrs['start_date'] = $p['start_date'];
  if (!empty($p['end_date']))
    $cattrs['end_date'] = $p['end_date'];
  if (!empty($p['public']))
    $cattrs['public'] = $p['public'];
  if (!empty($p['theme']))
    $cattrs['theme'] = $p['theme'];
  if (!empty($p['goal']))
    $cattrs['goal'] = $p['goal'];
  if (!empty($p['team']))
    $cattrs['team'] = $p['team'];
  if (!empty($p['syi_tag']))
    $cattrs['tags'] = $p['syi_tags'];

  global $wpdb;
  $wpdb->update('campaigns', $cattrs, array('post_id' => $id));

  $url = get_campaign_permalink( $id );

  $wpdb->update('campaigns', array('guid' => $url), array('post_id' => $id));

  if($is_new) {
    global $emailEngine;
    global $current_user;

    $theme = eor($p['theme'], 'fundraising');
    wp_get_current_user();

    $email_content = "<br/>User " . $current_user->user_login . " (#" . $current_user->ID . ") " .
        "has just created a fundraiser page: $url theme:" . eor($p['theme'], '(none)') .
        "<hr/>Fundraiser title:<br/>".stripslashes($p['post_title']).
        "<hr/>Fundraiser text:<br/>".stripslashes($p['post_content']).
        "<hr/>Spam check:<br/>".print_r($spam,TRUE).
        "<hr/>Stack trace:<br/>".stacktrace();

    SyiMailer::altsend(array(
      "recipient" => 'Administrator <' . get_email_address('outreach') . '>',
      "subject" => "New $theme fundraiser created",
      "mail_body" => "clean",
      "variables" => array( "content" => $email_content ),
    ));

    if ( ! $existing) {
      $profile_url = site_url('/members/'.urlencode($current_user->user_login));

      SyiMailer::altsend(array(
        'recipient' => $current_user->user_email,
        'subject'   => "Thank you for setting up your page on SeeYourImpact.org",
        'mail_body' => 'new_fundraiser',
        'header'    => array(
          'From'    => '"Jamie" <contact@seeyourimpact.org>',
          'syi-bcc' => get_email_address('outreach'),
        ),
        'variables' => array(
          'first_name' => $current_user->user_firstname,
          'profile_url' => $profile_url,
        ),
      ));
    }
  }

  return form_success( $url );
}

function end_campaign($id) {
  global $bp, $wpdb;

  $c = get_campaign_stats($id);
  if ($c == NULL)
    return;

  $post = get_post($id);

  $body = "<br/>User " . $bp->loggedin_user->userdata->user_login . " (#" . $bp->loggedin_user->id . ") " .
    "has archived $c->guid as c$id".
    "<hr/>Fundraiser title:<br/>$post->post_title".
    "<hr/>Fundraiser text:<br/>$post->post_content".
    "<hr/>Stack trace:<br/>".stacktrace();

  SyiMailer::altsend(array(
    "recipient" => "Administrator <".get_email_address('outreach').">",
    "subject" => "Campaign archived: $c->post_title",
    "mail_body" => "clean",
    "variables" => array( "content" => $body ),
  ));

  $postdata = array('ID' => $id, 'post_name' => "c$id");
  wp_update_post($postdata);

  $wpdb->update('campaigns', array(
    'archived' => 1,
    'public' => 0
  ), array('post_id' => $id));

  return TRUE;
}

function incrementCampaign($id)
{
    global $wpdb;

    $wpdb->query( $wpdb->prepare("UPDATE gift_campaigns SET status = status + 1 WHERE campaignID='%s'", $id) );
}

add_shortcode('campaign', 'campaign_func');

function refresh_campaign($id) {
  if (function_exists('wp_cache_post_change'))
    wp_cache_post_change($id);
}

function get_campaign_for_user($user_id) {
  global $wpdb;

  $id = intval($wpdb->get_var($wpdb->prepare(
    "SELECT post_id FROM campaigns WHERE owner = %d AND archived=0",
    $user_id)));

  return $id;
}

function get_campaign_permalink($event_id) {
  global $wpdb;
  $guid = $wpdb->get_var($wpdb->prepare(
    "SELECT guid FROM campaigns WHERE post_id=%d",
    $event_id));

  if (!$guid) {
    $guid = get_home_url(1, '/members/'.$wpdb->get_var($wpdb->prepare('
      select user_login from wp_users u
      inner join wp_1_posts p on p.post_author = u.id
      where p.id = %d', $event_id)));
  }

  return $guid;

/* WAS:
  // get_campaign_permalink_filter takes care of substituting author's profile page
  return get_post_permalink($event_id);
*/
}

function get_campaign_pledge_url($event_id) {
  return get_campaign_permalink($event_id) . "pledge";
}

function get_campaign_title($event_id) {
  $post = get_post($event_id, OBJECT);
  if ($post == NULL)
    return "Fundraiser";
  return $post->post_title;
}

function draw_pledge_button($args) {
  global $bp;

  extract(shortcode_atts(array(
    'label' => 'Donate',
    'href' => $bp->displayed_user->domain . 'pledge',
    'class' => ''
  ), $args));

  return '<a href="' . $href. '" class="' . $class . ' button orange-button big-button">' . xml_entities($label) . '</a>';
}
add_shortcode('pledge_button', 'draw_pledge_button');

// From a fundraiser ID, get the charity page it should replace
function get_campaign_charity($event_id) {
  if ($event_id == 8097)
    return 96; // ImpactIndia

  global $wpdb;
  $b = $wpdb->get_var($wpdb->prepare(
    "SELECT blog_id FROM charity c
    WHERE c.fundraiser = %d", $event_id));
  return $b;
}

// From a charity ID, get the fundraiser page it should display instead of the standard page
function get_campaign_for_charity($blog_id) {
  if ($blog_id == 96)
    return 8097; // Impact India

  global $wpdb;
  return $wpdb->get_var($wpdb->prepare(
    "SELECT fundraiser FROM charity c
    WHERE c.blog_id = %d", $blog_id));
}

function get_campaign_permalink_filter($link, $post, $leavename = false, $sample = false) {
  if ($post->post_type != 'event')
    return $link;

  $author = get_campaign_owner($post->ID);
  if ($author > 0)
    return bp_core_get_userlink($author, FALSE, TRUE);

  $blog_id = get_campaign_charity($post->ID);
  if ($blog_id > 0) {
    return get_site_url($blog_id, "/", "http");
  }

  return $link;
}
add_filter('post_link', 'get_campaign_permalink_filter', 0, 4);
add_filter('post_type_link', 'get_campaign_permalink_filter', 0, 4);

function draw_active_step($step, $active, $done, $label, $href) {
  $cls = "step";
  if ($step == $active)
    $cls .= " active";
  $available = ($step < $active);

  $label = xml_entities($label);
  if ($done) {
    $cls .= " done";
    if ($step != $active)
      $label = '<img src="' . __C('themes/syi/images/check.png') . '" class="check">' . $label;
  }
  $img = __C("images/{$step}circle.png");

  if ($available) {
    ?>
      <a href="<?=$href?>" class="<?= $cls ?> available"><img src="<?= $img ?>"><?= $label ?></a>
    <?
  } else {
    ?>
      <div class="<?= $cls ?>"><img src="<?= $img ?>"><?= $label ?></div>
    <?
  }
}

function get_campaign_action_links($id) {
  global $bp;

  $links = new stdClass;
  $links->id = $id;
  $links->view =get_member_link($bp->displayed_user->id, 'campaign');
  $links->update = get_member_link($bp->displayed_user->id, 'updates?new');
  $links->preview = $links->view . '?preview';
  $links->invite = $links->view . '?invite';
  $links->edit =get_member_link($bp->displayed_user->id, 'campaign', 'edit');

  return $links;
}

function draw_campaign_help($id, $step) {
  global $post;

  if ($id != -1) {
    if (!is_my_campaign($id) || !has_action('draw_campaign_help'))
      return;
  }

  $links = get_campaign_action_links($id);
  $has_invited = get_post_meta($id, "has_invited", true);

  ?>
  <div class="help-box cv2">
    <div class="steps">
      <?
      draw_active_step(1, $step, $step != 1, "Create your campaign", $links->edit);
      draw_active_step(2, $step, $has_invited, "Invite & Share", $links->invite);
      draw_active_step(3, $step, false, "See your impact!", $links->view); 
      ?>
    </div>

    <div class="help-text">
      <? do_action('draw_campaign_help', $links); ?>
    </div>
  </div>
  <?
}

function draw_campaign_help_message($msg, $campaign = NULL) {
  $lead = "You can ";
  $def = "";

  ob_start();
  switch ($msg) {
    case 'cart_empty':
      echo 'Your cart is empty.';
      $lead = "Make a donation, or ";
      break;
    case 'thankyou':
      $def = "Thank you for your donation!";
      $lead = "Now, you can: ";
      // fallthrough
    case 'activity':
      draw_my_campaign_activity($campaign, $def);
      $lead = "Now, you can: ";
      break;
  }
  $s = ob_get_contents();
  ob_end_clean();

  if (empty($s))
    return;

  ?><div class="help-box cv2"><div class="help-text"><?
  if (bp_is_my_profile()) {
    $links = get_campaign_action_links($campaign->id);
    draw_campaign_actions($s, $links, $lead);
  } else {
    ?><b><?= $s ?></b><? 
  }

  ?></div></div><?
}

function draw_campaign_actions($title, $links, $lead = "") {
  $actions[] = draw_invite_link("campaign/".encrypt($links->id), '', TRUE);
  $actions[] = '<a href="' . $links->update . '" class="button green-button">post an update</a>';
  $actions[] = '<a href="' . $links->edit . '" class="button green-button">edit this page</a>';
  $actions = apply_filters('campaign_actions', $actions);

  ?><b><?= $title ?></b><?
  if (count($actions > 0)) {
    echo $lead;
    echo comma_list($actions, "", " ");
  }
}

function preview_campaign_help($links) {
  draw_campaign_actions("How does it look?", $links);
}

function finished_campaign_help($links) {
  $l = (object)apply_filters('profile_tab_labels', array(
    'fundraiser' => 'fundraiser'));

  draw_campaign_actions("Your $l->fundraiser is up and running!", $links);
}

function invite_campaign_help($links) {
  $l = (object)apply_filters('profile_tab_labels', array(
    'fundraiser' => 'fundraiser'));

  draw_campaign_actions("Share your $l->fundraiser story!", $links);
}

function draw_my_campaign_activity($campaign, $default) {
  global $wpdb, $bp, $event_id;

  if (empty($bp->loggedin_user->id))
    return;

  $pledges = $wpdb->get_results($wpdb->prepare(
    "select *, NOW() as now from pledges where user_id=%d and event_id=%d",
    $bp->loggedin_user->id, $campaign->id));

  if (!empty($pledges)) {
    $today = date("M jS", strtotime($pledges[0]->now));

    foreach ($pledges as $pledge) {
      $activity = "pledged " . as_money(from_money($pledge->message));
      $activity .= " per $campaign->unit";
  /*
      $t = strtotime($pledge->date_updated);
      $t = date("M jS", $t);
      if ($t != $today)
        $activity .= " on $t";
  */
      $p[] = $activity;
    }
  }

  /*
    $donations = $wpdb->query($wpdb->prepare(
      "select 
  */ 

  if (count($p) > 0)
    echo "You " . comma_list($p) . ".  Thanks for your support!";
  else 
    echo $default;
}

function invite_button_sc($atts,$content='') {
  global $post;
  if($post->post_type=='event')  
  return draw_invite_link ("campaign/".encrypt($post->ID),$content,1);
  else return '';    
}

add_shortcode('invite_button','invite_button_sc');

function is_campaign_admin() {
  return current_user_can('edit_posts');
}

function is_my_campaign($id) {
  global $bp;
  if ($bp->loggedin_user->id == 0)
    return false;

  return $bp->loggedin_user->id == get_campaign_owner($id);
}

function can_manage_campaign($id) {
  return is_campaign_admin() || is_my_campaign($id);
}

function get_campaign_invitees($eid) {
  global $wpdb;
   
  $sql = $wpdb->prepare("SELECT DISTINCT name, email 
    FROM invite WHERE context=%s",
    'campaign/'.intval($eid));
  $invitees = $wpdb->get_results($sql);

  $return = array();  
  foreach ($invitees as $d) { 
    if(is_email($d->email)) 
      $return[$d->email] = $d->name; 
  }
  return $return;
}

function calculate_campaign_stats($eid) {
  global $wpdb;

  $sql = $wpdb->prepare("
    SELECT
      COUNT(dgid) as lives, 
      COUNT(DISTINCT(donorID)) as donors, 
      SUM(raised+offline) as raised,
      SUM(tip) as tip,
      SUM(offline) as offline,
      SUM(raised + tip + offline) as total 
    FROM((
    SELECT dg.ID as dgid, d.donationID, donor.firstName as firstName,
        dg.amount * COUNT(DISTINCT dg.ID) as raised,
        dg.tip * COUNT(DISTINCT dg.ID) as tip,
        0 as offline,
        IF(tg.id IS NULL, CONCAT('gave ', g.displayName),
          CONCAT('gave $',FORMAT(dg.amount,2),' for ',tg.displayName)) AS activity, 
        COUNT(DISTINCT dg.ID) AS qty, d.donationDate AS date, 
        donor.user_id, donor.ID as donorID, donor.data as donorData, 0 as datid, NULL as matched
    FROM donationGifts dg
    JOIN donation d ON d.donationID = dg.donationID
    JOIN donationGiver donor ON donor.ID = d.donorID
    JOIN payment p ON d.paymentID = p.id
    LEFT JOIN gift g ON dg.giftID = g.ID 
    LEFT JOIN gift tg ON (g.towards_gift_id=tg.id 
      AND g.varAmount=1 AND g.unitAmount=1) 
    LEFT JOIN donationAcctTrans dat ON 
      dat.paymentID = p.id AND p.provider = 5
    LEFT JOIN donationAcct da ON dat.donationAcctId = da.id 
    WHERE IFNULL(d.test,0) = 0
      AND dg.event_id = %d
      AND dg.matchingDonationAcctTrans=0
      AND (da.id IS NULL OR (da.donationAcctTypeId != 4 
        AND da.event_id != dg.event_id)) 
      AND d.donationAmount_Total > 0
    GROUP BY d.donationID, g.ID, dg.matchingDonationAcctTrans
  ) UNION (

    SELECT 0 as dgid, d.donationID AS donationID, donor.firstName,
        IFNULL(dat.amount, 0) * (p1.amount/(p1.amount+p1.tip)) as raised,
        IFNULL(dat.amount, 0) * (p1.tip/(p1.amount+p1.tip)) as tip,
        0 as offline,
        '' AS activity, 
        1 AS qty, dat.dateInserted AS date, donor.user_id, 
        donor.ID as donorID, dat.ID as datid, donor.data as donorData, daMatch.id as matched 
    FROM donationAcct da
    JOIN donationAcctTrans dat ON dat.donationAcctId = da.id AND dat.amount > 0
    LEFT JOIN payment p1 ON p1.id=dat.paymentID
    LEFT JOIN donation d on d.paymentID=p1.ID
    JOIN donationGiver donor ON da.owner = donor.ID
    LEFT JOIN wp_1_posts wp ON wp.ID = da.event_id 
    LEFT JOIN donationAcctTrans dat2 ON dat2.paymentID=p1.id AND dat2.donationAcctId != da.id AND dat2.amount < 0
    LEFT JOIN donationAcct da2 on dat2.donationAcctId = da2.id
    LEFT JOIN donationAcct daMatch on dat2.donationAcctId = daMatch.id AND daMatch.donationAcctTypeId=4
    WHERE IFNULL(d.test,0) = 0
      AND da.event_id = %d
      AND d.donationID > 0 
      AND NOT (dat.note LIKE '%%efunded%%')
      AND NOT (IFNULL(da2.donationAcctTypeId,0) = 7 and da2.event_id = da.event_id) 
      AND da.donationAcctTypeId > 2
  ) UNION (
    SELECT 0 as dgid, 0 as donationID, NULL as firstName,
      0 as raised, 0 as tip, fr.offline,
      '' AS activity, 1 as qty, NULL as date, NULL as user_id,
      NULL as donorID, NULL as datid, NULL as donorData, NULL as matched
    FROM campaigns fr
    WHERE fr.post_id=%d AND offline > 0
  )) AS dg_rows",
   $eid, $eid, $eid);


/*

  Old calculation left here in case there is wisdom in it.
  Was replaced by above so that at least it would be in sync with activity widget.

    SELECT
      COUNT(dgid) as lives, 
      COUNT(DISTINCT(donorID)) as donors, 
      SUM(raised) as raised,
      SUM(tip) as tip,
      SUM(raised + tip) as total 
    FROM(
      (SELECT 
        dg.ID as dgid, 
        d.donorID as donorID, 
        dg.amount * COUNT(DISTINCT dg.ID) as raised, 
        dg.tip * COUNT(DISTINCT dg.ID) as tip,
        0 as trans_id
      FROM donationGifts dg
      JOIN donation d ON d.donationID = dg.donationID
      JOIN donationGiver donor ON donor.ID = d.donorID
      JOIN payment p ON d.paymentID = p.id
      LEFT JOIN gift g ON dg.giftID = g.ID 
      LEFT JOIN gift tg ON (g.towards_gift_id = tg.id AND g.varAmount = 1 AND g.unitAmount = %d) 
      LEFT JOIN donationAcctTrans dat ON dat.paymentID = p.id AND p.provider = 5
      LEFT JOIN donationAcct da ON dat.donationAcctId = da.id 
      WHERE dg.event_id=%d AND d.test=0 
        -- AND dg.matchingDonationAcctTrans=0 
        AND (da.id IS NULL OR (da.donationAcctTypeId != 4 AND da.event_id != dg.event_id))
      GROUP BY d.donationID, g.ID, dg.matchingDonationAcctTrans
      ) UNION (
        SELECT
          IFNULL(dg.ID,0) as dgid,
          da.donorID as donorID,
          IFNULL(dat.amount, 0) * (p1.amount/(p1.amount+p1.tip)) as raised,
          IFNULL(dat.amount, 0) * (p1.tip/(p1.amount+p1.tip)) as tip,
          dat.id as trans_id
        FROM donationAcct da
        JOIN donationAcctTrans dat ON dat.donationAcctId = da.id AND dat.amount > 0
        LEFT JOIN payment p1 ON p1.id=dat.paymentID
        LEFT JOIN donationAcctTrans dat2 ON dat2.donationAcctId = da.id
        LEFT JOIN payment p ON dat2.paymentID = p.id AND p.provider = 5
        LEFT JOIN donation d ON d.paymentID = p.id
        LEFT JOIN donationGifts dg ON d.donationID = dg.donationID
        WHERE da.event_id = %d AND IFNULL(dg.ID,0) = 0
        GROUP BY da.id, dg.ID
    )) AS dg_rows", AVG_UNIT_AMOUNT, $eid, $eid);
*/

  if ($_REQUEST['sql'] == "yes")
    pre_dump($sql);
    
  $stats = $wpdb->get_row($sql);

  $stats->pledge_count = $wpdb->get_var($wpdb->prepare(
    "select count(*) from pledges where event_id=%d",
    $eid));
  $stats->supporters_count = count(get_supporters($eid));

  return $stats;    
}

function get_campaign_donors($eid,$id_only=false) {
  return get_campaign_activities($eid,0,
    ($id_only?'donor_ids':'donor_emails'));
}

function get_pledge_activities($event_id=0) {
  global $wpdb;
  $sql = $wpdb->prepare("SELECT 0 AS donationID,p.visitor_id as vid, p.name,
    p.message as displayName,
    p.name as firstName,
    1 AS qty, p.date_created AS date, p.user_id
    FROM pledges p WHERE p.event_id=%d AND p.status='promised'
    ORDER BY p.date_updated DESC",$event_id);

  return $wpdb->get_results($sql);

}

function should_include_tips($eid) {
  // Two exceptions
  if ($eid == 7918 || $eid == 7849)
    return true;

  if (empty($eid))
    return false;

  return $eid < 7789;
}

function compare_activities_dates($a,$b) {
  $date_a = strtotime($a->date);
	$date_b = strtotime($b->date);
	if($date_a == $date_b) return 0;
	return ($date_a > $date_b) ? -1 : 1;
}

function get_campaign_activities($event_id, $limit, $type='') {    
	$pledges = get_pledge_activities($event_id);
  foreach ($pledges as $pledge) {
    if ($pledge->user_id > 0)
      $pledge->pid = "{$pledge->user_id}_";
    else
      $pledge->pid = "0_{$pledge->vid}";
    $pledge->activity = 'pledged <span class="pledge-amount">' . $pledge->displayName . ' per book</span>';
  }

	$activities = get_donation_activities(1, $event_id, $limit, $type);
  if(is_array($pledges) && !empty($pledges)) {
    if(is_array($activities) && !empty($activities)) {
      $activities = array_merge($activities,$pledges);
		  uasort($activities,'compare_activities_dates');
		} else {
		  $activities = $pledges;
		}
	}

  return $activities;
}

function get_donation_activities($blog_id=1, $id=0, $limit='', $type='') {
  global $wpdb;  
  $avg_unit_amt = AVG_UNIT_AMOUNT;

  if($blog_id > 1) {
    $donation_where = $wpdb->prepare(
      "AND dg.blog_id = %d",
      $blog_id);
    $giveany_where = $wpdb->prepare(
      "AND da.blogId = %d",
      $blog_id);
    if ($id > 0) {
      $donation_where .= $wpdb->prepare(
        " AND dg.story = %d", $id);
    }
    $offline_where = $wpdb->prepare('fr.post_id = %d', $id);
  }
  else if($id > 0) {
    $donation_where = $wpdb->prepare(
      "AND dg.event_id = %d
      AND dg.matchingDonationAcctTrans=0
      AND (da.id IS NULL OR (da.donationAcctTypeId != 4 
        AND da.event_id != dg.event_id))",
      $id);
    $offline_where = $wpdb->prepare('fr.post_id = %d', $id);
    $giveany_where = $wpdb->prepare('AND da.event_id = %d', $id);
  } else if (!empty($id)) { // Use it as a theme
    // Get campaign donation summary
    $giveany_where = $wpdb->prepare("AND c.theme = %s", $id);
    $donation_where = "$giveany_where 
      AND dg.matchingDonationAcctTrans=0
      AND (da.id IS NULL OR (da.donationAcctTypeId != 4
        AND da.event_id != dg.event_id))";
    $offline_where = $wpdb->prepare('fr.theme = %s', $id);
  }   

  switch ($type) {
    case "donor_ids":
    case "donor_emails":
      $fields = "name, email, user_id, ID";
      $donation_fields = "DISTINCT 
        CONCAT(donor.firstName, ' ', donor.lastName) 
        AS name, donor.email, donor.user_id, donor.ID";                        
      $giveany_fields = $donation_fields;
      $order = "";
    break;    
    default:
      $fields = "donationID, anonymous, firstName, lastName, SUM(raised) as raised, SUM(tip) as tip, SUM(offline) as offline, varAmount, activity, SUM(qty) as qty, date, user_id, donorID, data, datid, matched";
      $donation_fields = "d.donationID, c.post_id as fr_id, d.anonymous, donor.firstName as firstName,
        donor.lastName as lastName,
        dg.amount * COUNT(DISTINCT dg.ID) as raised,
        dg.tip * COUNT(DISTINCT dg.ID) as tip,
        0 as offline, g.varAmount,
        IF((dg.amount * COUNT(DISTINCT dg.ID)) = g.unitAmount,
          CONCAT('gave ', g.displayName),
          CONCAT('gave $',FORMAT(dg.amount * COUNT(DISTINCT dg.ID),0),' for ',IFNULL(tg.displayName,g.displayName))) AS activity, 
        COUNT(DISTINCT dg.ID) AS qty, d.donationDate AS date, 
        donor.user_id, donor.ID as donorID, donor.data, 0 as datid, NULL as matched";    
      $giveany_fields = "d.donationID AS donationID, c.post_id as fr_id, d.anonymous, donor.firstName,
        donor.lastName as lastName,
        IFNULL(dat.amount, 0) * (p1.amount/(p1.amount+p1.tip)) as raised,
        IFNULL(dat.amount, 0) * (p1.tip/(p1.amount+p1.tip)) as tip,
        0 as offline, 1 as varAmount,
        '' AS activity, 
        0 AS qty, dat.dateInserted AS date, donor.user_id, 
        donor.ID as donorID, dat.ID as datid, donor.data, daMatch.id as matched";
      if (!empty($offline_where))
        $offline = " UNION (SELECT 0 as donationID, fr.post_id as fr_id, 0 as anonymous, NULL as firstName, NULL as LastName,
          0 as raised, 0 as tip, fr.offline, 1 as varAmount,
          NULL as activity, 0 as qty, NULL as date, NULL as user_id,
          NULL as donorID, NULL as datid, NULL as data, NULL as matched
          FROM campaigns fr WHERE $offline_where AND fr.offline > 0)";
      $order = "ORDER BY date DESC";
    $break;
  }
  
  if ($limit>0)
    $limit = "LIMIT ".intval($limit); 
  else 
    $limit = '';

// da is the payment donation account
// da2 is the matching donation account

  $sql = "
    SELECT $fields FROM
  ((
    SELECT $donation_fields 
    FROM donationGifts dg
    JOIN donation d ON d.donationID = dg.donationID
    JOIN donationGiver donor ON donor.ID = d.donorID
    JOIN payment p ON d.paymentID = p.id
    LEFT JOIN gift g ON dg.giftID = g.ID 
    LEFT JOIN gift tg ON (g.towards_gift_id=tg.id 
      AND g.varAmount=1 AND g.unitAmount=$avg_unit_amt) 
    LEFT JOIN donationAcctTrans dat ON 
      dat.paymentID = p.id AND p.provider = 5
    LEFT JOIN donationAcct da ON dat.donationAcctId = da.id 
    LEFT JOIN campaigns c ON c.post_id = dg.event_id AND dg.event_id > 0
    WHERE IFNULL(d.test,0) = 0 AND NOT d.anonymous
      $donation_where 
      AND d.donationAmount_Total > 0
    GROUP BY d.donationID, g.ID, dg.matchingDonationAcctTrans
  ) UNION (

    SELECT $giveany_fields 
    FROM donationAcct da
    JOIN donationAcctTrans dat ON dat.donationAcctId = da.id AND dat.amount > 0
    LEFT JOIN payment p1 ON p1.id=dat.paymentID
    LEFT JOIN donation d on d.paymentID=p1.ID
    JOIN donationGiver donor ON da.owner = donor.ID
    LEFT JOIN wp_1_posts wp ON wp.ID = da.event_id 
    LEFT JOIN donationAcctTrans dat2 ON dat2.paymentID=p1.id AND dat2.donationAcctId != da.id AND dat2.amount < 0
    LEFT JOIN donationAcct da2 on dat2.donationAcctId = da2.id
    LEFT JOIN donationAcct daMatch on dat2.donationAcctId = daMatch.id AND daMatch.donationAcctTypeId=4
    LEFT JOIN campaigns c ON c.post_id = da.event_id AND da.event_id > 0
    WHERE IFNULL(d.test,0) = 0 AND NOT d.anonymous
      $giveany_where
      AND d.donationID > 0 
      AND NOT dat.note LIKE '%efunded%'
      AND NOT (IFNULL(da2.donationAcctTypeId,0) = 7 and da2.event_id = da.event_id) 
      AND da.donationAcctTypeId > 2
  ) $offline) AS t
  GROUP BY t.donationID,t.activity
  $order $limit"; 

  if ($_REQUEST['sql'] == 'yes') pre_dump($sql);
  $results = $wpdb->get_results($sql);

  if ($type=='donor_ids'||$type=='donor_emails') {            
    $return = array();  
    foreach ($results as $d) { 
      if($type=='donor_ids') 
        $return[] = $d->ID;
      else if(is_email($d->email)) 
        $return[$d->email] = $d->name;         
    }
    return $return;                    
  } 
  
  return $results;
}

function draw_campaign_stats_email($event_id) {
}

function log_invite_visit($id) {
  global $wpdb;
  $sql = $wpdb->prepare ("UPDATE invite SET visited = NOW() WHERE visited IS NULL AND id=%d ",$id);
  $wpdb->query($sql);
}

function set_event_cookie($id) {
  unset($_COOKIE['eid']);
  $setcookie = setcookie('eid', $id, time()+3600, '/',
    ".".str_replace(array("https://","http://","/"),"", SITE_URL));
}

function get_campaign_stats($id) {
  $campaign = FundraiserApi::getOne($id, array( 'view' => 'stats'));
  return apply_filters('get_campaign_stats', $campaign);
}

// Pass in a fundraiser object OR an ID
function get_fr_tags($fr) {
  global $wpdb;

  if (is_numeric($fr))
    $fr = FundraiserApi::getOne($fr);

  // Does this fundraiser override with tags?
  if (!empty($fr->tags))
    return $fr->tags;

  // Otherwise grab the tag from its campaign (if any)
  $campaign = CampaignApi::getOne(array( 'name' => $fr->theme ));
  if ($campaign == NULL)
    return $fr->theme; // Default tag: the theme

  $tag = eor($campaign->tag, $campaign->name);
  return $tag;
}

function update_campaign_stats($id, $debug=false, $return_update=false) {
  global $wpdb;
  if(empty($id)) 
    return; 

  $db_stats = calculate_campaign_stats($id); // this is the longer process

  // Update to current values stored in post
  $p = get_blog_post(1, $id);
  $db_stats->public = has_tag('public',$p) && !has_tag('private',$p);
  $db_stats->featured = has_tag('featured',$p) && !has_tag('private',$p);
  $db_stats->post_title = $p->post_title;
  $db_stats->post_name = $p->post_name;
  $db_stats->guid = str_replace('https:','http:',get_campaign_permalink($id));

  // URL for campaign's default fundraiser is that campaign's URL
  $c = CampaignApi::getOne(array( 'fr_id' => $id ));
  if ($c !== NULL) {
    $db_stats->guid = $c->url;
  }

  $db_stats->last_donated = '0000-00-00 00:00:00';
  $last_activity = get_campaign_activities($id, 1);
  if (is_array($last_activity) && !empty($last_activity[0]->date)) {
    $db_stats->last_donated = $last_activity[0]->date;
  }

  $update = TRUE;
  if ($debug) {
    $wrong = array();

    $fr = get_campaign_stats($id);

    if (intval($fr->gifts_count) != intval($db_stats->lives)) $wrong[] = 'gifts_count';
    if (intval($fr->donors_count) != intval($db_stats->donors)) $wrong[] = 'donors_count';
    if (intval($fr->supporters_count) != intval($db_stats->supporters_count)) $wrong[] = 'supporters_count';
    if (floatval($fr->raised) != floatval($db_stats->raised)) $wrong[] = 'raised';
    if (floatval($fr->tip) != floatval($db_stats->tip)) $wrong[] = 'tip';
    if ($fr->last_donated != $db_stats->last_donated) $wrong[] = 'last_donated';
    if ($fr->public != $db_stats->public) $wrong[] = 'public';
    if ($fr->featured != $db_stats->featured) $wrong[] = 'featured';
    if (''.$fr->title != ''.$db_stats->post_title) $wrong[] = 'post_title';
    if ($fr->post_name != $db_stats->post_name) $wrong[] = 'post_name';
    if ($fr->guid != $db_stats->guid) $wrong[] = 'guid';
    if ($fr->pledge_count != $db_stats->pledge_count) $wrong[] = 'pledge_count';
		
    if (count($wrong) == 0)
      $update = FALSE;
    else {
      $debug_msg = get_campaign_permalink($id) . "\nRECORDED: \n".print_r($fr,TRUE).
        "\nGENERATED: \n".print_r($db_stats,TRUE)."\n".print_r($db_stats, TRUE)."\nUPDATE: ". implode(',', $wrong);
      debug($debug_msg, 1, "DEBUG CAMPAIGN #$id STATS WRONG");
    }
  }

  if ($update) {
    $wpdb->query($sql = $wpdb->prepare(
      "INSERT INTO campaigns 
      (post_id, donors_count, gifts_count, raised, tip, last_donated,
      public, featured, post_title, post_name, guid, pledge_count, supporters_count) 
      VALUES (%d,%d,%d,%f,%f,%s,%d,%d,%s,%s,%s,%d,%d)
      ON DUPLICATE KEY UPDATE 
      donors_count=%d, gifts_count=%d, raised=%f, tip=%f, last_donated=%s,
      public=%d, featured=%d, post_title=%s, post_name=%s, guid=%s, pledge_count=%d,
      supporters_count=%d",
      $id, 

      $db_stats->donors, $db_stats->lives, $db_stats->raised, $db_stats->tip,
      $db_stats->last_donated,
      $db_stats->public, $db_stats->featured, $db_stats->post_title, 
      $db_stats->post_name, $db_stats->guid, $db_stats->pledge_count,
      $db_stats->supporters_count,

      $db_stats->donors, $db_stats->lives, $db_stats->raised, $db_stats->tip,
      $db_stats->last_donated,
      $db_stats->public, $db_stats->featured, $db_stats->post_title, 
      $db_stats->post_name, $db_stats->guid, $db_stats->pledge_count,
      $db_stats->supporters_count));

    if ($return_update) 
      return print_r($db_stats,1); 
  }

// update public, featured
// update post_title, post_name, guid

}

function get_supporters($fr_id, $count_only = FALSE) {
  global $wpdb;

  // Note from Steve:
  // What the hell is this mess?  It's what we have to do to get an accurate
  // donor list until we build a better donor_activity table
  //
  // merge donor list and invite list to get supporter list

  $context = "campaign/$fr_id";
  $supps = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM ((
      SELECT 
        donor.email, CONCAT(donor.firstName,' ',donor.lastName) as name, 'donor' as role
      FROM donationGifts dg
      JOIN donation d ON d.donationID = dg.donationID
      JOIN donationGiver donor ON donor.ID = d.donorID
      JOIN payment p ON d.paymentID = p.id
      LEFT JOIN gift g ON dg.giftID = g.ID
      LEFT JOIN gift tg ON (g.towards_gift_id=tg.id
        AND g.varAmount=1 AND g.unitAmount=1)
      LEFT JOIN donationAcctTrans dat ON
        dat.paymentID = p.id AND p.provider = 5
      LEFT JOIN donationAcct da ON dat.donationAcctId = da.id
      WHERE IFNULL(d.test,0) = 0
        AND dg.event_id = %d
        AND dg.matchingDonationAcctTrans=0
        AND (da.id IS NULL OR (da.donationAcctTypeId != 4
          AND da.event_id != dg.event_id))
        AND d.donationAmount_Total > 0
      GROUP BY d.donationID, g.ID, dg.matchingDonationAcctTrans
    ) UNION (
      SELECT
        donor.email, CONCAT(donor.firstName,' ',donor.lastName) as name, 'donor' as role
      FROM donationAcct da
      JOIN donationAcctTrans dat ON dat.donationAcctId = da.id AND dat.amount > 0
      LEFT JOIN payment p1 ON p1.id=dat.paymentID
      LEFT JOIN donation d on d.paymentID=p1.ID
      JOIN donationGiver donor ON da.owner = donor.ID
      LEFT JOIN wp_1_posts wp ON wp.ID = da.event_id
      LEFT JOIN donationAcctTrans dat2 ON dat2.paymentID=p1.id AND dat2.donationAcctId != da.id AND dat2.amount < 0
      LEFT JOIN donationAcct da2 on dat2.donationAcctId = da2.id
      LEFT JOIN donationAcct daMatch on dat2.donationAcctId = daMatch.id AND daMatch.donationAcctTypeId=4
      WHERE IFNULL(d.test,0) = 0
        AND da.event_id = %d
        AND d.donationID > 0
        AND NOT (IFNULL(da2.donationAcctTypeId,0) = 7 and da2.event_id = da.event_id)
        AND da.donationAcctTypeId > 2
    ) UNION (
      SELECT
        email,name,'invited' as role
      FROM invite
      WHERE
        context like %s
    )) as d
    WHERE d.email != ''
    GROUP BY d.email",
    $fr_id, $fr_id, $context));

  return $supps;
}

function leaderboard_shortcode($atts, $content=null, $code="") {
  return ''; // Steve: killed non-team leaderboards
}
add_shortcode('leaderboard', 'leaderboard_shortcode');

function draw_team_leaderboard($teams, $atts = NULL) {
  if (count($teams) <= 0)
    return;

  if ($atts == NULL)
    $atts = array();

  $s = "<div class=\"box-model leaderboard\">";

  $title = $atts['title'];
  if (!empty($title)) {
    $s .= "<div>$title</div>";
  }

  $me = $atts['me'];
  $limit = eor($atts['limit'], 100);

  $i = 0;
  foreach ($teams as $team) {
    $i++;
    $r = round($team->raised);
    $max = eor($max,$r);
    if ($r == 0 || $max == 0)
      $w = 0;
    else
      $w = pct($r, $max);
    if ($w < 5) $w = 5;
    if ($w > 100) $w = 100;
    $total += $r;

    $is_me = $team->team == $me;
    if (!$is_me && $i > $limit)
      continue;

    $isme = $is_me ? "is-me" : "";
    $stats = array();
    $stats[] = "<span class=\"amount\">$" . $r . "</span>";
    if ($atts['donors'] && ($team->donors > 0))
      $stats[] = plural($team->donors, "donor");
    $raised = implode(" - ", $stats);

    $href = empty($team->team_url) ? "" : "href=\"{$team->team_url}\"";
    $s .= "<div class=\"team team-{$team->team} row-$i $isme\" id=\"team-{$team->team}\"><a $href class=\"progress\" style=\"width:$w%;\"></a>";
    $s .= "<label>";
    $s .= "<a $href class=\"name\">" . xml_entities($team->team_title) . "</a>";
    $s .= "<span class=\"raised\">" . $raised . "</span></div>";
    $s .= "</label>";
  }

  if ($total > 0)
    $s .= "<span class=\"total\">$" . $total . " raised so far!</span>";

  return $s . "</div>";
}

function is_themeless_campaign($id) {
  return $id < 7354 && $id > 0;
}

function load_custom_skin($campaign = NULL) {
  global $event_theme, $post, $event_id, $bp, $NO_SHARING;

  $event_theme = trim($_REQUEST['theme']);
  if (empty($event_theme)) {
    $c = get_campaign_stats($event_id);
    if ($c == null)
      return;

    $event_theme = $c->theme;
  }

  setup_custom_skin($event_theme, !is_themeless_campaign($event_id));
}

function setup_custom_skin($theme, $load_default = TRUE) {
  global $event_theme, $NO_SHARING;

  $event_theme = trim($theme);
  add_action('wp_head', 'draw_custom_skin', 100);
  sharing_init(!$NO_SHARING);

  if (empty($event_theme))
    return;

  $file = ABSPATH . "themes/$event_theme.php";

  if (file_exists($file)) {
    include_once($file);
    load_theme_contents();
  }
  else if ($load_default) {
    include_once( ABSPATH . "/themes/default.php" );
    load_theme_contents();
  }

  global $context;
  if (is_showing($context->campaign_page, 'banner')) {
    global $header_file;
    $header_file = "branded";
    add_action('branded_header', 'draw_campaign_banner');
  }

}

function draw_custom_skin() {
  global $event_theme;
  if (empty($event_theme))
    return;

  do_action('campaign_custom_css');

  $file = ABSPATH . "themes/$event_theme.css";
  if (file_exists($file)) {
    ?><style><?
    include_once($file);
    ?></style><?
  }
}

function get_donation_campaign($donation_id, $gift_id=0) {
  global $wpdb;

  if ($gift_id == 0)
    return $wpdb->get_var($wpdb->prepare("SELECT event_id FROM donationGifts 
      WHERE donationID=%d AND giftID>0 AND event_id>0 LIMIT 1 ", $donation_id));    
  else if ($gift_id == 50)       
    return $wpdb->get_var($wpdb->prepare("SELECT da.event_id FROM donation d
      LEFT JOIN donationAcctTrans dat ON d.paymentID = dat.paymentID
      LEFT JOIN donationAcct da ON dat.donationAcctID = da.id WHERE d.donationID=%d
      LIMIT 1 ", $donation_id));
  else 
    return $wpdb->get_var($wpdb->prepare("SELECT event_id FROM donationGifts 
      WHERE donationID=%d AND giftID=%d AND event_id>0 LIMIT 1 ", 
      $donation_id, $gift_id));
}

function save_campaign_stats($post_id) {
  global $blog_post, $wpdb;
  if ($blog_post>1) return;
  $p = get_blog_post(1,$post_id);
  if ($p->post_type == 'event') {
    update_campaign_stats($post_id);
  }
}

add_action('publish_post', 'save_campaign_stats',9999,1);
add_action('save_post', 'save_campaign_stats',9999,1);
add_action('edit_post', 'save_campaign_stats',9999,1);
add_action('pending_post', 'save_campaign_stats',9999,1);



function sharing_init($enable = FALSE) {
  global $CEVHER_SHARING;

  if ($enable && function_exists('cevhershare_init')) {
    if ($CEVHER_SHARING === FALSE) {
      $CEVHER_SHARING = TRUE;
      cevhershare_init();
      add_action('wp_head', 'cevhershare_header');
    }
  } else {
    if ($CEVHER_SHARING !== FALSE) { 
      $CEVHER_SHARING = FALSE;
      remove_action('init', 'cevhershare_init');
      remove_filter('the_content', 'cevhershare_auto');
      remove_action('wp_head', 'cevhershare_header');
      wp_dequeue_script('cevhershare');
    }
  }
}
add_action('init', 'sharing_init', -1);

function draw_sharing_vertical() {
  global $CEVHER_SHARING;

  if (!$CEVHER_SHARING)
    return;

  cevhershare();
}
function draw_sharing_horizontal() {
  global $CEVHER_SHARING;

  if (!$CEVHER_SHARING)
    return;

  cevhershare_horizontal();
}

function bold_shortcode($attr, $content = NULL) {
  return '<b>' . do_shortcode($content) . '</b>';
}
add_shortcode('bold', 'bold_shortcode');

function strike_shortcode($attr, $content = NULL) {
  return '<strike>' . do_shortcode($content) . '</strike>';
}
add_shortcode('strike', 'strike_shortcode');

function italic_shortcode($attr, $content = NULL) {
  return '<i>' . do_shortcode($content) . '</i>';
}
add_shortcode('italic', 'italic_shortcode');

// update permalink for campaigns table

function update_campaign_guid($guid, $where="0") {
  global $wpdb;
  $wpdb->query($sql = $wpdb->prepare(
    "UPDATE campaigns SET guid=%s WHERE $where LIMIT 1", $guid));
  //debug($sql,true);
}


/*
add_action('wpmu_delete_user','delete_campaign_owner',999,1); // campaign owner deleted\
function delete_campaign_owner($user_id) {
  global $wpdb;
  $site_url = site_url();  
  $u = get_userdata($user_id);
  if (!empty($u)) { 
    $guid = $site_url.'members/'.$u->user_login.'/';  
	$post_id = $wpdb->get_var($sql = $wpdb->prepare(
	  "SELECT post_id FROM campaigns WHERE guid=%s",$guid));
	if (!empty($post_id)) {	  
	  $p = get_post($post_id);
	  if(!empty($p)) {
		$guid = site_url().'/support/'.$p->post_name.'/';
		update_campaign_guid($guid,"post_id=".intval($post_id));
	  }
	}
  }
}
*/

add_action('username_changed','update_campaign_owner_url',99,2); // profile (login) changed

function update_campaign_owner_url($user, $old_login) {
  $uid = get_campaign_for_user($user->ID);
  if ($uid > 0)
    update_campaign_guid(get_member_link($user->ID), $uid);
}

function draw_campaign_badge($c, $readonly = FALSE) {
  $cct = pct($c->raised, $c->goal);
  $raised = as_money($c->raised, '$%.0n');

  $tag = $readonly ? "span" : "a";

?>
  <<?=$tag?> class="campaign-box small-campaign item" href="<?=$c->guid;?>" target="_new">
    <span class="icon"><?=fundraiser_image_src($c->post_id, 75,90);?></span>
    <span class="text">
      <span class="progress" style="border-radius: 0 6px 6px 0; margin: -5px 0 5px -10px;
        width:100%; display:block; background:#ddd; position: relative; border: 1px solid #eee">
        <span class="progress-bar" style="width: <?=$cct?>%; background:#87C442; height: 10px;
          display:block; border-radius: 0 4px 4px 0;"></span></span>
      <span class="title"><?=$c->post_title;?></span>
      <span class="stats" style="font-size:75%; color: #666;">
        <span class="raised"><?= $raised ?> raised</span>
        <? if ($c->donors_count > 0) { ?>
          -
          <span><?= plural($c->donors_count, 'supporter') ?></span>
        <? } ?>
      </span>
    </span>
  </<?=$tag?>>
<?
}

function pct($a,$b) {
  if ($b == 0)
    return 100;
  $pct = round($a * 100 / $b);
  if ($pct < 0) $pct = 0;
  if ($pct > 100) $pct = 100;
  return $pct;
}

function get_campaign_appear_as($id) {
  global $wpdb;

  return $wpdb->get_var($sql = $wpdb->prepare(
    "SELECT meta_value FROM wp_1_postmeta
     WHERE post_id = %d AND meta_key='syi_appear-as'",
     $id));
}

function get_campaign_owner_name($id) {
  $user_id = get_campaign_owner($id);
  if ($user_id > 1)
    return get_displayname($user_id);
  
  $name = get_campaign_appear_as($id);
  return $name;
}

function fundraiser_widget($args, $content = NULL) {
  $p = (object)$args;

  // Use a provided avatar image or get avatar from (user_id, campaign owner)
  $c = get_campaign_stats($p->id);
  $user_id = eor($p->user_id, $c->owner);
  if (empty($p->avatar))
    $p->avatar = get_avatar_url($user_id, 50);
  else {
    $p->avatar = image_src($p->avatar, 50, 50);
  }

  // Size the main image
  if (empty($p->size))
    $p->size = "200x150";
  $p->img = image_src($p->img, $p->size);

  if (empty($p->title))
    $p->title = get_campaign_owner_name($p->id);
  if (empty($p->name))
    $p->name = get_displayname($user_id);
  if (empty($p->img))
    $p->img = fundraiser_image_src($p->id, 150,180);

  $p->href = $c->guid;
  $p->pay_link = pay_link(CART_GIVE_ANY);
  $p->content = $content;
  $p->action = eor($p->action, "");

  render_client_template('fundraiser', $p);
}

function fundraiser_shortcode($args, $content) {
  return shortcode_widget('fundraiser_widget', $args, $content);
}
add_shortcode('fundraiser','fundraiser_shortcode');

# http://staging.url2png.com/docs/v6.php
# usage
# $options['force']     = 'false';      # [false,always,timestamp] Default: false
# $options['fullpage']  = 'false';      # [true,false] Default: false
# $options['thumbnail_max_width'] = 'false';      # scaled image width in pixels; Default no-scaling.
# $options['viewport']  = "1280x1024";  # Max 5000x5000; Default 1280x1024
function url2png($url, $args=array()) {
  $URL2PNG_APIKEY = "P4FA9FEB7E15C2";
  $URL2PNG_SECRET = "S8626E98DF37F9";

  # urlencode request target
  $options['url'] = urlencode($url);

  $options += $args;

  # create the query string based on the options
  foreach($options as $key => $value) { $_parts[] = "$key=$value"; }

  # create a token from the ENTIRE query string
  $query_string = implode("&", $_parts);
  $TOKEN = md5($query_string . $URL2PNG_SECRET);

  return "http://beta.url2png.com/v6/$URL2PNG_APIKEY/$TOKEN/png/?$query_string";
}

function fundraiser_thumbnail_widget($args) {
  global $event_theme;

  if (is_array($args))
    extract($args);

  $width = eor($width, 200);
  $opts = array(
    'thumbnail_max_width' => $width,
    'viewport' => '990x1200',
    'fullpage' => TRUE,
  );

  $theme = eor($theme, $event_theme, 'default');

  $page = apply_filters('fundraiser_thumbnail', "members/ryang");
  $page = add_query_arg(array(
    'theme' => $theme,
    'NOHDR' => '1.0',
    'timestamp' => intval(time() / 300),  # force the url that url2png sees to change every 5 minutes
  ), $page);

  $src = url2png( "seeyourimpact.org/$page", $opts);

  ?><img class="fundraiser-thumbnail" src="<?=$src?>" width="<?= $width ?>" /><?
}
function fundraiser_thumbnail_shortcode($args, $content) {
  return shortcode_widget('fundraiser_thumbnail_widget', $args, $content);
}
add_shortcode('fundraiser_thumbnail','fundraiser_thumbnail_shortcode');






// BEGIN FUNDRAISER API

function get_fundraiser_stories_where($args) {
}


// translates the data in JSON into what the global $TEMPLATE understands
function load_theme_contents() {
  global $TEMPLATE, $context;

  if (!isset($TEMPLATE)) {
    $TEMPLATE = new stdClass;
  }

  if (!$TEMPLATE->theme) {
    $TEMPLATE->theme = 'default';
  }

  $context = $campaign = CampaignApi::getOne(array(
    'name' => $TEMPLATE->theme,
    'view' => 'gallery' // full inclusion of content panels
  ));

  if ($campaign !== NULL)
    $TEMPLATE = (object)array_merge((array)$TEMPLATE, (array)$campaign);

  return $TEMPLATE;
}


function draw_campaign_banner($context = NULL) {
  if (!empty($context->campaign_url)) {
    ?><a href="<?= esc_url($context->campaign_url) ?>" class="branded-tab-bar"><?
  }
  draw_gallery_part($context->gallery['campaign_banner'], "campaign_banner");
  if (!empty($context->campaign_url)) {
    ?></a><?
  }
}





function init_events() {
  $labels = array(
    'name' => _x('Fundraisers','general name'),
    'singular_name' => _x('Fundraiser','singular name'),
    'add_new' => _x('Add New', 'event'),
    'add_new_item' => __('Add New Fundraiser'),
    'edit_item' => __('Edit Fundraiser'),
    'new_item' => __('New Fundraiser'),
    'view_item' => __('View Fundraiser'),
    'search_items' => __('Search Fundraisers'),
    'not_found' => __('No Fundraisers Found'),
    'not_found_in_trash' => __('No Fundraisers Found in Trash'),
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'capability_type' => 'post',
    'public' => false, // Disables partial URL matching
    'show_ui' => true, // But includes it in admin
    'publicly_queryable' => true, // and lets you go by URL
    'exclude_from_search' => true, // but not by search
    'hierarchical' => true,
    'has_archive' => true,
    'menu_position' => 9,
    'rewrite' => array('slug'=>'support'),
    'query_var' => 'events',
 //   'taxonomies' => array('post_tag'),
    'register_meta_box_cb' => 'addEventMeta',
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt')
  );

  register_post_type( 'event' , $args );

  $role = get_role( 'subscriber' );
  $role->add_cap('edit_own_events');

  $labels = array(
    'name' => _x('Updates','general name'),
    'singular_name' => _x('Update','singular name'),
    'add_new' => _x('Add New', 'event'),
    'add_new_item' => __('Add New Update'),
    'edit_item' => __('Edit Update'),
    'new_item' => __('New Update'),
    'view_item' => __('View Update'),
    'search_items' => __('Search Updates'),
    'not_found' => __('No Updates Found'),
    'not_found_in_trash' => __('No Updates Found in Trash'),
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'supports' => array('title', 'editor', 'author', 'excerpt', 'custom-fields')
  );

  register_post_type( 'update' , $args );
}

// This function enables subscribers(users) to edit their own fundraisers
// It turns "edit_post" capability checks into a check for "edit_own_events"
// when checking an event object against its author.
// All subscribers are assigned the capability 'edit_own_events')
function event_map_meta_cap($caps, $cap, $user_id, $args) {
  if ($cap != 'edit_post')
    return $caps;

  $post = get_post($args[0]);
  if ($post->post_type != 'event')
    return $caps;

  if ($post->post_author == $user_id)
    return array('edit_own_events');

  return array('edit_others_posts');
}
add_filter('map_meta_cap', 'event_map_meta_cap', 10, 4);

global $PREFIX;
$PREFIX = "syi_";

$event_fields = array(
    /*array(
      'name' => 'Gifts',
      'desc' => 'ID#s of gifts to display',
      'id' => $PREFIX.'gift_ids',
      'type' => 'text',
      'def' => ''
    ),*/ array(
      'name' => 'Goal',
      'desc' => '$ amount to raise',
      'id' => 'goal',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Tag',
      'desc' => 'custom gift tag',
      'id' => $PREFIX.'tag',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Theme',
      'desc' => 'custom theme',
      'id' => 'theme',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Owner',
      'desc' => 'username or id',
      'id' => 'owner',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Appear as',
      'desc' => 'team/chapter page listing',
      'id' => $PREFIX.'appear-as',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Match',
      'desc' => 'matching account ID',
      'id' => $PREFIX.'matching_account',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Featured',
      'desc' => 'blogID-postID',
      'id' => $PREFIX.'featured_story',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Team',
      'desc' => 'team name',
      'id' => 'team',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'My Headline',
      'desc' => 'Headline for just this fundraiser',
      'id' => $PREFIX.'my_headline',
      'type' => 'text',
      'def' => ''
    ), array(
      'name' => 'Offline',
      'desc' => '$ amount raised offline',
      'id' => 'offline',
      'type' => 'text',
      'def' => '0'
    ), /*, array(
      'name' => 'Message',
      'desc' => 'Overrides the tags',
      'id' => $PREFIX.'gift_message',
      'type' => 'text',
      'def' => ''
    ),array(
      'name' => 'Restricted',
      'desc' => '',
      'id' => $PREFIX.'restricted',
      'type' => 'text',
      'def' => '0'
    ) */
);
function addEventMeta() {
  global $event_fields;
  add_meta_box('event-meta', 'Customize this event', 'showEventMeta', 'event', 'side', 'low');
}


function showEventMeta() {
  global $event_fields, $post;

  // Use nonce for verification
  echo '<input type="hidden" name="event_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

  echo '<table class="form-table">';

  $campaign = (array)get_campaign_stats($post->ID);

  foreach ($event_fields as $field) {
    // get current post meta data
    $id = $field['id'];
    $meta = eor($campaign[$id], get_post_meta($post->ID, $field['id'], true));

    echo '<tr>',
        '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
        '<td>';
    switch ($field['type']) {
      case 'text':
        echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['def'], '" size="30" style="width:97%" />', '
', $field['desc'];
        break;
      case 'textarea':
        echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['def'], '</textarea>', '
', $field['desc'];
        break;
      case 'select':
        echo '<select name="', $field['id'], '" id="', $field['id'], '">';
        foreach ($field['options'] as $option) {
          echo '<option', selected($meta, $option, FALSE),'>', $option, '</option>';
        }
        echo '</select>';
        break;
      case 'radio':
        foreach ($field['options'] as $option) {
          echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
        }
        break;
      case 'checkbox':
        echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta == 1 ? ' checked="checked"' : '', ' />';
        break;
    }
    echo   '<td>',
      '</tr>';
  }

  echo '</table>';
}

add_action('save_post', 'updateEventMeta', 1,2);
function updateEventMeta($post_id) {
  global $event_fields;
  global $wpdb;
  global $PREFIX;

  // verify nonce
  if (!wp_verify_nonce($_POST['event_meta_nonce'], basename(__FILE__))) {
    return $post_id;
  }
  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }
  // check permissions
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }

  $cattrs = array();
  foreach ($event_fields as $field) {
    $id = $field['id'];
    $new = stripslashes($_REQUEST[$id]);

    if (!startsWith($id, $PREFIX)) {

      // When updating the fundraiser's owner...
      if ($id == 'owner') {
        if ($new == 0) {
          $user = get_userdatabylogin($new);
          $new = intval($user->ID);
        }
        $new = intval($new);

        // current user for this campaign; 
        $uid = get_campaign_owner($post_id);
        if ($new != $uid) {
          $cattrs[$id] = $new;

          // current campaign for this user
          $cid = get_campaign_for_user($new);
          if ($cid == 0 || $cid == $post_id) {
            // Update this fundraiser's GUID
            $cattrs['guid'] = get_member_link($new);
          } else {
            // Don't update the GUID, we're setting the owner of a non-active fundraiser
          }
        }
      } else {
        $cattrs[$id] = $new;
      }
      continue;
    }

    $old = get_post_meta($post_id, $field['id'], true);
    if (($new || $new!==0) && $new != $old) {
      update_post_meta($post_id, $field['id'], $new);
    } elseif ('' == $new && $old) {
      delete_post_meta($post_id, $field['id'], $old);
    }
  }

  if (count($cattrs) > 0) {
    $wpdb->update('campaigns', $cattrs, array('post_id' => $post_id));
  }

}

// use the template's "organization" field to track down what team/campaign/org we are associated with
// first we try to match this fundraiser to a team
// then we try to match to a campaign
// then we try the organization
function get_fundraiser_cause($cause) {
  global $TEMPLATE, $post, $wpdb;

  $url = $TEMPLATE->url;
  if (!empty($url) && !empty($TEMPLATE->title))
    $cause = "for <a class=\"link\" href=\"$url\">{$TEMPLATE->title}</a>";
  else
    $cause = "";

  $blog_id = $TEMPLATE->blog_id;
  if ($blog_id == 0)
    return $cause;

  $team = $wpdb->get_row($wpdb->prepare("
SELECT team.id   AS id,
     c.team AS name
FROM   campaigns c
     INNER JOIN wp_${blog_id}_posts team
             ON team.post_title = c.team
     INNER JOIN wp_${blog_id}_posts parent
             ON parent.id = team.post_parent
     INNER JOIN wp_${blog_id}_postmeta pm
             ON pm.post_id = parent.id
             AND pm.meta_value = CONCAT(%s, c.theme)
WHERE  team.post_status = 'publish'
 AND team.post_type = 'page'
 AND c.post_id = %d
  ", $TEMPLATE->org.'/', $post->ID ));

  if ($team) {
    $url = get_blog_permalink($blog_id, $team->id);
    $cause .= " (<a href=\"$url\">{$team->name}</a> team)";
  }

  return $cause;
}
