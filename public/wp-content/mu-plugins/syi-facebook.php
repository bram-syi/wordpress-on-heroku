<?php

// SYI implementation of Facebook

// test: https://developers.facebook.com/apps/470745049637347/opengraph
// live: https://developers.facebook.com/apps/123397401011758/opengraph

// since this will likely be run on a remote server at some point in the future,
// NO WORDPRESS FUNCTIONS ALLOWED. $wpdb is the only way to talk to Wordpress
// that will be allowed here, because it can be ported easily. In fact, only
// the sql() method requires $wpdb, so $wpdb doesn't even need to be ported.
// The sql() method alone has to be implemented.
//
// EXCEPTIONS
// fundraiser_image_src (wp-content/mu-plugins/images.php)

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/facebook/facebook.php');

class SyiFacebook extends Facebook {
  //
  // instance members
  //

  // for users: whether we use custom open graph actions, or post straight
  // to /feed and /photos
  public $use_open_graph = true;

  // testusers are the only users whose OG calls actually go through
  public $no_live_users = true;

  // cache of test users
  protected $test_users = null;

  // cache of user ids
  protected $wordpress_user_id;
  protected $facebook_user_id;

  // What we are allowed to do on behalf of the user this instance represents.
  // - If $use_open_graph is true, then "open_graph" is the only permission
  //   that matters, it is checked for all our custom actions.
  // - If $use_open_graph is NOT true, then each invididual action (some of
  //   which are /me/feed, others /me/photos, etc) are checked.
  //
  //   open_graph = true/false
  //   publish_story = true/false
  //   publish_thanks = true/false
  //   publish_invite = true/false
  //   publish_update = true/false
  //
  // Everything true/allowed by default, if this instance is backed by a valid
  // WordPress user.
  //
  // TODO: if we really are settling on Open Graph actions, we should just cut
  // out all the other non-OG code. Code that calls api('/me/feed'), etc.
  protected $perms;

  //
  // static member
  //

  // only access via static method "name_space()"
  protected static $name_space;

  //
  // instance functions
  //

  public function __construct($wp_user_id=0) {
    $config = array(
      'appId' => self::setting('appid'),
      'secret' => self::setting('secret'),
    );
    parent::__construct($config);

    $this->wordpress_user_id = $wp_user_id;
    if ($wp_user_id > 0) {
      $rows = self::sql('select meta_key, meta_value from wp_usermeta where meta_key like "fb_%" and user_id = '.$wp_user_id);
      foreach ($rows as $i => $row) {
        if ($row->meta_key == 'fb_id') {
          $this->facebook_user_id = $row->meta_value;
          $this->user = $row->meta_value;
          $this->setPersistentData('user_id', $row->meta_value);
        }
        if ($row->meta_key == 'fb_access_token') {
          $this->setAccessToken($row->meta_value);
          $this->setPersistentData('access_token', $row->meta_value);
        }
        if ($row->meta_key == 'fb_perms') {
          $this->perms = json_decode($row->meta_value);
        }
      }
    }

    if (!$this->perms) {
      if ($wp_user_id) {
        // we will create permissions for this user
        $this->perms = (object)array(
          'open_graph' => true,
          'publish_story' => true,
          'publish_thanks' => true,
          'publish_invite' => true,
          'publish_update' => true,
        );

        self::sql('insert wp_usermeta (user_id, meta_key, meta_value) values (%d, %s, %s)',
          $wp_user_id,
          'fb_perms',
          json_encode($this->perms)
        );
      }
      else {
        // in this case, we were constructed without a valid wp user, so we will never
        // blindly call api methods in such a state
        $this->perms = (object)array(
          'open_graph' => false,
          'publish_story' => false,
          'publish_thanks' => false,
          'publish_invite' => false,
          'publish_update' => false,
        );
      }
    }

    self::log('SyiFacebook::__construct: fb_user_id: '.$this->facebook_user_id.', wp_user_id: '.$this->wordpress_user_id);
  }

  // $fundraiser: url to the fundraiser
  public function publish_invite($post_id, $personal_message='') {
    self::log("SyiFacebook::publish_invite: $post_id, $personal_message");
    if ($this->fake()) {
      return;
    }

    if (!$personal_message) {
      $personal_message = "I'm inviting people to this fundraiser, check it out!";
    }

    $fundraiser = self::fundraiser_as_array($post_id);

    if ($this->use_open_graph) {
      if (!$this->perms->open_graph) {
        error_log("publish_invite: not allowed via open graph");
        return;
      }

      $params = array(
        'access_token' => $this->accessToken,
        'fundraiser' => $fundraiser['url'],
        'personal_message' => $personal_message,
      );
      if (0) {
        $params['fb:explicitly_shared'] = true;
      }

      return $this->api('/'.$this->facebook_user_id.'/'.self::name_space().':invite', 'POST', $params);
    }
    else {
      if (!$this->perms->publish_invite) {
        error_log("publish_invite: not allowed via /me/feed");
        return;
      }
      // https://developers.facebook.com/docs/reference/api/post/
      $fundraiser['message'] = $personal_message;
      self::swap($fundraiser, 'url', 'link');
      return $this->api('/'.$this->facebook_user_id.'/feed', 'POST', $fundraiser);
    }
  }

