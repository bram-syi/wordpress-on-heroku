<?
require_once('../wp-load.php');
require_once(ABSPATH . 'database/db-functions.php');
require_once(ABSPATH . 'wp-includes/syi/syi-includes.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');

nocache_headers();

define('MAX_FILE_SIZE', 5000000);

define('BUTTON_SUBMIT', "Submit for review");
define('BUTTON_PENDING', "Save pending");
define('BUTTON_PUBLISH', "Publish story");
define('BUTTON_SAVE', "Save Now");
define('BUTTON_UPDATE', "Update story");
define('BUTTON_PREVIEW', "Preview &raquo;");
define('BUTTON_VIEW', "View story &raquo;");
define('BUTTON_CANCEL', "Undo changes");
define('BUTTON_ADD_RECIPIENT', "Add Recipient");
define('BUTTON_ADD_NEW', "Add & New");

global $this_url;
$this_url = remove_query_arg('update');

if ($blog_id == 1)
  wp_die('Please visit this page on a charity sub-site.');

if (STORY_LOGIN)
  force_login();

if (!user_can_edit()) {
  wp_die('Please log in as an editor.');
}

$this_id = intval($_REQUEST['ID']);
$this_page = intval($_REQUEST['page']);
$this_gift_id = intval($_REQUEST['gift']);
$this_status = strval($_REQUEST['status']);

global $errors;
$errors = array();

function extra_mimes($arr) {
  $arr['wav'] = 'audio/wav';
  $arr['mp3'] = 'audio/mp3';
  $arr['3gp'] = 'audio/3gpp';
  return $arr;
}
add_filter('upload_mimes', 'extra_mimes');

function save_story($story) {
  global $errors;
  $steps = array();

  $salutation = "";
  $donors = array_map('esc_html', $story['donors']);
  if ($story['r_Dear'] && (count($donors) > 0))
    $salutation = "Dear " . comma_list($donors) . ",\n\n";

  $audio_id = intval($story['r_AudioID']);
  $thumbnail_id = intval($story['r_ThumbnailID']);
  $thumb = wp_get_attachment_image( $thumbnail_id, array(450,450), false, '');
  if (!empty($thumb)) {
    $caption = '[caption align="alignright" width="450" caption=" "]' . $thumb . "[/caption]";
    $story['r_Body'] = $caption . $salutation . $story['r_Body'];
  }
  if (empty($story['r_Title']))
    $story['r_Title'] .= ' ';
  if (empty($story['r_Body']))
    $story['r_Body'] .= ' ';
  if (empty($story['new_status']))
    $story['new_status'] = $story['status'];

  $please = "Please ";
  switch ($story['new_status']) {
    case 'publish':
      $story['post_date'] = '';
    case 'future':
      $please = "Before publishing, please ";
      if (!user_can_edit($story, 'publish_posts')) 
        $errors[] = new WP_Error('publish', 'Sorry, this story must be reviewed.');

      if ($story['r_ThumbnailID'] == 0)
        $steps[] = 'upload a picture of the recipient';
      if (count($story['r_Items']) == 0)
        $steps[] = 'pick at least one donor';
      else if (trim($story['r_Title']) == '' || trim($story['r_Body']) == '')
        $steps[] = 'make sure the story is finished';
      else if (count($story['needs']) > 0 && ($_REQUEST['skip'] != 'yes')) {
        if (current_user_can('level10'))
          $check = ' (or <input type="checkbox" name="skip" value="yes" /><i> publish anyway</i>) ';
        $steps[] = 'confirm that the received gifts match the selected donors' . $check;
      }
      else if (!check_donation_dates($story) && ($_REQUEST['skip'] != 'yes')) {
        if (current_user_can('level10'))
          $check = ' (or <input type="checkbox" name="skip" value="yes" /><i> publish anyway</i>) ';
        $steps[] = 'confirm that the last donation did not arrive within 24 hours' . $check;
      }
      
      // fallthrough
    case 'pending':
      if (count($story['r_Gifts']) == 0)
        $steps[] = 'specify which gift was received';
      // fallthrough
    default:
      if (empty($story['r_Name']))
        $steps[] = "enter a name for this recipient";
      break;
  }

  if (count($steps) > 0) {
    $errors[] = new WP_Error('required', $please . comma_list($steps) . ".");
  }

  if (count($errors) > 0)
    return NULL;

  $catID = get_cat_ID('Impact Stories');

  $new_id = wp_insert_post(array(
    'ID' => $story['ID'],
    'post_author' => $current_user->ID,
    'post_content' => $story['r_Body'],
    'post_status' => $story['new_status'],
    'post_title' => $story['r_Title'],
    'post_date' => $story['post_date'],
    'comment_status' => 'closed',
    'ping_status' => 'closed',
    'post_category' => array( $catID )
    ), true);

  if (is_wp_error($new_id)) {
    $errors[] = $new_id;
    return NULL;
  }

  update_r_fields($new_id, 'r_StoryVersion', 1);
  update_r_fields($new_id, 'r_Name', $story['r_Name']);
  update_r_fields($new_id, 'r_Notes', $story['r_Notes']);
  update_r_fields($new_id, 'r_NoDear', !$story['r_Dear']);
  update_r_fields($new_id, 'r_Gifts', implode(',', $story['r_Gifts']));
  update_r_fields($new_id, 'donation_items', implode(',', $story['r_Items']));
  update_r_fields($new_id, '_thumbnail_id', $thumbnail_id);

  save_donorDetails($new_id); //need to be called again after updating post meta

  if ($thumbnail_id > 0)
    wp_update_post(array('ID' => $thumbnail_id, 'post_parent' => $new_id));
  if ($audio_id > 0)
    wp_update_post(array('ID' => $audio_id, 'post_parent' => $new_id));

  return $new_id;
}

