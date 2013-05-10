<?

function getUniqueUsername($username, $first='', $last='') {
  global $wpdb;
  $id = 1;
  $counter = 1;

  $i = 0;
  $exists = username_exists($username);
  while ($exists > 0 && $i < strlen($last)) {
    $username .= substr($last, $i++, 1);
    $exists = username_exists($username);
  }

  $new_username = $username;
  $exists = username_exists($new_username);
  while ($exists > 0) {
    $counter++;
    $new_username = $username.$counter;
    $exists = username_exists($new_username);
  }

  $counter_exists = $wpdb->get_var($wpdb->prepare(
    "SELECT counter FROM donorUsername WHERE first_name = %s", $username));

  if (empty($counter_exists)) {
    $wpdb->query( $wpdb->prepare(
      "INSERT INTO donorUsername (counter, first_name) VALUES (%d, %s) ",
        $counter, $username));
  } else {
    $wpdb->query( $wpdb->prepare(
      "UPDATE donorUsername SET counter = %d WHERE first_name = %s",
        $counter, $username));
  }

  return $new_username;
}

function createWpAccount($email, $first_name, $last_name,
  $username='', $password='',$ignore_username=false,$create_donor=true) {
  global $error_wp_signin, $wpdb;

  //debug($first_name.'-'.$email);

  $id = email_exists($email);
  if ($id > 0) {
    $user = get_userdata($id);
    $error_wp_signin = '<strong>ERROR</strong>: Sorry, that e-mail is already registered. '.
      '<a href="' . site_url('/signin/?action=lostpassword') . '">Lost your password</a>?';
    return false;
  } else {

    $first_name = fix_name($first_name);
    $last_name = fix_name($last_name);

    if (empty($username)) {
      if(!empty($first_name)) {
        $username = $first_name;
      } else if(!empty($email)) {
        $email_part = explode("@",$email);
        $email_part = explode("+",$email_part[0]);
        $username = $email_part[0];
      } else {
        $error_wp_signin = 'Please enter your full contact information.';
        return false;
      }
    } else if (!$ignore_username) {
      $id = username_exists($username);
      if ($id > 0) {
        $error_wp_signin = '<strong>ERROR</strong>: Sorry, that username is already registered. '.
          '<a href="' . get_site_url(1, '/signin/?action=lostpassword') . '">Lost your password</a>?';
        return false;
      }
    }

    $username = getUniqueUsername(strtolower(sanitize_user($username)),$first_name,$last_name);

    if (empty($password))
      $password = wp_generate_password(12, false);
    $id = wp_create_user($username, $password, $email);
    if (is_wp_error($id)) {
      $error_wp_signin = $id->get_error_message();
      return false;
    }

    $userdata = array(
      'ID' => $id,
      'user_nicename' => $first_name,
      'first_name' => $first_name,
      'last_name' => $last_name,
      'display_name' => $first_name
    );

    wp_update_user($userdata);
    do_action('user_donor_register', $id);
  }

  return array($username, $id);
}









add_action( 'admin_menu', 'remove_your_profile');
function remove_your_profile() {
  global $bp, $menu;

  remove_menu_page('profile.php');
  remove_submenu_page('users.php', 'profile.php');

  // Don't let people go to the "Your Profile" tab -- send them to settings
  if ($_SERVER['REQUEST_URI'] == "/wp-admin/profile.php") {
    wp_redirect(get_member_link($bp->loggedin_user->id, 'settings'));
    die();
  }
}

function get_avatar_url($user_id, $size) {
  $get_avatar = get_avatar($user_id, $size);
  $matches = array();
  if (preg_match("/src='(.*?)'/i", $get_avatar, $matches))
    return $matches[1];
  return NULL;
}


function draw_avatar_box($user_id, $interactive = TRUE, $label = FALSE, $avatars = TRUE, $hide_default_avatar = FALSE) {
  if ($user_id == 0) {
    $dname = "";
    $interactive = FALSE;
    if ($hide_default_avatar)
      $avatars = FALSE;
  } else {
    $dname = get_userdata($user_id)->display_name;
    $profile_url = bp_core_get_user_domain($user_id);

    $href = ' href="' . $profile_url . '"';
    if (stristr($dname, "anonymous") !== FALSE || $dname == "SeeYourImpact.org") {
      $interactive = FALSE;
      $href = '';
    }
  }

  $cl = "";
  if ($avatars) {
    $key = image_key($user_id, 'user');
    $avatar = image_src($key, image_geometry(50,50));

    // Special case for missing photos
    if (is_no_photo_image($key)) {
      $cl = "default-avatar";
      if ($hide_default_avatar)
        $avatars = FALSE;
    }
  }

  if ($label === TRUE)
    $label = xml_entities($dname);
  else if ($label !== FALSE)
    $label = xml_entities($label);
  else
    $label = "";

  if ($interactive == TRUE) { 
?>
  <? if ($avatars) { ?>
    <a class="avatar-link <?=$cl?>"<?= $href ?>>
      <div class="user-tag"><div>
        <?= $label ?>
        <u>view profile</u>
      </div></div>
      <img src="<?= $avatar ?>" class="avatar">
    </a>
  <? } ?>
  <a class="profile-name"<?=$href?>><?= $label ?></a>
<? 
  } else {
    ?>
      <? if ($avatars) { ?>
        <a class="avatar-link <?=$cl?>"><img src="<?= $avatar ?>" class="avatar"></a>
      <? } ?>
      <b class="profile-name"><?= $label ?></b>
    <?
  }

  return $dname;
}