  // $blog_id: the WordPress blog_id
  // $post_id: the WordPress post_id
  // $params: extra params, ONLY USED if $use_open_graph is false, possibilities include:
  //   * $params = array() (default)
  //     Story is publish as a Facebook Post on user's /me/feed
  //   * $params = array( 'as_photo' => 1)
  //     This will publish the story as a Facebook Photo to the user's photo
  //     stream (ie, "/me/photos")
  //   * $params = array( 'to_page' => <facebook page id> )
  //     This publishes the story as a Facebook Photo, but to a Page, rather
  //     than an individual user's profile. NOTE: requires a page token, which
  //     this method assumes is already set up.
  public function publish_story($blog_id, $post_id, $params=array()) {
    self::log("SyiFacebook::publish_story: $blog_id, $post_id, ".var_export($params, true));
    if ($this->fake()) {
      return;
    }

    $story = self::story_as_array($blog_id, $post_id);

    if ($this->use_open_graph) {
      if (!$this->perms->open_graph) {
        error_log("publish_story: not allowed via open graph");
        return;
      }

      $params = array(
        'access_token' => $this->accessToken,
        'story' => $story['url']
      );
      if (0) {
        $params['fb:explicitly_shared'] = true;
      }
      return $this->api('/'.$this->facebook_user_id.'/'.self::name_space().':receive_story', 'POST', $params);
    }
    else {
      if (!$this->perms->publish_story) {
        error_log("publish_story: not allowed via /me/feed or /me/photos");
        return;
      }
      if ($params['as_photo']) {
        // https://developers.facebook.com/docs/reference/api/photo/
        self::swap($story, 'title', 'name');
        self::swap($story, 'image', 'source');
        $story['url'] = $story['source'];
        $graph = '/'.$this->facebook_user_id.'/photos';
      }
      else if ($params['to_page']) {
        // this requires a real POST, not some nice OG api call
        return $this->post_story_to_page($story, $blog_id, $params['to_page']);
      }
      else {
        // https://developers.facebook.com/docs/reference/api/post/
        $story['link'] = $story['url'];
        $story['message'] = "I received a story about my donation's impact!";
        $graph = '/'.$this->facebook_user_id.'/feed';
      }
      return $this->api($graph, 'POST', $story);
    }
  }

  // $donation: the `donationID` column of the `donation` table
  public function publish_donation($donation_id, $message=null) {
    self::log("SyiFacebook::publish_donation: $donation_id, $message");
    if ($this->fake()) {
      return;
    }

    $rows = self::sql('select domain from wp_site');
    $object_url = 'http://' . $rows[0]->domain . '/';

    $sql = "
    SELECT
      d.donationID, d.donationDate,
      donor.email,
      IFNULL(c1.guid, c2.guid) AS fundraiser_url,
      IFNULL(c1.theme, c2.theme) AS fundraiser_theme
    FROM donation d
    LEFT JOIN donationGiver donor ON donor.id=d.donorID

    LEFT JOIN donationGifts dg ON dg.donationID=d.donationID
    LEFT JOIN campaigns c1 ON c1.post_id=dg.event_id

    LEFT JOIN donationAcctTrans dat ON dat.paymentID=d.paymentID AND dat.amount > 0
    LEFT JOIN donationAcct da ON dat.donationAcctId=da.id
    LEFT JOIN campaigns c2 ON c2.post_id=da.event_id

    WHERE d.donationID = %d

    ORDER BY d.donationID DESC";

    $rows = self::sql($sql, $donation_id);
    if (count($rows) == 1) {
      $object_url = $rows[0]->fundraiser_url;
    }
    else {
      trace_up("expected exactly 1 row, but got ".count($rows)." instead: ".var_export($rows,true));
      return;
    }

    if ($message === null) {
      $theme_data = self::get_facebook_theme_data($rows[0]->fundraiser_theme);
      $message = $theme_data->default_donation_message;
    }

    if ($this->use_open_graph) {
      if (!$this->perms->open_graph) {
        error_log("publish_donation: not allowed via open graph");
        return;
      }

      $params = array(
        'access_token' => $this->accessToken,
        'fundraiser' => $object_url,
        'message' => $message,
      );
      if (0) {
        $params['fb:explicitly_shared'] = true;
      }
      return $this->api('/'.$this->facebook_user_id.'/'.self::name_space().':donate', 'POST', $params);
    }
    else {
      if (!$this->perms->publish_thanks) {
        return;
        error_log("publish_donation: not allowed via /me/feed");
      }
      // https://developers.facebook.com/docs/reference/api/post/
      $post = array();
      $post['message'] = $message;
      $post['link'] = $object_url;
      $post['caption'] = 'here is a caption';
      $post['application'] = '{name:"seeyourimpact.org", id:"'.$this->getAppId().'"}';
      $post['actions'] = '[{name:"foo", link:"http://dev3.seeyourimpact.com/foo"}]';
      $post['picture'] = '';
      return $this->api('/'.$this->facebook_user_id.'/feed', 'POST', $post);
    }
  }

