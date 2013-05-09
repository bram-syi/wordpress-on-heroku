<?

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path.'/wp-content/mu-plugins/notification.php');
include_once($path.'/wp-content/mu-plugins/syi-mailer.php');
include_once($path.'/a/api/campaign.php');

function decode_id($id) {
  if (empty($id))
    return 0;
  return intval(decrypt(urldecode($id)));
}


global $bp, $wpdb, $invite_context, $invite_debug;

$context = $_REQUEST['context'];

$invite_group = 0;
if(isset($_REQUEST['invite_group'])) {
  $invite_group = intval($_REQUEST['invite_group']);
}

if (!isset($context)) die('Error: no context.');

$parts = explode("/", $context,2);
$user_id = 0;
if(is_user_logged_in())
  $user_id = $bp->loggedin_user->id;

$invites = '';
$error_msg = '';
$success = isset($_GET['success']);
$invites = $_REQUEST['invites'];
//pre_dump(decode_id($parts[1]));
// Decode context
switch ($parts[0]) {
case 'thankyou':
  $cart_id = decode_id($parts[1]);
  $invite_context = 'thankyou/'.$cart_id;
  break;

case 'campaign':
  $event_id = decode_id($parts[1]);
  $invite_context = 'campaign/'.$event_id;
  $subj_repl = "about this fundraiser";
  break;

case 'update':
  $event_id = decode_id($parts[1]);
  $invite_context = 'update/'.$event_id;
  $subj_repl = "about this fundraiser";
  $invitees = array();


  $donors = get_campaign_donors($event_id);
  if($invite_group == 1) { //donors
    $invitees = $donors;
  } else if($invite_group == 2) { //all
    $old_invitees = get_campaign_invitees($event_id);
    $invitees = array_merge($donors,$old_invitees);
  } else if($invite_group == 3) { //invitees only
    $invitees = get_campaign_invitees($event_id);
  }
  if(!empty($invitees)) {
    foreach($invitees as $k=>$i) {
      $invitees[$k] =  ($i!=''? $i.' ':'').'<'.$k.'>';
    }
    $invites = implode("\n",$invitees);
  }
  break;

default:
  if (!is_user_logged_in())
    die('Error: access denied: please log in.');
  $invite_context = $context;
}

//pre_dump($invite_context);

// XXX - what is the point of setting these here?
//   a. $invites is empty: we call the_content() and these are set from WP Posts
//   b. $invites is not empty: we set these from the $_POST
// http://www.google.com/search?tbm=isch&q=jackie+chan+wtf
$subject = draw_promo_content('default-invite-subject',0,0,1,1);
$message = draw_promo_content('default-invite-message',0,0,1,1);

$title = get_the_title();
if (!empty($subj_repl))
  $title = preg_replace('/\[(.*)\]/', $subj_repl, $title);
else
  $title = preg_replace('/\[(.*)\]/', '$1', $title);

