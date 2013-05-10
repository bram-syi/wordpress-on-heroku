<?
//
//  UPLOAD PHOTOS and automatically create user profiles!
// either add photos via the input box / form submit,
// or put a bunch of photos into /wp-content/uploads/profiles
//
// JPG only, filenames must be "email@address - full name.jpg"
//
include_once('../wp-load.php');

do_action('wp');

ensure_logged_in_admin();

$dir = WP_CONTENT_DIR . "/uploads";
$theme = $_REQUEST['theme'];

$custom = ABSPATH . "themes/$theme.php";
if (file_exists($custom))
  include_once($custom);


foreach ($_FILES as $f) {
  process($f, $theme);
  next_profile();
}

$files = glob("$dir/profiles/*.jpg");
foreach ($files as $file) {
  process($file, $theme);
  next_profile();
}

$files = glob("$dir/profiles/*.JPG");
foreach ($files as $file) {
  process($file, $theme);
  next_profile();
}

function next_profile() {
  $url = remove_query_arg('xxqfo');
  ?><div><a href="<?=$url?>">NEXT</a></div><?
  die;
}

// OVERRIDE the bp default upload path
global $bp;
$bp->avatar->upload_path = $dir;

function process($file, $theme = NULL) {
  if (is_string($file)) {
    $file =array (
      'name' => basename($file),
      'type' => 'image/jpeg',
      'tmp_name' => $file,
      'error' => 0,
      'size' => filesize($file)
    );
  }

  $filename = $file['name'];
  ?><div><?=esc_html($filename)?>:</div><?
  $f = array();
  if (!preg_match("/^(.*)?\-(.*)\.(JPG|PNG)/i", $filename, $f)) {
    ?><div> does not match.</div><?
    return;
  }
  $email = trim($f[1]);
  if (!is_email($email)) {
    ?><div> is not a valid email.</div><?
    return;
  }

  $name = trim($f[2]);
  $n = array();
  if (!preg_match("/^(.*) (\w*)$/", $name, $n)) {
    ?><div> needs a full name.</div><?
    return;
  }

  $user_id = email_exists($email);
  if ($user_id == NULL) {
    list($user, $user_id) = createWpAccount($email, $n[1], $n[2], '', 'crim1', TRUE);
    global $error_wp_signin;
    echo $error_wp_signin;
    if (empty($user_id))
      return;

    $url = get_member_link($user_id);
    ?>Created <a href="<?=$url?>" target="_new"><?= $name ?></a> #<?= $user_id ?> <?
  } else {
    $url = get_member_link($user_id);
    ?>Found <a href="<?=$url?>" target="_new"><?= $name ?></a> #<?= $user_id ?> <?
    $existing = get_campaign_for_user($user_id); 
  }

  $object = 'user';
  $avatar_dir = 'avatars';
  $original_file = $file['tmp_name'];
  $avatar_folder_dir = apply_filters( 'bp_core_avatar_folder_dir', bp_core_avatar_upload_path() . '/' . $avatar_dir . '/' . $user_id, $user_id, $object, $avatar_dir );

  echo " dir=" . $avatar_folder_dir;

  if ( !file_exists( $avatar_folder_dir ) )
    mkdir($avatar_folder_dir, 0777);
  if ( !file_exists( $avatar_folder_dir ) ) {
    ?> creation failed <?
  }


  require_once( ABSPATH . '/wp-admin/includes/image.php' );
  require_once( ABSPATH . '/wp-admin/includes/file.php' );

  // Delete the existing avatar files for the object
  bp_core_delete_existing_avatar( array( 'object' => $object, 'avatar_path' => $avatar_folder_dir ) );

  // Make sure we at least have a width and height for cropping
  $crop_x = 0;
  $crop_y = 0;
  $crop_w = bp_core_avatar_full_width();
  $crop_h = bp_core_avatar_full_height();

  // Set the full and thumb filenames
  $full_filename  = wp_hash( $original_file . time() ) . '-bpfull.jpg';
  $thumb_filename = wp_hash( $original_file . time() ) . '-bpthumb.jpg';

  // Crop the image
  $full = image_resize( $original_file, 
    bp_core_avatar_full_width(), bp_core_avatar_full_height(), TRUE);
  if (!empty($full)) {
    rename($full, $avatar_folder_dir . '/' . $full_filename );
  } else {
    ?> full image failed <?
  }

  if ( !file_exists( $avatar_folder_dir ) )
    mkdir($avatar_folder_dir, 0777);

  $thumb = image_resize( $original_file, 
    bp_core_avatar_full_width(), bp_core_avatar_full_height(), TRUE);
  if (!empty($thumb)) {
    rename($thumb, $avatar_folder_dir . '/' . $thumb_filename );
  } else {
    ?> thumb failed <?
  }


  // Remove the original
  @unlink( $original_file );

  global $bp;
  $bp->displayed_user = new stdClass;
  $bp->displayed_user->id = $user_id;

  $p = create_campaign_post($user_id, $theme);
  $p['goal'] = 50;
  $p['ID'] = $existing;
  $result = start_campaign($p);
  if (is_error_result($result))
    pre_dump($result); 
  return true;
}


?>

<form action="" method="POST" enctype="multipart/form-data" >
<input type="file" name="file1">
<input type="submit" name="go" value="Go!">
</form>
