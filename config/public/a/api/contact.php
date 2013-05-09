<?
require_once(__DIR__.'/api.php');
require_once(APIPATH . '/donor.php');
require_once(APIPATH . '/fullcontact/FullContact.php');

define('FULLCONTACT_APIKEY', '1450afa02c8b7e2b');

class ContactApi extends Api {

  public static function get($req) {
    $record = req($req, array('email:id', 'refresh', 'queue'));

    // refresh the contact info - possibly queueing it async
    if (isset($record->refresh)) {
      static::getFullContact($record->email, isset($record->queue));
    }

    // For now, donor == contact
    return DonorApi::get($req);
  }

  public static function update($req) {
    $record = req($req, array('email:id', 'fullcontact'));

    return static::insert_or_update($record);
  }

  protected static function insert_or_update($record) {
    global $wpdb;

    if (!empty($record->email))
      $wpdb->update('donationGiver', (array)$record, array('email' => $record->email));
    else {
      throw new Exception("Not supported");
    }

    return static::getOne($record->email);
  }

  public static function fix_email($email) {
    $email = str_replace("@test.seeyourimpact.com","", $email);
    $email = str_replace(".yahoo.com", "@yahoo.com", $email);
    $email = str_replace(".gmail.com", "@gmail.com", $email);
    $email = str_replace(".seeyourimpact.org", "@seeyourimpact.org", $email);
    $email = str_replace(".hotmail.com", "@hotmail.com", $email);
    $email = str_replace(".microsoft.com", "@microsoft.com", $email);
    $email = str_replace(".concur.com", "@concur.com", $email);
    return $email;
  }

  public static function getFullContact($email, $queue = FALSE) {
    if (empty($email))
      return;

    $webhook = NULL;
    if ($queue) {
      $webhook_path = str_replace(ABSPATH,'',__FILE__);
      $webhook_url = "http://" . $_SERVER['SERVER_NAME'] . '/' . $webhook_path;
      $webhook_id = $email;

/*
      // For debugging, use postcatcher.in
      $webhook_url = "http://postcatcher.in/catchers/5116e493bb2dcf020000013d";
*/

      $webhook = array(
        'webhookUrl' => $webhook_url . "?webhook=" . FULLCONTACT_APIKEY,
        'webhookId' => $webhook_id
      );
    } else {
      // TODO: store this result right away
    }

    $fullcontact = new FullContactAPI(FULLCONTACT_APIKEY); 
    return (array)$fullcontact->doLookup(static::fix_email($email), 0, $webhook);
  }

}

if ($_POST && ($_GET['webhook'] == FULLCONTACT_APIKEY )) {

  // Receive FullContact.com webhook response (JSON format: http://www.fullcontact.com/developer/docs/person/)

  // TODO: should probably use req instead in case we swap out from wp
  //       (so we don't have to replace each "stripslashes")
  // $req = req($_POST, array('result','email:webhookId'));

  $result = stripslashes($_POST['result']);
  $email = stripslashes($_POST['webhookId']);

  // Debugging? check this.
  // file_put_contents("webhook.log", $result);

  // Store the contact information with the donor
  ContactApi::update(array(
    'email' => $email,
    'fullcontact' => indent_json($result)
  ));
  die;
}

register_api(__FILE__, 'ContactApi');