if (!empty($invites)) {
  if (!wp_verify_nonce($_REQUEST['_wpnonce'],'invite-nonce'))
    wp_die('Access denied.');
  $message = trim(strip_tags($_REQUEST['message']));
  $sender = trim(strip_tags($_REQUEST['sender']));

  di('INSERTING INVITES');
  di('RAW CONTEXT: '.$_REQUEST['context']);
  di('CONTEXT: '.$invite_context);
  di('GROUP: '.$invite_group);
  di('SENDER NAME: '.$sender);
  di('PERSONAL MESSAGE: '.$message);
  di('RAW INVITES: '.$invites);

  $email_pattern="/([\s]*)([_a-zA-Z0-9-+]+(\.[_a-zA-Z0-9-+]+)*([ ]+|)@([ ]+|)([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,}))([\s]*)/i";
  $invites = preg_split("/[\r\n,;]/",$invites);

  di('PARSED INVITES: '.print_r($invites,true));

  $actual_invite_count = 0;
  if (count($invites)>0) {
    $parsed_invites = array();
    foreach ($invites as $invite) {
      if (preg_match($email_pattern, $invite, $matches)>0) {
        $email = $matches[0];
        $name = trim(preg_replace(array("/\s+/","/[^A-Za-z0-9_\s]/"),array(" ",""),str_replace($email,'',$invite)));
        $parsed_invites[] = trim($name.' <'.$email.'>');
        $actual_invite_count++;
      }
    }
    $invites = implode("\n",$parsed_invites);
    if ($error_msg != '') { $error_msg .= '.'; }
  }
  if ($actual_invite_count == 0) {
    $invites = '';
    $error_msg .= " No email found. ";
  }

  ////////////////////////////////////////////////////////////////////////////////

  if ($error_msg == '') {
    $invites_arr = explode("\n", $invites);
    if (is_array($invites_arr) && $invites_arr>0) {
      $to_emails = array();

      // we used to insert into "invite" and "invitations", and then later in
      // wp-cron we would pull this info back out with this query:
      //
      //   $sql = $wpdb->prepare("SELECT *, ivn.id AS ivnID, iv.id AS ivID
      //     FROM invitation ivn JOIN invite iv ON iv.invitation_id = ivn.id
      //     WHERE (iv.status = 'pending')
      //     AND ivn.date_added >= DATE_SUB(NOW(), INTERVAL 30 DAY) LIMIT 500");
      //   $results = $wpdb->get_results($sql);
      //
      // then we make the Notification:
      //   $n = new Notification(0,0,20,0);
      //
      // then we build the content:
      //
      //   if ($n->recipient_name=='') $n->recipient_name = 'Friend';
      //   $built = $n->build_invite_content($result->context, $result->user_id, $result->message,
      //     $result->ivID, $result->inviter_name);
      //   $content = utf8_encode(xml_entities($n->get_finished_content()));
      //
      // NOW, we just do the above in this function (by calling "send_to_gearman"), and we
      // record the entry in the "invite" table as being status "gearman".

      // inserting invitation (the content)
      $invitation_sql_arr = array('user_id'=>$user_id,  'date_added'=>date('Y-m-d H:i:s'),
      'inviter_name'=>$sender, 'message'=>$message);
      $wpdb->insert('invitation', $invitation_sql_arr, array('%d','%s','%s','%s'));
      $invitation_id = $wpdb->insert_id;
      di('INSERTED INVITATION #'.$invitation_id.': '.print_r($invitation_sql_arr,true));

      $inserted = 0;
      foreach ($invites_arr as $invite) {
        if(preg_match($email_pattern, $invite, $matches)>0) {
          $email = $matches[0];
          $name = trim(preg_replace(array("/\s+/","/[^A-Za-z0-9_\s]/"),array(" ",""),str_replace($email,'',$invite)));
          // inserting invite (the recipients)
          $invite_sql_arr = array('name'=>$name, 'email'=>$email,
            'status'=>'gearman', 'context'=>$invite_context, 'invitation_id'=>$invitation_id);
          $wpdb->insert('invite', $invite_sql_arr, array('%s','%s','%s','%s','%d'));
          $wpdb->query($wpdb->prepare("update invite set date_sent = NOW() where id = %d", $wpdb->insert_id));
          // di('INSERTED INVITE #'.$wpdb->insert_id.': '.print_r($invite_sql_arr,true));
          $inserted++;
          $to_emails[] = $name ? "\"$name\" <$email>" : $email;
        }
      }

      if (count($invites_arr)>0) {
        $success = 1; $invites='';
        di_end('INVITE PROCESS COMPLETED, INSERTED: '.$inserted);
      }

      $owner = get_campaign_owner_name($event_id);

      // we pass the arguments needed to call Notification::build_invite_content
      $message = stripcslashes($message);
      $message = preg_replace('/(\r?\n)+/', '<br/><br/>', $message);
      $build_invite_content = compact('invite_context', 'user_id', 'message', 'invitation_id', 'sender', 'owner');
      send_via_syimailer( $to_emails, $build_invite_content );

      $facebook = new SyiFacebook(get_current_user_id());
      $facebook->publish_invite($event_id, $message);
    }
  } else { $success = 0; $error_msg = ' No email found. '; }
} else { $success = 0; $error_msg = ' No email found. '; }