function sanitize($s) {
  return trim($s);
}

function update_r_fields($new_id, $name, $value) {
  if (empty($value))
    delete_post_meta($new_id, $name);
  else  
    update_post_meta($new_id, $name, $value);
}

function user_can_edit($story = NULL, $action = 'edit_posts') {
  if ($story == NULL)
    return current_user_can($action);
  if (empty($story['ID'])) {
    if ($action == 'delete_posts')
      return false;
    return current_user_can($action);
  }
  
  $post_type_object = get_post_type_object('post');
  $cap = $post_type_object->cap;
  $id = $story['ID'];
 
  // If it's published only real admins can edit
  if (is_published($story))
    return current_user_can($cap->delete_posts, $id);
  return current_user_can($cap->$action, $id);
}

function save_story_file($name, $id, $type = 'photo') {
  // TODO: Confirm post $id is a story

  if (count($_FILES) == 0)
    return;

  /*
  if (!current_user_can('upload_files') || empty($id)) {
    return new WP_Error('files', "Sorry, the upload failed.");
  }
  */

  $file = $_FILES[$name];
  if ($name == 'r_Photo' && $file == NULL) {
    $name = 'file';
    $file = $_FILES[$name];
  }

  if ($file == NULL)
    return;

  if ($file['error'] > 0)
    return;

  if ($file['size'] <= 0)
    return new WP_Error('files', "There was an error while uploading the $type file.");
  if ($file['size'] > MAX_FILE_SIZE)
    return new WP_Error('files', "Sorry, attached files must be less than " . number_format(MAX_FILE_SIZE/1000000.0,1) . "mb" );

  switch ($type) {
    case 'photo':
      $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png', 'image/pjpeg', 'application/octet-stream');
      $invalid = "Please upload a valid JPG, GIF, or PNG image.";
      break;
    case 'audio':
      $allowed_file_types = array('audio/mp4a-latm', 'audio/3gpp', 'audio/wav', 'audio/x-wav', 'audio/mpeg', 'audio/mp3');
      $invalid = "Please upload a valid MP3 or M4A audio file.";
      break;
  }

  $filetype = strtolower($file['type']);
  $file['name'] = preg_replace('/\.+/', '.', sanitize_file_name($file['name']));
  
  if (!in_array($filetype, $allowed_file_types))
    return new WP_Error('files', $invalid);

  if ($type == 'audio') {
    $new_ext = "mp3";
    $new_type = 'audio/mpeg';

    $f = $file['tmp_name'];
    $pi = pathinfo($file['name']);

    if ($pi['extension'] != $new_ext) {
      // Generate a new filename
      $fname = "$f.$new_ext";
      for ($i = 2; file_exists($fname); $i++) {
        $fname = "$f$i.$new_ext";
      }

      // Do the transcode
      exec($cmd = "ffmpeg -i $f -ar 22000 $fname");
 
      if (file_exists($fname)) {
        rename($fname, $f);
        $_FILES[$name]['type'] = $new_type;
        $_FILES[$name]['name'] = $pi['filename'] . ".$new_ext";
        $_FILES[$name]['size'] = filesize($f);
        $file = $_FILES[$name];
      }
    }
  }
   
  $override['test_form'] = false;
  $fid = media_handle_upload($name, $id);
  if (is_wp_error($fid))
    return $fid;
  if ($fid > 0)
    return intval($fid);

  return new WP_Error('files', "There was an error while uploading the $type file.");
}

