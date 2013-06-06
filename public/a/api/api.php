<?

// Should we execute this request, or is it a library include?
define('API_CALL', !defined('ABSPATH'));

// Current content version (increment to clear content caches)
define('CONTENT_VERSION', '1.33');

// Handle PUT like a POST
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  parse_str(file_get_contents("php://input"), $_POST);
  $_REQUEST = array_merge($_POST, $_GET);
} else if (API_CALL && $_SERVER['REQUEST_METHOD'] != 'POST' && empty($_FILES) && !isset($_REQUEST['rewrite'])) {
  if (!defined('SHORTINIT'))
    define( 'SHORTINIT', true );
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
define('APIDIR', 'a/api');
define('APIPATH', ABSPATH . APIDIR);

// Missing because of SHORTINIT -- this logs you in
if(!function_exists('get_currentuserinfo')){
 require_once(ABSPATH . WPINC . '/formatting.php' );
 require_once(ABSPATH . WPINC . '/capabilities.php' );
 require_once(ABSPATH . WPINC . '/user.php' );
 require_once(ABSPATH . WPINC . '/meta.php' );
 require_once(ABSPATH . WPINC . '/pluggable.php' );
 require_once(ABSPATH . WPINC . '/post.php' ); // For post permissions
 wp_cookie_constants();
}

// Because we do a SHORTINIT in some cases...
require_once(ABSPATH . WPINC . '/syi/syi-functions.php');
require_once(ABSPATH . WPINC . '/functions.php'); // support for core code
require_once(ABSPATH . WPINC . '/kses.php'); // support for core code
require_once(ABSPATH . WPINC . '/link-template.php'); // get_site_url
require_once(ABSPATH . 'wp-content/mu-plugins/multisite.php'); 

if (!defined('SITE_URL'))
  syi_multisite_init();

define('CONTENT_PATH', '/wp-content/V' . CONTENT_VERSION);

function content_version($ver = CONTENT_VERSION) {
  return SITE_URL . '/wp-content/V' . $ver . '/';
}
function __C($path, $ver = CONTENT_VERSION) {
  if (substr($path,0,1) == '/')
    $path = substr($path, 1);
  return content_version($ver) . $path;
}

function versioned_url($url, $version = CONTENT_VERSION) {
  if (!IS_LIVE_SITE) {
    return $url;
  } else {
    return str_replace('/wp-content/', "/wp-content/V$version/", $url);
  }
}

// Uses a trick to execute "svnversion" and return this as the app's version number
function sub_version() {
  $revision = intval(`svnversion`);
  return "2.{$revision}";
}

global $wpdb;
global $dump;
global $blog_id;

define('ALL_ADMIN_PERMISSIONS', -99);
global $ADMIN_PERMISSIONS;
$ADMIN_PERMISSIONS = ALL_ADMIN_PERMISSIONS;

global $ADMIN_PARTNER;
if ($blog_id > 1) {
  $ADMIN_PARTNER = $wpdb->get_var($wpdb->prepare(
    "SELECT domain FROM charity WHERE blog_id = %d", 
    $blog_id));
}

function js_open_home() {
  $path = eor($_REQUEST['_open'], '/');

  global $ADMIN_PARTNER;
  if ($path == '/' && !empty($ADMIN_PARTNER))
      $path = "/partner/$ADMIN_PARTNER";

  ?><script>_open=<?= json_encode($path);?>;</script><?
}

/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 *
 * @return string Indented version of the original JSON string.
 */
function indent_json($json) {

  $result      = '';
  $pos         = 0;
  $strLen      = strlen($json);
  $prevChar    = '';
  $outOfQuotes = true;

  for ($i=0; $i<=$strLen; $i++) {

    // Grab the next character in the string.
    $char = substr($json, $i, 1);

    // Are we inside a quoted string?
    if ($outOfQuotes) {
      if ($char == ' ')
        continue;
      else if ($char == ':')
        $char = ": ";

      if ($prevChar == ',') {
        if ($char != '{')
          $result .= newline($pos);

      } else if ($char == '}' || $char == ']') {
        // If this character is the end of an element,
        // output a new line and indent the next line.
        $result .= newline(--$pos);
      } else if ($prevChar == '{' || $prevChar == '[') {
        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        $result .= newline(++$pos);
      }
    }

    if ($char == '"' && $prevChar != '\\') {
      $outOfQuotes = !$outOfQuotes;
    }

    // Add the character to the result string.
    $result .= $char;
    $prevChar = $char;
  }

  return $result;
}

function newline($pos) {
  $result = "\n";
  for ($j = 0; $j < $pos; $j++) {
    $result .= "  ";
  }
  return $result;
}

function comment($str) {
  global $dump;

  $dump .= "\r\n/* ========= <PRE>" . print_r($str, true) . "\r\n</PRE> ========= */\r\n";
}

function req($req, $var, $def = NULL) {
  $req = (array)$req;

  if (is_array($var)) {
    $ret = array();

    for ($i = 0; $i < count($var); $i++) {
      // If a multi-name var is requested, use the first one as the "true" name
      list($name) = explode(':', $var[$i]);
      $val = req($req, $var[$i]);

      $name = str_replace('#','', $name); // remove type signifiers, TODO: generalize

      // Only return non-NULL values
      if ($val !== NULL)
        $ret[$name] = $val;
    }

    return (object)$ret;
  }

  // Remove type identifiers, TODO: generalize
  if (strncmp($var, '#', 1) === 0) {
    $var = substr($var, 1); 
    $numeric = TRUE;
  }

  $val = $def;

  // Accepts name of variable in A:B format, 
  $vars = explode(':', $var);
  for ($i = 0; $i < count($vars); $i++) {
    if (isset($req[$vars[$i]]))
      $val = $req[$vars[$i]];
  }

  if ($val === NULL)
    return $val;

  if ($numeric) {
    if ($val === 'false' || $val === 'off' || $val === 'no')
      return FALSE;
    if ($val === 'true' || $val === 'on' || $val === 'yes')
      return TRUE;
  }

  if (is_array($val))
    return $val;
  if (is_string($val))
    return trim($val);
  return $val;
}

function get_site_id($site) {
  if ($site > 0)
    return $site;

  global $wpdb;
  $id = $wpdb->get_var($wpdb->prepare(
    "SELECT blog_id FROM charity WHERE domain=%s",
    $site));
  if ($id > 0)
    return $id;

  return NULL;
}


class ApiQuery {
  var $wheres = array();
  var $fields = array();
  var $orders = array();
  var $tables = array();
  var $groups = array();

  public function __construct($fields = NULL, $tables = NULL) {
    if ($fields != NULL)
      $this->fields($fields);
    if ($tables != NULL)
      $this->tables($tables);
  }

  public function require_wheres() {
    if (count($this->wheres) == 0)
      throw new Exception("Use search or filter to fill this list.");
  }

  // This function finds any values in the data set that are in the form ('A.B'=>V) and
  // turns them into (A=>(B=>V))
  public function xform($row) {
    foreach ($row as $k=>$v) {
      $path = explode('.', $k);
      if (count($path) < 2)
        continue;

      unset($row->$k);
      for ($c = count($path) - 1; $c >= 1; $c--) {
        $k = $path[$c];
        $v = (object)array( $k => $v );
      }
      $k = $path[0];
      $row->$k = $v;
    }
    return $row;
  }

  public function get_results() {
    $args = func_get_args();
    array_unshift($args, $this->sql());

    global $wpdb;
    $results = call_user_func_array(array($wpdb,'get_results'), $args);

    $ret = array();
    for ($i = 0; $i < count($results); $i++) {
      $r = $this->xform($results[$i]);
      if ($r !== NULL)
        $ret[] = $r;
    }

    return $ret;
  }

  public function map_results($fn, $results = NULL) {
    if ($results === NULL)
      $results = $this->get_results();

    $ret = array();
    for ($i = 0; $i < count($results); $i++) {
      $r = call_user_func($fn, $results[$i]);
      if ($r == NULL)
        continue;
      $r = $this->xform($r);
      if ($r == NULL)
        continue;
      $ret[] = $r;
    }

    return $ret;
  }

  public function get_row() {
    $args = func_get_args();
    array_unshift($args, $this->sql());

    global $wpdb;
    return call_user_func_array(array($wpdb,'get_row'), $args);
  }

  public function get_col() {
    $args = func_get_args();
    array_unshift($args, $this->sql());

    global $wpdb;
    return call_user_func_array(array($wpdb,'get_col'), $args);
  }

  public function get_var() {
    $args = func_get_args();
    array_unshift($args, $this->sql());

    global $wpdb;
    return call_user_func_array(array($wpdb,'get_var'), $args);
  }

  public function where($where) {
    $args = func_get_args();

    if (count($args) == 1)
      $this->wheres[] = $where;
    else {
      global $wpdb;
      $this->wheres[] = call_user_func_array(array($wpdb,'prepare'), $args);
    }
  }

  public function where_expr($var, $expr) {
    $where = $this->build_expr($var, $expr);
    if (empty($where))
      $this->wheres[] = $wpdb->prepare("$var = %s", $expr);
    else
      $this->wheres[] = $where;
  }

  public function fields($fields) {
    $args = func_get_args();

    if (count($args) == 1)
      $this->fields[] = $fields;
    else {
      global $wpdb;
      $this->fields[] = call_user_func_array(array($wpdb,'prepare'), $args);
    }
  }
  public function field($field) {
    $args = func_get_args();
    call_user_func_array(array($this,'fields'), $args);
  }

  public function tables($tables) {
    $args = func_get_args();

    if (count($args) == 1)
      $this->tables[] = $tables;
    else {
      global $wpdb;
      $this->tables[] = call_user_func_array(array($wpdb,'prepare'), $args);
    }
  }
  public function table($table) {
    $args = func_get_args();
    call_user_func_array(array($this,'tables'), $args);
  }

  public function orders($orders) {
    $this->orders[] = $orders;
  }
  public function order($order) {
    $this->orders($order);
  }

  public function groups($groups) {
    $this->groups[] = $groups;
  }
  public function group($group) {
    $this->groups($group);
  }

  public function sql() {
    $fields = static::sql_fields($this->fields);
    $tables = static::sql_tables($this->tables);
    $wheres = static::sql_wheres($this->wheres);
    $groups = static::sql_groups($this->groups);
    $orders = static::sql_orders($this->orders);

    $sql = "
      SELECT
        $fields
      FROM $tables
      $wheres
      $groups
      $orders
      LIMIT 5000";

    if ($_REQUEST['sql'] == 'yes') {
      pre_dump($sql);
    } else if ($_REQUEST['sql'] == 'raw') {
      echo $sql;
    }

    return $sql;
  }

  protected function sql_fields(&$fields) {
    return implode(",\n", $fields);
  }

  protected function sql_groups(&$groups) {
    if (count($groups) == 0)
      return "";
    return "GROUP BY ". implode(",\n", $groups);
  }

  protected function sql_orders(&$orders) {
    if (count($orders) == 0)
      return "";
    return "ORDER BY " . implode(",", $orders);
  }

  protected function sql_tables(&$tables) {
    return implode("\n", $tables);
  }

  protected function sql_wheres(&$wheres) {
    if (!is_array($wheres)) {
      if (empty($wheres))
        return "";

      return "WHERE $wheres";
    }

    if (count($wheres) == 0)
      return "";
    return "WHERE (" . implode(")\n  AND (", $wheres) . ")";
  }

  protected function build_expr($var, $expr) {
    global $wpdb;

    $matches = array();
    if (preg_match('/^(\!|not )/', $expr, $matches)) {
      return "not (" . $this->build_expr($var, str_replace($matches[1], '', $expr)) . ")";
    }

    $old_expr = $expr;
    $expr = str_replace(' ','', $expr);

    if ($expr == "empty" || $expr == "(none)") {
      return "IFNULL($var,'') = ''";
    }

    // X%
    while (preg_match('/([-+]?[0-9]*\.?[0-9]+)\%/', $expr, $matches)) {
      $expr = str_replace($matches[0], $matches[1] / 100.0, $expr);
    }

    // X-Y
    if (preg_match('/([-+]?[0-9]*\.?[0-9]+)(\-|to)([-+]?[0-9]*\.?[0-9]+)/', $expr, $matches)) {
      return $wpdb->prepare("$var >= %s and $var <= %s", $matches[1], $matches[3]);
    }

    // X+
    if (preg_match('/([-+]?[0-9]*\.?[0-9]+)\+/', $expr, $matches)) {
      return $wpdb->prepare("$var >= %s", $matches[1]);
    }

    // ~X
    if (preg_match('/\~(.*)/', $expr, $matches)) {
      $s = str_replace("'",'', $wpdb->prepare("%s", $matches[1]));
      return "$var LIKE '%$s%'";
    }

    // <=, =, >=, >, <
    if (preg_match('/(\<\=|\<|\=|\>|\>=)(.*)/', $expr, $matches)) {
      return $wpdb->prepare("$var {$matches[1]} %s", $matches[2]);
    }

    // X,Y,Z
    $words = array_map('trim', as_array($old_expr));
    if (count($words) > 1) {
      for ($i = 0; $i < count($words); $i++) {
        $words[$i] = $wpdb->prepare('%s', $words[$i]);
      }
      return "$var in (" . implode(',', $words) . ")";
    } else if (count($words) == 1) {
      return $wpdb->prepare("$var = %s", $words[0]);
    }

    return NULL;
  }

}



class Api {
  public static function process() {

    // Set up permissions
    global $ADMIN_PARTNER, $ADMIN_PERMISSIONS;
    if (!empty($ADMIN_PARTNER))
      $ADMIN_PERMISSIONS = "#partner/$ADMIN_PARTNER#";

    try {
      if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'POST') {
        // Undo WordPress's magic quotes yuck
        $_GET = stripslashes_deep($_GET);
        $_REQUEST = stripslashes_deep($_REQUEST);
        $_POST = stripslashes_deep($_POST);
      }

      switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': // create new
          $results = static::create($_REQUEST);
          break;

        case 'PUT': // update existing
          $results = static::update($_REQUEST);
          break;

        case 'DELETE': // delete existing
          $results = static::delete($_REQUEST);
          break;

        case 'GET': // find existing
          $results = static::get($_REQUEST);
          break;
      }

    } catch (Exception $e) {
      $error = eor($e->getMessage(), 'Error');
    }

    static::reply($results, $error, empty($error) ? static::getMeta($_REQUEST) : null);
  }

  public static function reply($data, $error = null, $meta = null) {

    /* Not needed yet; CanJS can take results in our format
    if (!isset($_SERVER['HTTP_X_API'])) {
      $ret = $data;
    }
    */

    if (is_array($meta) || is_object($meta))
      $ret = (array)$meta;
    else if ($meta !== null)
      $ret = array('meta' => $meta);
    else
      $ret = array();

    if ($data !== NULL)
      $ret['data'] = $data;
    if ($error !== NULL) {
      $ret['error'] = $error;
      if (headers_sent()) {
        header('X-PHP-Response-Code: 400', true, 400);
      }
    }

    if (!headers_sent()) {
      header('Cache-Control: no-cache, must-revalidate');
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header('Content-type: application/json');
    }

    switch ($_SERVER['REQUEST_METHOD']) {
      case 'PUT': // update existing
        if (!empty($error)) {
          echo $error;
          die;
        }
        // else fall through
      case 'POST': // create new
        if ($error == NULL) {
          // no column data, etc. just the updated info
          echo self::output_json($data);
          break;
        }
        // else fall through
      case 'GET': // find existing
        echo self::output_json($ret);
        break;

      case 'DELETE': // delete existing
        break;
    }

    if (!isset($_SERVER['HTTP_X_API'])) {
      global $dump;
      echo $dump;
    }

    exit;
  }

  public static function hasPermission($perm) {
    global $ADMIN_PERMISSIONS;
    if ($ADMIN_PERMISSIONS == ALL_ADMIN_PERMISSIONS || $perm == NULL || $perm === TRUE)
      return TRUE;

    $check = explode("/", implode("/", array_filter(explode("/", $perm))));
    if (count($check) == 0)
      return TRUE;
    $check1 = $check[0];

    if (strstr($ADMIN_PERMISSIONS, "#$check1#") !== FALSE)
      return TRUE;
    if (count($check) == 1)
      return FALSE;

    $check1 .= "/" . $check[1];
    return strstr($ADMIN_PERMISSIONS, "#$check1#") !== FALSE;
  }

  public static function output_json(&$data) {
    $json = indent_json(json_encode($data, JSON_NUMERIC_CHECK));

    if (isset($_GET['callback']))
      echo $_GET['callback'] . "($json)";
    else
      echo $json;
  }

  public static function expand(&$results, $record = NULL) {
  }

  public static function create($req) {
    return static::update($req);
  }

  public static function get($req) {
    throw new Exception("not supported");
  }

  public static function getOne($req, $params = NULL) {
    if (empty($req))
      return NULL;

    if (!is_array($req))
      $req = array('id' => $req);
    if (is_array($params))
      $req = array_merge($req, $params);
    $results = static::get($req);
    if (count($results) < 1)
      return NULL;

    return $results[0];
  }

  public static function setOne($id, $req) {
    $req = (array)$req;
    $req['id'] = $id;

    static::update($req);
  }

  public static function update($req) {
    throw new Exception("update not supported");
  }

  public static function delete($req) {
    throw new Exception("delete not supported");
  }

  public static function getMeta($req) {
    $meta = new stdClass;

    $meta->columns = static::getColumns($req);
    $meta->actions = static::getActions($req);
    $meta->forms = static::getForms($req);

    return $meta;
  }

  public static function getColumns($req) {
  }

  public static function getActions($req) {
  }

  public static function getForms($req) {
  }

  // HELPER FUNCTIONS:

  // Update the various promos included in a POST
  public static function save_gallery(&$record) {
    if (isset($record->gallery)) {
      foreach ($record->gallery as $name=>$promo) {
        $promo = (object)$promo;
        if (isset($promo->ref) && isset($promo->html))
          PromoApi::update($promo);
      }
      unset($record->gallery);
    }
  }

  // helper to validate a slug for our purposes, also may set it if empty
  public static function make_slug(&$slug, $default) {
    $slug = sanitize_title(!empty($slug) ? $slug : $default);
    $slug = strtolower($slug);
  }

  public static function set_blog_id(&$req, $enforce = TRUE) {
    // Switch to the proper site
    if (!empty($req->site))
      $req->blog_id = get_site_id($req->site);

    global $blog_id;
    if ($req->blog_id <= 0)
      $req->blog_id = $blog_id;

    if ($req->blog_id <= 0 || ($enforce && ($req->blog_id == 1))) {
      throw new Exception("invalid site");
    }
  }

  protected static function field($label, $field, $type = NULL, $opts = NULL) {
    $a = array(
      'label' => $label
    );

    if (is_array($field))
      $a = array_merge($a, $field);
    else
      $a['name'] = $field;

    if (is_array($type))
      $a = array_merge($a, $type);
    else if ($type != NULL)
      $a['type'] = $type;

    if (is_array($opts))
      $a = array_merge($a, $opts);

    return $a;
  }

  protected static function permitted($perm, $val) {
    if (static::hasPermission($perm))
      return $val;
    return null;
  }

  protected static function ref($label, $ref, $perm = "--") {
    if ($perm === "--")
      $perm = $ref;
    return static::action($label, $ref, $perm);
  }

  protected static function action($label, $ref, $perm = TRUE) {
    return static::permitted($perm, 
      array('label' => $label, 'ref' => $ref)
    );
  }

  // pass menu items as arguments after label
  protected static function menu($label) {
    $args = func_get_args();
    array_shift($args);

    if (is_array($label))
      $a = $label;
    else
      $a = array('label' => $label);
    $menu = array();

    foreach ($args as $arg) {
      if ($arg != NULL)
        $menu[] = $arg;
    }
    if (count($menu) == 0)
      return NULL;

    // Collapse if it just contains a single menu
    if (count($menu) == 1 && is_array($menu[0])) {
      // $menu[0]['label'] = $label;
      return $menu[0];
    }

    $a['items'] = $menu;
    return $a;
  }

  public static function unpack_data(&$row, $field = 'data') {
    if (!$row->{$field}) {
      unset($row->{$field});
      return FALSE;
    }

    $json = $row->{$field};
    if (is_string(json))
      $row->{$field} = json_decode($json, TRUE);
    return TRUE;
  }

}


function protect_email(&$email) {
  $em = explode('@', $email);
  if (count($em) != 2 ||
      stripos($em[1], 'seeyourimpact.org') !== FALSE || 
      $em[1] == 'tfbnw.net') {
    $email = '';
    return;
  }

  $email = str_replace('@test.seeyourimpact.com','', $email);
}

// "Registers" an API for direct execution.  We don't actually need to keep
// a registry of APIs, but when the file being registered matches the current PHP
// script, we execute its ::process() method to run the API
function register_api($name, $class) {
  if ($name == $_SERVER['SCRIPT_FILENAME']) {
    call_user_func(array($class, 'process'));
  }
}