if (isset($_GET['ajax'])) {
  if (!$success) echo 'Error: '.$error_msg;
  exit();
}

function send_via_syimailer($to_emails, $build_invite_content) {
  global $wpdb;

  $build_invite_content['sender'] = preg_replace('/"/', '', stripslashes($build_invite_content['sender']));

  $syi_mailer = array(
    'recipient' => $to_emails,
    'mail_body' => 'invite',
    'header' => array(),
    'variables' => array()
  );

  if ($build_invite_content['owner'] == $build_invite_content['sender']) {
    $syi_mailer['header']['X-Tag'] = 'why:direct';
  }
  else {
    $syi_mailer['header']['X-Tag'] = 'why:indirect';
  }
  $syi_mailer['subject'] = "You're invited! Join me and help change lives.";

  $n = new Notification(0,0,20,0);
  $success = $n->build_invite_content(
    $build_invite_content['invite_context'],
    $build_invite_content['user_id'],
    $build_invite_content['message'],
    $build_invite_content['invitation_id'],
    $build_invite_content['owner']
  );
  $syi_mailer['variables']['body_html'] = $n->get_finished_content();

  $a = explode('/', $build_invite_content['invite_context']);
  $theme = $wpdb->get_var($wpdb->prepare(
    'select theme from campaigns where post_id = %d', $a[1]
  ));
  if (!$theme) {
    $theme = 'none';
  }

  $post = get_post($a[1]);

  global $current_user;
  get_currentuserinfo();

  $reply_to = $current_user->user_email;
  if (!$reply_to) {
    $reply_to = 'impact@seeyourimpact.org';
  }

  $syi_mailer['header']['From'] = '"' . $build_invite_content['sender'] . ' via SeeYourImpact.org" <impact@seeyourimpact.org>';
  $syi_mailer['header']['Reply-To'] = $reply_to;
  $syi_mailer['header']['X-Tag'] .= ",email:fr_invite,theme:$theme";
  $syi_mailer['header']['syi-bcc'] = get_email_address("outreach");
  $syi_mailer['header']['syi-invite'] = $build_invite_content['invitation_id'];
  $syi_mailer['variables'] = array(
    'owner' => $build_invite_content['owner'],
    'sender' => $build_invite_content['sender'],
    'photo' => fundraiser_image_src($a[1], image_geometry(150, 150)),
    'message' => $build_invite_content['message'],
    'fundraiser_text' => nl2br(html_to_text($post->post_content)),
    'email_description' => "An invitation to join ".$build_invite_content['owner'].'\'s fundraiser on SeeYourImpact.org',
    'url' => get_permalink($a[1])
  );

  SyiMailer::altsend($syi_mailer);
}

// Arguably, this could be in wp-content/mu-plugins/notification.php, except that
// it only applies to campaigns, and that file is bloated enough already.
function get_default_message($event_id) {
  // get it from the theme
  global $wpdb;

  $campaign = CampaignApi::getOne(array( 'for_fr' => $event_id ));
  return eor($campaign->h20->default_invite_message, '');
}

?><div class="page based" id="invite-page">
<script type="text/javascript" src="https://api.cloudsponge.com/address_books.js"></script>
<script type="text/javascript" charset="utf-8">
var csPageOptions = {
  domain_key:"GMBB3T4VFG5RMDMDKWFQ",
    textarea_id:"invites",
    include: ['name','email']
}
</script>
<form method="post" action="" id="invite-form" onsubmit="return validate();" class="no-enter evs">
  <? wp_nonce_field('invite-nonce'); ?>
  <input type="hidden" name="context" value="<?=$context?>" />
  <h1 style="margin:10px 0;"><?= $title ?></h1>
<? the_content();
// content is in notification.php functions (shortcode handlers):
// draw_invite_import -- 1344
// draw_invite_message -- 1391
?>
</form>
</div>