function is_published($story) {
  return ($story['status'] == 'publish' || $story['status'] == 'future');
}

function modify_story(&$story, &$req) {
  handle_attachments($story['ID']);

  $story['r_AudioID'] = intval($req['r_AudioID']);
  $story['r_ThumbnailID'] = intval($req['r_ThumbnailID']);
  $story['r_Name'] = sanitize($req['r_Name']);
  $story['r_Title'] = sanitize($req['r_Title']);
  $story['r_Notes'] = sanitize($req['r_Notes']);
  $story['r_Gifts'] = as_ints($req['r_Gifts']);
  $story['r_Items'] = as_ints($req['r_Items']);

  if ($req['r_Dear'] === NULL)
    $story['r_Dear'] = false;
  else 
    $story['r_Dear'] = sanitize($req['r_Dear']);

  //$tags = array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array());
  $body = fixEncoding(stripslashes($req['r_Body']));
  $story['r_Body'] = $body; // wp_kses($body, $tags);

  return $story;
}

function handle_attachments($id) {
  global $errors;

  $result = array();

  // Save the photo attachment ('file')
  $file_id = save_story_file('r_Photo', $id, 'photo');
  if (is_wp_error($file_id)) {
    $errors[] = $file_id;
  } else if ($file_id > 0) {
    $_REQUEST['r_ThumbnailID'] = intval($file_id);
    $result['ID'] = $file_id;
    $result['html'] = wp_get_attachment_image( $file_id, array(250,250), false, '');

    if (empty($_REQUEST['r_Name'])) {
      $_REQUEST['r_Name'] = get_the_title($field_id);
    }
  } 

  $file_id = save_story_file('r_Audio', $id, 'audio');
  if (is_wp_error($file_id)) {
    $errors[] = $file_id;
  } else if ($file_id > 0) {
    $_REQUEST['r_AudioID'] = intval($file_id);
    $result['audio_id'] = $file_id;
    $result['audio_html'] = '';

    if (empty($_REQUEST['r_Name'])) {
      $_REQUEST['r_Name'] = get_the_title($field_id);
    }
  } 

  return $result;
}

function initialize_story() {
  return 
    $story = array(
    'status' => 'draft',
    'r_Gifts' => array(),
    'r_Items' => array(),
    'r_Dear' => TRUE,
    'needs' => array(),
    'donors' => array()
  );
}

?>
