<?php

// for apply_filters()
require_once( ABSPATH . 'wp-includes/plugin.php' );

if(!defined('POSTAGE_HOSTNAME')) define ('POSTAGE_HOSTNAME', 'https://api.postageapp.com');
if(!defined('POSTAGE_API_KEY')) define ('POSTAGE_API_KEY', 'q5deROAewzYi9Yn5tTbwoRwSFqGP2XCR');

//
// This class sends email. See the send() function for details on how to call.
// NOTE: send() applies the "syi_mailer_content" filter, so add_filter() on this
// tag to extend mail sending functionality.
//
// Current extensions:
//
// 1. "requires_approval" key - setting this key will defer actually sending
//    the email. Instead, it will be held in the "syi_mailer_queue" database
//    table. SyiMailer::hold_email() can be used to do this.
//
class SyiMailer
{
  // currently knows about 'postageapp', 'gearman', and 'stdout'
  public static $which = 'gearman';

  // if something goes wrong, read this property
  // NOTE: it might contain newlines
  public static $error;

  // select the gearman queue to send jobs to
  public static $gearman_queue = 'send_email_v6';

  // This function signature is based on PostageApp.com's PHP example class.
  // it eventually sends JSON via POST, and the PHP arguments are tied very
  // tightly to how the json is structured. read about the json here:
  //   http://help.postageapp.com/kb/api/send_message
  //
  // Briefly:
  //   $recipient - string, array, or complex array
  //   $subject   - string (mustache template tags allowed)
  //   $mail_body - string (a template name, no file extension)
  //   $header    - array (email headers)
  //   $variables - hash (variables for templating)
  //
  // Some headers will do special things, they are all prefixed with "syi-",
  // and are stripped from the email prior to sending through critsend. In
  // other words, they are used to control Gearman.
  //
  //   syi-bcc - this will cause Gearman to send a bcc of the email job to
  //       the alias given by syi-bcc
  //   syi-invite - this will cause the email to get an X-UID email header
  //       which will contain the row ID of the invite associated with
  //       each invite email
  //   syi-update - similar to syi-invite, this will set an X-UID email header
  //       with the row ID of the post in wp_1_posts where the update is
  //       stored
  //   syi-post-facebook-update - if this header is present and non-empty, the
  //       the fundraiser associated with the syi-update header will get their
  //       update posted to their facebook wall
  public function send($recipient, $subject, $mail_body, $header = array(), $variables=NULL) {
    SyiMailer::$error = NULL;

    SyiLog::log('mq', 'SyiMailer::send: '.json_pretty(debug_backtrace()));

    if ($header === null) {
      $header = array();
    }

    $defaults = array('From' => 'SeeYourImpact.org <impact@seeyourimpact.org>');
    if (!empty($subject))
      $defaults['Subject'] = $subject;

    $content = array(
      'recipients'  => $recipient,
      'headers'     => array_merge($defaults, $header),
      'variables'   => $variables,
    );

    if (is_string($mail_body)) {
      $content['template'] = $mail_body;
    } else {
      $content['content'] = $mail_body;
    }

    // http://codex.wordpress.org/Plugin_API#Filters
    $content = apply_filters('syi_mailer_content', $content);

    if (is_array($content['recipients']) and count($content['recipients']) > 100) {
      $content['requires_approval'] = "More than 100 recipients";
    }

    if (array_key_exists('requires_approval', $content)) {
      SyiMailer::hold_email($content, $content['requires_approval']);
    } else {
      SyiMailer::send_content($content);
    }

    return is_null(SyiMailer::$error);
  }

  // This is just a convenience method to call ::send with a single argument.
  public function altsend($thing) {
    if (is_object($thing)) {
      return SyiMailer::send(
        $thing->recipient,
        $thing->subject,
        $thing->mail_body,
        $thing->header,
        $thing->variables
      );
    }
    else if (is_array($thing)) {
      return SyiMailer::send(
        $thing['recipient'],
        $thing['subject'],
        $thing['mail_body'],
        $thing['header'],
        $thing['variables']
      );
    }
    else {
      error_log("invalid use of SyiMailer::altsend(\$thing)");
    }
  }

  // this takes a PHPMailer object, and pulls the info we need out of it
  // and sends it through the Gearman "raw" template
  public function legacy($mail, $recipients, $headers) {
    $opts = array(
      'recipient' => $recipients,
      'subject' => $mail->Subject,
      'mail_body' => 'raw',
      'header' => array(
        'From' => 'SeeYourImpact.org <impact@seeyourimpact.org>',
        'X-Tag' => 'why:direct,email:thankyou',
        'syi-bcc' => get_email_address('outreach'),
      ),
      'variables' => array(
        'body_html' => $mail->Body),
    );

    foreach ($headers as $name => $val) {
      $opts['header'][$name] = $val;
    }

    return SyiMailer::altsend($opts);
  }