  // $update_id: the post id of the update
  // $fundraiser_id: the post id of the fundraiser
  // We need the fundraiser ID because there isn't a non-WordPress way to get
  // the url of the update. So we use the fundraiser's guid, and just append
  // the update portion of the url.
  public function publish_update($update_id, $fundraiser_id) {
    self::log("SyiFacebook::publish_update: $update_id, $fundraiser_id");
    if ($this->fake()) {
      return;
    }

    $update = self::update_as_array($update_id, $fundraiser_id);

    if ($this->use_open_graph) {
      if (!$this->perms->open_graph) {
        error_log("publish_update: not allowed via open graph");
        return;
      }

      $params = array(
        'access_token' => $this->accessToken,
        'update' => $update['url'],
      );
      if (0) {
        $params['fb:explicitly_shared'] = true;
      }

      return $this->api('/'.$this->facebook_user_id.'/'.self::name_space().':post_update', 'POST', $params);
    }
    else {
      if (!$this->perms->publish_update) {
        error_log("publish_update: not allowed via /me/feed");
        return;
      }
      // https://developers.facebook.com/docs/reference/api/post/
      self::swap($update, 'url', 'link');
      return $this->api('/'.$this->facebook_user_id.'/feed', 'POST', $update);
    }
  }

  // figures out where the image for the given story is in the *filesystem*,
  // and then POSTs the story to a Facebook Page
  // $story: story array from story_as_array()
  // $blog_id: numeric id from wp_blogs table
  // $fb_page_id: numeric id of Facebook Page
  public function post_story_to_page($story, $blog_id, $fb_page_id) {
    $image_file = preg_replace('/^.*wp-content/', ABSPATH . 'wp-content', $story['image']);

    if (! file_exists($image_file)) {
      error_log("post_story_to_page: file doesn't exist: ".var_export(
        compact('story', 'image_file', 'fb_page_id'), true));
      return;
    }

    $rows = self::sql("select option_value from wp_${blog_id}_options where option_name = 'fb_page_access_token'");
    $token = $rows[0]->option_value;
    if (!$token) {
      error_log("post_story_to_page: no page access token found: ".var_export(
        compact('story', 'image_file', 'fb_page_id'), true));
      return;
    }

    $postdata = array(
      "source" => "@/".realpath($image_file).";type=image/jpeg",
      "message" => $story['description'],
      "access_token" => $token,
    );

    // manually do curl operations, because makeRequest() doesn't do thse multipart/form-data
    // uploads. or at least, i can't figure out how to make it work.
    $ch = curl_init("https://graph.facebook.com/$fb_page_id/photos");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    $response = curl_exec ($ch);
    curl_close($ch);

    return json_decode($response, true);
  }

  // expose this, because we pull it out persistent data and store it in DB
  public function userToken() {
    $token = $this->accessToken;

    if (!$token) {
      $token = $this->getPersistentData('access_token');
    }

    if (!$token) {
      $token = $this->accessToken;
    }

    return $token;
  }

  // returns the value of one of the permission keys
  // NOTE: this is only useful for showing UI, as all of the publish_*
  // methods each check permissions before calling Facebook's API.
  public function get_permission($name) {
    $value = $this->perms->$name;
    if ($value === null) {
      $value = true;
      $this->set_permission($name, $value);
    }
    return $value;
  }

  // Sets a permission key in $this->perms, and writes that change to the
  // database.
  //
  // Returns the previous permission value, or "true" if it doesn't exist.
  public function set_permission($name, $value) {
    $old = $this->perms->$name;
    if ($old === null) {
      $old = true;
    }
    $this->perms->$name = $value;

    if ($this->wordpress_user_id) {
      self::sql('update wp_usermeta set meta_value = %s where meta_key = "fb_perms" and user_id = %d',
        json_encode($this->perms), $this->wordpress_user_id
      );
    }

    return $old;
  }

