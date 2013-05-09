<?php

// inherit from WordPress's wp-includes/class-phpmailer.php class,
// so that we can add one method to access a protected member.

require_once( ABSPATH . 'wp-includes/class-phpmailer.php' );

class SyiPHPMailer extends PHPMailer {
   /*
   * Returns our protected $to property as an array of strings:
   *   array( "alice <alice@foo.com>", "bob <bob@bar.com>" )
   */
  public function GetToAddresses() {
    $arr = array();
    foreach($this->to as $t) {
      $arr[] = "\"$t[1]\" <$t[0]>";
    }
    return $arr;
  }
}