  // This accepts the id returned by hold_email() to finalize an email
  // send. Returns true if successful, false otherwise, read
  // SyiMailer::$error for error info.
  public function release_email($id) {
    global $wpdb;

    SyiMailer::$error = NULL;

    $select = $wpdb->prepare('select content from syi_mailer_queue where id = %s', $id);
    $json = $wpdb->get_var($select);
    if ($json != NULL) {
      SyiMailer::send_content(json_decode($json, 1));
      if (is_null(SyiMailer::$error)) {
        $delete = $wpdb->prepare('delete from syi_mailer_queue where id = %s', $id);
        $wpdb->query($delete);
      }
      else {
        SyiMailer::error("releasing mail id '$id' failed, leaving it in database");
      }
    }
    else {
      SyiMailer::error("json for send id $id doesn't exist");
    }

    return is_null(SyiMailer::$error);
  }

  // Put an email in the database for holding, and send an admin email 
  // the ID of the deferred email.
  // xxx - if the Gearman queue name changes between hold_email() and
  // release_email(), there will be problems
  protected function hold_email($content, $reason) {
    global $wpdb;

    $id = uniqid();

    $wpdb->query($wpdb->prepare(
      'insert into syi_mailer_queue(id, created, reason, content) values (%s, now(), %s, %s)',
      $id,
      $reason,
      json_encode($content)
    ));

    $count = 1;
    if (is_array($content['recipients'])) {
      $count = count($content['recipients']);
    }

    $email = get_email_address('outreach');

    $approval_link = site_url('/syi/approve_email.php') . "?id=$id";

    if (preg_match('/More than \d+ recipients/i', $reason)) {
      // these can go via a template on the Gearman server...we add in some
      // extra info, and then send it along
      $original = $content;
      $content = array(
        'headers' => array(
          'syi-approve' => $id,
          'Subject' => "APPROVAL REQUIRED: trying to send $count emails",
          'From' => "\"Outreach\" <$email>",
        ),
        'template' => 'request_approval',
        'recipients' => $email,
        'variables' => array(
          'approval_link' => $approval_link,
          'approval_id' => $id,
          'approval_template' => $content['template'],
          'approval_subject' => $content['headers']['Subject'],
          'approval_from' => $content['headers']['From'],
          'recipient_list' => $content['recipients'],
          'original_content' => $original,
        ),
      );

      SyiMailer::send_content($content);
      if (!is_null(SyiMailer::$error)) {
        SyiMailer::error("sending approval email to email '$email' failed");
      }
    }
    else {
      // assume gearman itself is broken, so send via native mail()
      mail($email, 'email held while sending', "Reason: $reason\nSyiMailer::\$error: " . SyiMailer::$error . "\nretry by clicking: $approval_link");
    }

    return $id;
  }

  // Sends a php object to the proper email backend. Sets SyiMailer::$error
  // if there was an error.
  protected function send_content($content) {
    // we always decode the subject into non-html
    $content['headers']['Subject'] = html_entity_decode(
      $content['headers']['Subject'],
      ENT_COMPAT | ENT_HTML401,
      "UTF-8"
    );

    if (SyiMailer::$which == 'postageapp') {
      $content['uid'] = time();

      $response = SyiMailer::post(
        POSTAGE_HOSTNAME.'/v.1.0/'.$api_method.'.json',
        json_encode(
          array(
            'api_key' => POSTAGE_API_KEY,
            'arguments' => $content
          )
        )
      );

      if ($response->response->status != 'ok') {
        SyiMailer::error("send() via PostageApp failed: ".$response->response->message);
      }
    }
    else if (SyiMailer::$which == 'gearman') {
      $content['server_name'] = $_SERVER['SERVER_NAME'];
      $content['request_uri'] = $_SERVER['REQUEST_URI'];

      $r = SyiMailer::post(
        'http://aws.seeyourimpact.com/78336f87',
        json_encode(array(
          'method' => SyiMailer::$gearman_queue,
          'content' => $content
        ))
      );

      if ($r) {
        if ($r->status != 'ok') {
          SyiMailer::error("send() via Gearman failed: $r->reason");
          SyiMailer::hold_email($content, 'failed to queue Gearman job');
        }
      }
      else {
        SyiMailer::error("http post to gearman queue failed");
        SyiMailer::hold_email($content, "couldn't reach remote Gearman server");
      }
    }
    else if (SyiMailer::$which == 'stdout') {
      echo json_encode($content), "\n";
    }
    else {
      SyiMailer::error('send() didn not do anything, ::which was not set correctly');
    }
  }

  // Makes an HTTP post with the curl lib
  //   $url: the url, obviously
  //   $content: a json string, which will be the entire body of the post
  protected function post($url, $content) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $content);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output);
  }

  // error_log($str), and append $str to SyiMailer::$error
  protected function error($str) {
    error_log($str);
    if (is_null(SyiMailer::$error)) {
      SyiMailer::$error = '';
    }
    SyiMailer::$error .= "\n$str";
  }
}
?>