  // If $no_live_users is set, this will return true, UNLESS the active user
  // is a test user (ie, fake user), in which case it returns false.
  //
  // In addition, if $no_live_users is set, and the active user is a live user,
  // the active user will be overridden with a random test user. In this way,
  // live users will generate fake test user traffic.
  //
  // Note: this means this function only returns true if there is a problem
  // picking a random test user.
  protected function fake() {
    $faking = false;

    if ($this->no_live_users) {
      $faking = true;

      if ($this->is_test_user()) {
        $faking = false;
      }
      else {
        // if we are disallowing live users, AND we are a live user, re-route
        // API calls to a random test user
        $count = count($this->test_users);
        if ($count) {
          $random = rand(0, $count - 1);
          $user = (object)$this->test_users[$random];
          $old_user_id = $this->facebook_user_id;

          $this->facebook_user_id = $user->id;
          $this->user = $user->id;
          $this->setPersistentData('user_id', $user->id);
          $this->setAccessToken($user->access_token);
          $this->setPersistentData('access_token', $user->access_token);
          $this->accessToken = $user->access_token;
          $this->perms = (object)array(
            'open_graph' => true,
            'publish_story' => true,
            'publish_thanks' => true,
            'publish_invite' => true,
            'publish_update' => true
          );

          $me = (object)$this->api("/me");
          self::log("fake: re-assigning FB user id $old_user_id to $me->name: ".json_pretty($user));

          $faking = false;
        }
        else {
          error_log("fake: no test users to randomly pick from");
        }
      }
    }

    self::log("fake: $faking");
    return $faking;
  }

  protected function is_test_user() {
    if ($this->test_users === null) {
      // need to fetch test user IDs from FB
      $response = $this->api('/' . $this->getAppId() . '/accounts/test-users',
        'GET',
        array('access_token' => $this->getApplicationAccessToken())
      );
      if (array_key_exists('data', $response)) {
        $this->test_users = $response['data'];
      }
      else {
        error_log("json response from open graph doesn't contain 'data' key: ".var_export($response,true));
      }
    }

    $ret = false;
    $id = $this->getUser();
    if ($id) {
      foreach ($this->test_users as $i => $u) {
        if ($id == $u['id']) {
          $ret = true;
        }
      }
    }
    else {
      self::log("is_test_user: no facebook user ID");
    }

    self::log("is_test_user: $ret");
    return false;
  }

  //
  // static functions
  //

  // returns an array of open graph meta properties for the given fundraiser
  // $fundraiser: url of the form "<root>/members/user_login"
  public static function fundraiser_as_array($post_id) {
    $fundraisers = self::sql('select * from campaigns where post_id = %d', $post_id);
    $posts = self::sql('select * from wp_1_posts where id = %d', $fundraisers[0]->post_id);
    $desc = $posts[0]->post_content;

    return array(
      'app_id'      => self::setting('appid'),
      'url'         => preg_replace('/\/?$/', '/', $fundraisers[0]->guid), # trailing slash required
      'type'        => self::name_space() . ':fundraiser',
      'title'       => $fundraisers[0]->post_title,
      'description' => $desc,
      'image'       => fundraiser_image_src($fundraisers[0]->post_id),
    );
  }

  // returns an array of open graph meta properties for the given story
  // $story: url of the form "<root>/<stuff>/<slug>/"
  public static function story_as_array($blog_id, $post_id) {
    $stories = self::sql(
      'select post_id, post_image, guid from donationStory where blog_id = %d and post_id = %d',
      $blog_id,
      $post_id
    );

    $posts = self::sql(
      "select post_title, post_content from wp_${blog_id}_posts where id = %d",
      $stories[0]->post_id
    );

    // story post_content is a mangled mess of shit
    $desc = $posts[0]->post_content;
    $desc = preg_replace('/\[caption.*\[\/caption\]/', '', $desc);
    $desc = preg_replace('/^Dear.*\n/', '', $desc);

    return array(
      'app_id'      => self::setting('appid'),
      'url'         => preg_replace('/\/?$/', '/', $stories[0]->guid), # trailing slash required
      'type'        => self::name_space() . ':story',
      'title'       => $posts[0]->post_title,
      'description' => $desc,
      'image'       => $stories[0]->post_image,
    );
  }

