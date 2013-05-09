<?php

if (1) {
  define('XMLRPC_REQUEST', false);
  require('../wp-blog-header.php');
  remove_all_actions('wp_head');
  ensure_logged_in_admin();

  print "<pre>\n";
}

function foo($content) {
 $content['requires_approval'] = 'More than 99 recipients';
 return $content;
}
#add_filter('syi_mailer_content', 'foo');

if (!defined('ABSPATH')) {
  define('ABSPATH', dirname(__FILE__) . './');
}

include_once(dirname(__FILE__) . './../wp-content/mu-plugins/syi-mailer.php');

if (1) {
  // Send templated

  $donor = (object)array(
    'email' => 'foo@bar.com',
    'first' => 'Percival'
  );

  $ret = SyiMailer::send(
      'pop & fresh <alex@seeyourimpact.org>',
      'Basic Gearman test email',
      'clean',
      array(
        'From' => 'gearman'
      ),
      array(
        'leadin_alt_text' => "The handfuls of billionaires involved in space mining will be sure to share the profits with us.  Just you wait and see!  I mean, what could be wrong with having a few trillionaires around, anyway? Isn't more money always good?",
        'content' => "ugh, i'm sorry i missed your birthday party; ok?
i already told you, it was in low sec and it was 2days into Hulkageddon. was going to your party sooooo important that i needed to lose a hulk over it?
besides, i posted a delivery contract for 200units of Cake for delivery to your cargo bay.
the contract hasn't been picked up yet, but it's there... it's the thought that counts.
there! are we over this? or are you going to be like this all day?"
      )
    );

  // $ret = SyiMailer::send(
  //   $donor->email,
  //   'premailer testing',
  //   'invite',
  //   array(
  //     'From' => '"Contact" <contact@seeyourimpact.org>',
  //     'syi-bcc' => 'alex+bcc@seeyourimpact.org'
  //   ),
  //   array(
  //     'header_one' => '<p>this is a <br/><br/> html <b>test</b></p>',
  //     'header_two' => 'second header blah blah blah',
  //     'message' => 'lorem ipsum',
  //   )
  // );
}
else {
  // non-templated email
  $to = array('alex+wash@seeyourimpact.org', 'alex+malcolm@seeyourimpact.org');

  $mail_body = array(
    'text/plain' => 'Hello world in plain text',
    'text/html' => '<h1>Hello world</h1><p>in <b>HTML</b></p>'
  );

  $ret = SyiMailer::send($to, 'PostageApp Test Subject', $mail_body, $header);
}

if ($ret) {
  echo "success!\n";
}
else {
  echo "failed: ".SyiMailer::$error."\n";
}
