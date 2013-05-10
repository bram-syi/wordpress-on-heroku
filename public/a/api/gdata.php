<?

// Why is getcwd not correct here?
require_once(__DIR__ . '/api.php');
require_once __DIR__ . '/google-api-php-client/src/Google_Client.php';
require_once __DIR__ . '/google-api-php-client/src/contrib/Google_Oauth2Service.php';

class GAuth {

  public static $client;
  public static $token;

  // This function will cause the user to redirect through Google login if they're
  // not already signed in with Google.  If they're already logged in, it returns
  // a token that can be used in subsequent GData calls.
  public static function login() {

    // TODO: don't use session to store GAuth
    if (!session_id()) {
      session_start();
    }

    // Did the user ask to log out?
    if (isset($_GET['logout'])) {
      unset($_SESSION['token']);
      unset($_SESSION['state']);
      print "Logged out.";
      die;
    }

    $client = new Google_Client();
    $client->setApplicationName('SeeYourImpact.org');
    $client->setClientId('1050394412245-cqce8527u90708f2arvpdsuhv1ble72l.apps.googleusercontent.com');
    $client->setClientSecret('GALN4zlTMkQQjX97qIjGGbee');

    // TODO: install a permanent Oauth redirect endpoint
    $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . "/oauth2");
    $client->setDeveloperKey('AIzaSyAPo-317h0sQFovsY3VuKGQk6A41aCaJ0w');
    $client->setAccessType('offline');
    $client->setScopes('https://spreadsheets.google.com/feeds/ https://www.googleapis.com/auth/userinfo.profile');

    // Are we just back from a redirect through Google login?
    if (isset($_GET['code'])) {
      // restore the previous query string and redirect there
      $state = strval($_SESSION['state']);
      unset($_SESSION['state']);

      if ($state !== strval($_GET['state'])) {
        die("The session state did not match.");
      }

      $client->authenticate();
      $_SESSION['token'] = $client->getAccessToken();
      wp_redirect(remove_query_arg(array('code','state'), $state));
      die;
    }

    // Are we still logged out?
    if (!isset($_SESSION['token'])) {
      // Save the request & query string for when we return from auth
      $state = $_SERVER["REQUEST_URI"];
      $client->setState($state);
      $_SESSION['state'] = $state;

      wp_redirect($authUrl = $client->createAuthUrl());
      die;
    }

    // We're logged in.  Set the current auth token.
    self::$client = $client;
    self::$token = $_SESSION['token'];
    $client->setAccessToken(self::$token);
    return json_decode(self::$token);
  }

  // TODO: finish this [will need to store refreshTokens]
  public static function startOffline() {
    // set up the client
    // refresh the access token from storage
  }

  public static function getName() {
    $plus = new Google_Oauth2Service(self::$client);
    $me = $plus->userinfo->get();
    $my_name = trim("{$me['given_name']} {$me['family_name']}");

    if (empty($my_name)) {
      // Session has expired.
      // TODO: refresh/login?
    }

    return $my_name;
  }
}

class GData {

  public static function fetchList($doc, $token, $sheet = 1) {
    $key = static::findDocKey($doc);
    if (empty($key))
      throw new Exception("invalid document: $doc");

    $headers = array(
      'GData-Version: 3.0',
      'Content-Type: application/atom+xml'
    );

    $uri = "https://spreadsheets.google.com/feeds/list/{$key}/{$sheet}/private/full?access_token={$token->access_token}";
    if ($_GET['debug'] == 'yes') {
      pre_dump("GET $uri");
    }

    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt( $curl, CURLOPT_URL, $uri );
    $body = curl_exec( $curl );
    if ($body == FALSE) {
      throw new Exception('CURL ERROR: ' . curl_error( $curl ));
    }
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close( $curl );

    if ($body == "Invalid request URI") {
      throw new Exception("The spreadsheet URL seems to be invalid.");
    }
    if ($body == "private") {
      throw new Exception("You don't have access to that spreadsheet.");
    }
    if (strpos($body, "<TITLE>Token invalid - Invalid token") !== FALSE) {
      throw new Exception("Your session has expired - please log in again");
    }
    if ($http_status != 200) {
      throw new Exception("There was a problem: $body");
    }

    $rows = array();

    $xml = simplexml_load_string($body);
    foreach ($xml->entry as $entry) {
      $rows[] = static::getRowData($entry);
    }

    return $rows;
  }

  public static function getRowData(SimpleXmlElement $entry) {
    $row = array();
    $row['$id'] = "{$entry->id}";

    $gd = $entry->attributes("gd", TRUE);
    $row['$etag'] = "{$gd['etag']}"; // string conversion out of SimpleXML

    foreach ($entry->link as $link) {
      if ($link['rel'] == "edit")
        $row['$edit'] = "{$link['href']}";
    }

    foreach ($entry->children("gsx", TRUE) as $k=>$v) {
      $row[$k] = "$v";
    }

    return $row;
  }

  public static function saveRow($row, $token) {
    $data = '<entry xmlns="http://www.w3.org/2005/Atom" xmlns:gsx="http://schemas.google.com/spreadsheets/2006/extended" xmlns:gs="http://schemas.google.com/spreadsheets/2006" xmlns:gd="http://schemas.google.com/g/2005">';

    $data .= "<id>{$row['$id']}</id>";
    foreach ($row as $k=>$v) {
      if (strncmp($k, '$', 1) == 0)
        continue; // Skip special keys

      // TODO: encode for XML
      $data .= "<gsx:$k>$v</gsx:$k>";
    }
    $data .= "</entry>";

    $headers = array(
      'Content-Type: application/atom+xml',
      'GData-Version: 3.0',
      "If-Match: {$row['$etag']}"
    );

    $uri = "{$row['$edit']}?access_token={$token->access_token}";
    if ($_GET['debug'] == 'yes') {
      pre_dump("PUT $uri");
      pre_dump($data);
    }

    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_HEADER, FALSE);
    curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt( $curl, CURLOPT_URL, $uri);
    curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt( $curl, CURLOPT_POSTFIELDS, $data);
    $body = curl_exec( $curl );
    if ($body == FALSE) {
      throw new Exception('CURL ERROR: ' . curl_error( $curl ));
    }
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close( $curl );

    if ($http_status != 200) {
      throw new Exception("There was a problem: $body");
    }
  }

  public static function findDocKey($url) {
    if (!preg_match("/\/docs.google.com\/.*[\?\&]key=([^\?\&\#]*)/", $url, $m))
      return NULL;
    return $m[1];
  }

}

class GSpreadsheet extends GData {
}