  public static function update_as_array($update_id, $fundraiser_id) {
      $fundraiser = self::fundraiser_as_array($fundraiser_id);

      $rows = self::sql('select * from wp_1_posts where id = %d', $update_id);
      if (count($rows) != 1) {
        error_log("wp post for update $update_id not found");
        return;
      }

      $update_post = $rows[0];

      $update = array(
        'app_id'      => self::setting('appid'),
        'url'         => $fundraiser['url'] . "updates/?update=$update_id",
        'type'        => self::name_space() . ':update',
        'image'       => $fundraiser['image'],
        'title'       => $update_post->post_title,
        'description' => $update_post->post_content,
      );

      $rows = self::sql('select * from wp_1_postmeta where post_id = %d', $update_id);
      foreach ($rows as $row) {
        if ($row->meta_key == 'video') {
          $update['video'] = $row->meta_value;
        }
      }

      return $update;
  }

  public static function name_space() {
    if (!self::$name_space) {
      self::$name_space = self::setting('namespace');
    }
    return self::$name_space;
  }

  public static function array_as_metatags($arr) {
    $html = '';
    foreach ($arr as $name => $value) {
      $value = str_replace('"', '&quot;', $value);
      $namespace = $name == 'app_id' ? 'fb' : 'og';
      $html .= "<meta property=\"$namespace:$name\" content=\"$value\"/>\n";
    }
    return $html;
  }

  // similar to trace_up(), except this logs via self::log instead
  // of error_log(), and also logs as one single line
  public static function trace($str='') {
    $message = "SyiFacebook::trace";
    if ($str) {
      $message .= ": $str";
    }

    $frames = debug_backtrace();
    array_shift($frames);

    $functions = array();
    foreach ($frames as $f) {
      $functions[] = $f['function'];
    }

    $message .= ' ' . implode(',', $functions);

    self::log($message);
  }

  // returns all rows matching the given query
  // $args[0]: sql query with parameter placeholders
  // $args[1,2...]: sql paramters
  protected static function sql(/*poly*/) {
    global $wpdb;

    $test = false;
    $args = func_get_args();
    if ($test) error_log("SyiFacebook::sql: args: ".var_export($args,true));

    $query = array_shift($args);

    $sql = count($args) ? $wpdb->prepare($query, $args) : $query;
    if ($test) error_log("SyiFacebook::sql: sql: $sql");
    return $wpdb->get_results($sql);
  }

  // $theme: the name of a theme (out of the `theme_data` table)
  protected static function get_facebook_theme_data($theme) {
    $data = (object)array();

    $rows = self::sql('select contents from theme_data where name = %s', $theme);
    if ($rows == NULL || count($rows) == 0)
      return NULL;

    $content = $rows[0]->contents;

    if ($content) {
      $json = json_decode($content);
      if ($json) {
        if (property_exists($json, 'facebook')) {
          $data = $json->facebook;
        }
        else {
          $data->default_donation_message = '';
        }
      }
      else {
        error_log("get_facebook_theme_data: failed to parse content of theme $theme as json");
      }
    }
    else {
      error_log("get_facebook_theme_data: no content in database for theme $theme");
    }

    return $data;
  }

  // get a setting
  protected static function setting($name) {
    $sql = 'select option_value from wp_1_options where option_name = %s';
    $rows = self::sql($sql, "fb_$name");
    if (isset($rows[0]))
      return $rows[0]->option_value;
    return null;
  }

  // this function copies $arr[$old] to $arr[$new], and then unsets
  // $arr[$old]
  protected static function swap(&$arr, $old, $new) {
    $arr[$new] = $arr[$old];
    unset($arr[$old]);
  }

  // write to our private facebook log
  protected static function log($str) {
    SyiLog::log('facebook', $str);
  }

  //
  // overrides
  //

  // add more debugging when throwing exceptions
  protected function throwAPIException($result) {
    trace_up("SyiFacebook: throwing an exception: ".var_export($result, true));
    parent::throwAPIException($result);
  }

  // add conditional debugging when calling FB API
  public function api() {
    self::log("SyiFacebook::api: ".json_pretty(array_intersect_key(get_object_vars($this), array(
      'accessToken' => 1, 'user' => 1, 'wordpress_user_id' => 1, 'facebook_user_id' => 1))));

    $args = func_get_args();
    self::log("SyiFacebook::api args: ".json_pretty($args));
    try {
      $response = call_user_func_array(array('parent', 'api'), $args);
      self::log("SyiFacebook::api response: ".json_pretty($response));
      return $response;
    }
    catch (Exception $e) {
      error_log("SyiFacebook::api exception: ".$e->getMessage());
      self::log("SyiFacebook::api exception: ".$e->getMessage());
    }
  }

}
