<?php
define('XMLRPC_REQUEST', false);
require('../wp-blog-header.php');
remove_all_actions('wp_head');
ensure_logged_in_admin();

global $wpdb;

if ( ! array_key_exists('id', $_REQUEST)) {
  exit;
}
else {
  if ( ! array_key_exists('do', $_REQUEST)) {
    $exists = $wpdb->get_var($wpdb->prepare(
      'select id from syi_mailer_queue where id = %s', $_REQUEST['id']
    ));
    if ( ! $exists ) {
      print "<p>That is not a valid email ID.</p>";
      exit;
    }
  }
}

if ( ! array_key_exists('do', $_REQUEST)) { ?>
<p>The email below is trying to be sent, choose wisely:</p>
<form method="GET">
  <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>"/>
  <input type="submit" name="do" value="Yes, send it"/>
  <input type="submit" name="do" value="No, ignore it"/>
</form>
<? }
else {
  if ($_REQUEST['do'] == 'Yes, send it') {
    $c = SyiMailer::release_email($_REQUEST['id']);

    if ($c) {
      print "<p>successfully sent email</p>";
    }
    else {
      print "<p>FAILED to send email</p>";
    }

  }
  else if ($_REQUEST['do'] == 'No, ignore it') {
    print "<p>okay then</p>";
    $wpdb->query($wpdb->prepare(
      'update syi_mailer_queue set id = reverse(id) where id = ?', $_REQUEST['id']
    ));
  }
  else {
    print "<p>what?</p>";
  }
}

$html = $wpdb->get_var($wpdb->prepare(
  'select html_body from syi_mailer_queue where id = %s', $_REQUEST['id']
));
print "<br/>$html";
?>
