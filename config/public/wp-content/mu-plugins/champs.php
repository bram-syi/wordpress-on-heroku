<?

// Steve: removed
// add_action('admin_menu', 'add_champs_menu', SW_HOME_PRIORITY);

function add_champs_menu() {
  add_submenu_page(SW_HOME, 'Champion Support',
    'Champ Support',
    'manage_network', 'champs', 'champ_support_page');
}

function champ_support_page() {
  global $wpdb;

?>
<div class="wrap">
<div class="icon32" id="icon-users"><br></div>
<h2>Champion Support Tools</h2>
<?

$_GET = stripslashes_deep($_GET);
$_POST = stripslashes_deep($_POST);
$_REQUEST = stripslashes_deep($_REQUEST);

if (wp_verify_nonce($_REQUEST['champ_support'], 'fb_post')) {
  $args = array(
    'user_id' => $_REQUEST['user_id'],
    'message' => $_REQUEST['message'],
    'link' => $_REQUEST['link'],
    'link_name' => $_REQUEST['link_name'],
    'link_caption' => $_REQUEST['link_caption'],
    'link_description' => $_REQUEST['link_desc'],
    'photo' => $_REQUEST['photo']
  );
  $result = champ_support_fb_post($args);

  ?><div class="updated settings-error"><?

  if (is_string($result)) {
    ?><p><strong><?= $result ?></strong></p><?
  } else if (isset($result['error'])) {
    $error = $result['error'];
    ?><p>FaceBook says: <strong><?=xml_entities($error['message']) ?></strong>
      (<?=xml_entities($error['type']) ?> <?=xml_entities($error['code']) ?>)
    </p><?
  } else if (is_array($result)) {
    ?><p><strong><pre><?= print_r($result, TRUE) ?></pre></strong></p><?
  }

  ?></div><?
}
?>
<form method="POST">
<? wp_nonce_field('fb_post', 'champ_support'); ?>

<table class="form-table">
  <tr valign="top">
    <th scope="row"><label for="user_id">User ID</label></th>
    <td><input class="regular-text" id="user_id" name="user_id" value="<?= esc_attr($args['user_id']) ?>">
    <span class="description">User ID or username (will appear as real name on Facebook)</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="message">Message</label></th>
    <td><textarea style="width: 310px; height: 80px;" id="message" name="message"><?= xml_entities($args['message']) ?></textarea>
    <span class="description">Message that will appear in user's timeline (as him/her)</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="link">Post a link...</label></th>
    <td><input class="regular-text" id="link" name="link" value="<?= esc_attr($args['link']) ?>">
    <span class="description">URL of the link you want to attach</span>
    <br>
    <input class="regular-text" id="link_name" name="link_name" value="<?= esc_attr($args['link_name']) ?>">
    <span class="description">override "name" (optional)</span>
    <br>
    <input class="regular-text" id="link_caption" name="link_caption" value="<?= esc_attr($args['link_caption']) ?>">
    <span class="description">override "caption" (optional)</span>
    <br>
    <input class="regular-text" id="link_desc" name="link_desc" value="<?= esc_attr($args['link_description']) ?>">
    <span class="description">override "description" (optional)</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="photo">...or post a photo</label></th>
    <td><input class="regular-text" id="photo" name="photo" value="<?= esc_attr($args['photo']) ?>">
    <span class="description">URL to a story or photo to upload (must be from our site)</span>
    </td>
  </tr>
</table>

<p class="submit"><input type="submit" value="Post to facebook" class="button-primary" id="post-to-facebook" name="fb_post"></p>
</form>
<img  src="/wp-content/images/fb_post_sample.jpg" />
</div>
<?
}

function get_photo_file_from_url($url, $loop=FALSE) {
  $domain = parse_url( $url, PHP_URL_HOST );
  if (empty($domain))
    $domain = parse_url( "http://$url", PHP_URL_HOST );
  if (empty($domain))
    return NULL;

  $domain = strtolower($domain);
  if ("http://$domain" == SITE_URL) {
    $blog_id = 1;
  } else {
    $matches = array();
    if (preg_match("/(.*?).seeyourimpact.org/", $domain, $matches) == 0)
      return NULL;

    $blog_id = get_id_from_blogname($matches[1]);
    if ($blog_id <= 0)
      return NULL;
  }

  $path = parse_url( $url, PHP_URL_PATH );
  $file = ABSPATH . "wp-content/blogs.dir/$blog_id/files/". preg_replace("/^.*\/files\//", "", $path);

  if (file_exists($file))
    return $file;

  if ($loop) // Avoid over-recursion - just allow once
    return NULL;

  $slug = basename($path);

  switch_to_blog($blog_id);

/*
  global $wpdb;
  $id = $wpdb->get_var($wpdb->prepare(
    "select id from wp_{$blog_id}_posts
     where post_name=%s", $slug));
pre_dump($id);
*/
  $id = url_to_postid($path);
  if ($id <= 0) {
    restore_current_blog();
    return NULL;
  }

  $url = wp_get_attachment_url( get_post_thumbnail_id($id) );
  restore_current_blog();

  return get_photo_file_from_url($url, TRUE);
}

function champ_support_fb_post($args) {
  $user_id = $args['user_id'];
  $message = $args['message'];
  $link = $args['link'];
  $photo = $args['photo'];

  $user = get_userdata($user_id);
  if ($user == NULL)
    $user = get_userdatabylogin($user_id);
  if ($user == NULL) {
    return "Sorry, that's not a valid user ID";
  }

  if (!empty($link)) {
    // POST A LINK
    $wall_post = array(
      'message' => $message,
      'link' => $link
    );
    if (!empty($args['link_name']))
      $wall_post['name'] = $args['link_name'];
    if (!empty($args['link_caption']))
      $wall_post['caption'] = $args['link_caption'];
    if (!empty($args['link_description']))
      $wall_post['description'] = $args['link_description'];

    $facebook = new SyiFacebook(get_current_user_id());
    $result = $facebook->api('/me/feed', 'post', $wall_post);
  } else if (!empty($photo)) {
    $file = get_photo_file_from_url($photo);
    if (empty($file))
      return "That doesn't appear to be a valid photo on our site";

    // POST A PHOTO
    $wall_photo = array(
      'message' => $message,
      'image' => "@$file"
    );

    $result = $facebook->api('/me/photos', 'post', $wall_photo);
  } else if (!empty($message)) {
    // POST a status message
    $wall_post = array(
      'message' => $message
    );

    $result = $facebook->api('/me/feed', 'post', $wall_post);
  } else {
    return "Please enter a message, link, or photo.";
  }

  if (!empty($result['id']))
    return "OK!";

  return $result;
}
