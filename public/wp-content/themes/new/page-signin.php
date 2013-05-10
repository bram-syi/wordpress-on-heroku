<?
global $header_file;
global $errors;

define('FB_PLACEMENT', 'none');
$header_file = 'signin';

wp_dequeue_script('csimport');

// Redirect to https login if forced to use SSL
if ( force_ssl_admin() && !is_ssl() ) {
  if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
    wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']));
    exit();
  } else {
    wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
  }
}

global $wpdb;
global $error_signin;
global $payment_method_pages;

nocache_headers();

$errors = new WP_Error();
$pass = false;
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

// Don't let someone fake the 'update' command, which requires a special token.
if ($action == 'update')
  $action = 'login';

$b_id = $_GET['ch'];
if (empty($b_id))
  $b_id = 1;

// Get the e-mail address from anywhere in the request
$email = sanitize_text_field($_REQUEST['email']);
$firstname = sanitize_text_field($_REQUEST['firstname']);
$lastname = sanitize_text_field($_REQUEST['lastname']);
unset($_GET['action']);

function show_signin_sidebar() {
  echo '<div class="sidebar-panel charity-panel">';
  draw_promo_content("signin-sidebar", "h2", true);
  echo '</div>';
}
add_action('get_sidebar', 'show_signin_sidebar');

$can_sign_up = true;

if ($_GET['redirect_to'])
  $to_url = $_GET['redirect_to'];
else {
  $to_url = sanitize_text_field($_GET['to']);
  $empty_to_url = empty($to_url);
  unset($_GET['to']);

  // Don't force SSL
  $https = $_SERVER['HTTPS'];
  unset($_SERVER['HTTPS']);
  $httpp = $_SERVER['SERVER_PORT'];
  unset($_SERVER['SERVER_PORT']);
  $to_url = add_query_arg( $_GET, get_site_url($b_id, "/$to_url") );
  $_SERVER['HTTPS'] = $https;
  $_SERVER['SERVER_PORT'] = $http;

  $to_url = remove_query_arg( array('ch','_wpnonce','new_password','r'), $to_url );
}

if ($_REQUEST['xx']) {
  die($to_url);
}

if ($_GET['claim']) {
  header('HTTP/1.0 410 Gone');
  exit("<h1>410 Gone</h1>\nThe page you requested has been removed.");

  /* STEVEE: TURNED OFF CLAIM because it was getting spammed 
  $submit = "claim";
  $user_id = intval($_GET['claim']);
  $action = "resetpass";

  $user = get_userdata($user_id);
  $email = $user->user_email;
  confirm_new_password($user_id, $email, $new_password);

  wp_redirect( add_query_arg('new_password','2', wp_login_url()) );
  exit();
  */
}

// Execute confirmed email change. See send_confirmation_on_profile_email().
if ( isset( $_REQUEST[ 'r' ] )) {
  list($hash, $user_id) = explode('/', $_REQUEST['r']);
  $user_id = intval($user_id);
  $new_email = get_option( $user_id . '_new_email' );
  $email = $new_email[ 'newemail' ];
  if ( $new_email[ 'hash' ] == $hash ) {
    approve_new_email($user_id, $email);
    $url = add_query_arg( array('updated' => 'true'), bp_core_get_user_domain($user_id) . 'profile/settings');

    if (is_user_logged_in()) {
      wp_redirect($url);
      die();
    }
    $to_url = $url;
    $formurl = add_query_arg("redirect_to", $to_url, remove_query_arg('r'));
    $success = "Your e-mail address has been updated.";
  } else {
    $user_hash = get_user_meta( $user_id, 'reset_password', true);
    if ( $user_hash != $hash ) {
      debug("User $user_id, hash $hash != $user_hash", true, "Password reset expired: $email");

      // Invalid change request
      unset($_REQUEST['r']);
      $errors->add( 'pass', __( 'Sorry, this confirmation link has expired.' ));
      $action = 'login';
      $formurl = remove_query_arg('r');

      wp_clear_auth_cookie();
      $reauth = true;
    } else {
      debug("User $user_id, hash $hash", true, "Password was reset: $email");

      $action = 'update';
      $user = get_userdata($user_id);
      if (empty($email))
        $email = $user->user_email;
      if (empty($firstname))
        $firstname = $user->user_firstname;
      if (empty($lastname))
        $lastname = $user->user_lastname;
    }
  }

}

remove_action('widgets_init', 'syi_widgets_init');
remove_action('wp_footer', 'syi_widgets_fb_init');
remove_action('syi_pagetop', 'draw_the_crumbs', 0);
remove_action('syi_sidebar', 'social_widgets', 5);

// validate action so as to default to the login screen
if ( !in_array($action, array('logout', 'lostpassword', 'resetpass', 'update', 'join'), true) )
  $action = 'login';

$submit = str_replace(" ","", strtolower($_POST['submit']));
if ($_POST && !wp_verify_nonce($_REQUEST['_loginpage'], 'login')) {
  wp_redirect( wp_login_url() );
  exit();
}

switch ($action) {
case 'logout':
  if (wp_verify_nonce($_GET['_wpnonce'], 'log-out')) {
    wp_logout();
    restore_cart();
  }

  wp_redirect( $to_url );
  exit();

case 'lostpassword' :
  $action = 'resetpass';
  // fall through
case 'resetpass' :
  if (!empty($submit)) {
    if (reset_user_password($email, '' )) {
      wp_redirect( add_query_arg('new_password','1', wp_login_url()) );
      exit();
    }
  }

  $formurl = add_query_arg('action', $action, remove_query_arg(array('email')));
  break;

case 'update' :
  if (!empty($submit)) {
    $password = check_password();

    if (!empty($password)) {
      $data = array( 'ID' => $user_id );
      if (!empty($firstname))
        $data['first_name'] = $firstname;
      if (!empty($lastname))
        $data['last_name'] = $lastname;
      approve_new_password($user_id, $password);
      wp_update_user($data);
      auto_wp_login($user_id);
    }
  }

  if ($to_url == site_url("/") && is_user_logged_in()) {
    // Send them to their profile
    $to_url = bp_core_get_user_domain($user_id);
  }

  break;

case 'join' :
  if (!empty($submit)) {
    $password = check_password();

    if (!empty($password)) {
      $data = array(
        "account" => $email, 
        "password" => $password,
        "first_name" => $firstname,
        "last_name" => $lastname,
        "register" => true
      );
      $user_id = try_login($data);

      if ($user_id > 0) {
        if ($empty_to_url)
          $to_url = bp_core_get_user_domain($user_id);
      }
    }
  }
  break;

case 'login' :
default:
  switch ($submit) {
  case 'join':
  case 'joinnow':
  case 'signup':
  case 'signupnow':
  case 'signupnow!':
    $args = array(
      'action' => 'join',
    );
    if (is_email($email))
      $args['email'] = urlencode($email);
    wp_redirect( add_query_arg($args) );
    die();
    
  default:
    if (!empty($submit)) {
      $password = check_password();

      $user_id = try_login(array(
        "account" => $email, 
        "password" => $password,
        "register" => false
      ));
    }
    break;
  }

  break;
}

// Redirect now if possible
if ($_GET['preview'] != 'true') {
  if (!$reauth && is_user_logged_in() || $_REQUEST['pass'] == '1') {
    wp_redirect( $to_url );
    die();
  }
}

get_header();

the_post();
?>
<article class="type-page padded based">
<section id="frame" class="home-frame">
<form id="sign-form-container" class="panel current-panel standard-form sign-form" style="padding:20px 20px 20px 50px; min-height: 300px;" action="<?=$formurl?>" method="post">
<? wp_nonce_field('login', '_loginpage'); ?>

<div>
<? 
switch($_REQUEST['new_password']) {
case 1:
  ?>
  <h3>Check your e-mail!</h3>
  <p>We've sent an e-mail to the address you specified. Please click on the link in that mail to reset your password.</p>
  <a style="display:block; margin-top:44px; padding:10px; color:#376E82; font-size:10pt;" href="<?=get_site_url(1);?>"><u>back to SeeYourImpact.org</u> &raquo;</a>
<?
  break;

case 2:
  ?>
  <h3>Thanks for claiming your Impact Page!</h3>
  <p>We've sent an e-mail to the address you used when donating.  Click the link inside that e-mail to reset your password and sign in.</p>
  <a style="display:block; margin-top:44px; padding:10px; color:#376E82; font-size:10pt;" href="<?=get_site_url(1);?>"><u>back to SeeYourImpact.org</u> &raquo;</a>
  <?
  break;
}
?>
<? if (!empty($success)) { ?>
  <p class="updated"><?=htmlspecialchars($success);?></p>
<? } ?>
<!--
<h2 style="margin-bottom:.5em">Please sign in to continue...</h2>
-->
<? if (!empty($error_signin)) { $signin = "Sign"; ?>
  <p style="color:#c00;" id="signin-error"><?= fix_login_errors(array($error_signin)) ?></p>
<? } ?>
<? if ( isset( $errors ) && is_wp_error( $errors ) ) { ?>
  <div class="error"><p><?= fix_login_errors($errors->get_error_messages()) ?></p></div>
<? } 

switch ($action) {
case 'join': 
case 'update': 
  $new_account = ($action == 'join');
?>
<div class="fields">
<? if ($new_account) { ?>
  <h2>Welcome to SeeYourImpact.org!</h2>
  <p>To get started, just enter your contact information:</p>
<? } else { ?>
  <h2>Welcome back!</h2>
  <p>Please verify your account information:</p>
<? } ?>
  <div style="margin:10px 0;">
    <div class="indent">
      <div class="labeled"><label for="firstname">first name</label>
        <input class="focused" type="text" name="firstname" id="firstname" size="16" tabindex="2" value="<?= esc_attr($firstname) ?>" />
      </div>
      <div style="margin-left:5px;" class="labeled"><label for="lastname">last name</label>
        <input class="focused" type="text" name="lastname" id="lastname" size="16" tabindex="2" value="<?= esc_attr($lastname) ?>" />
      </div>
    </div>
<? if ($new_account) { ?>
    <div class="indent labeled"><label for="email">e-mail address</label>
      <input class="focused" type="text" name="email" id="email" size="32" tabindex="2" value="<?= esc_attr($email) ?>" />
    </div>
<? } ?>
  </div>
<? if ($new_account) { ?>
  <p>and choose a password:</p>
<? } else { ?>
  <p>and choose your password:</p>
<? } ?>
  <p><input class="indent focused" type="password" name="password" id="password" size="32" tabindex="3"></p>
</div>
<p>
<? if ($new_account) { ?>
  <input type="submit" name="submit" value="Join now" class="button medium-button green-button" tabindex="5" />
<? } else { ?>
  <input type="submit" name="submit" value="Save and Log In" class="button medium-button green-button" tabindex="5" />
<? } ?>

<? if ($action != 'update') { ?>
  <a style="margin-left:100px;color:#444;text-decoration: underline;" href="<?= remove_query_arg(array('action','email')) ?>">already a member?</a>
<? } ?>

</p>
<? break;

case 'resetpass': ?>
  <p>It's easy to set a new password for your account.</p>
<div class="fields">
  <p>Just tell us your <b>e-mail address</b>:</p>
  <p><input class="indent focused" type="text" name="email" id="email" size="32" tabindex="2" value="<?= esc_attr($email) ?>" /></p>
</div>
  <p>We'll mail you a link to choose a new password.</p>
<p style="margin-top:20px;">
  <input type="submit" name="submit" value="Reset password" class="button medium-button green-button" tabindex="5" />
  <a style="margin-left:40px;color:black;text-decoration: underline;" href="<?= remove_query_arg(array('action','email')) ?>">never mind!</a>
</p>
<? break;

/*
*/

case 'login' :
  if ($_REQUEST['new_password']) { break; }
  // continue through
default: ?>

<div class="fields">
<? if (is_fb_connect_enabled() && !isset($_REQUEST['new_password'])) { ?>
  <div id="fb-connect-info" class="left">
    <div id="fb-connect-btn"><?=display_fb_login()?></div>
  </div>
  <p style="clear:left;padding:15px 20px;font-size:9pt; color: #666;">- or -</p>
<? } ?>
  <p><label for="email">Sign in with an <b>e-mail address</b> or <b>username</b>:</label></p>
  <p style="margin-bottom:10px;"><input class="focused" type="text" name="email" id="email" size="34" tabindex="2" value="<?= esc_attr($email) ?>" /></p>
<? if ($can_sign_up && false) { ?>
  <div class="left" style="border-right: 1px solid #f4f4f4;padding:0 15px 10px 0px; width: 113px;">
    <p><span class="bullet">&raquo;</span> <b>New</b> here?</p>
    <input id="go" type="submit" name="submit" value="Join now" class="button medium-button green-button" tabindex="5" style="padding: 0.5em 1.5em 0.5em;"  />
    <p style="padding:10px 10px; width:100px;color:#888;font-size:10pt;">It's easy to sign up... and free!</p>
  </div>
<? } ?>
  <div class="left" style="padding:0 10px 10px 0; width: 185px;">
    <p><label for="password">Got a <b>password</b>?</label></p>
    <p><input type="password" name="password" id="password" size="15" tabindex="3"></p>
    <input id="go" type="submit" name="submit" value="Log in" class="button medium-button green-button" tabindex="5" />
    <a id="lostpass" style="margin-left:30px;color: #666;font-size:10pt;" href="<?= remove_query_arg('r', add_query_arg('action','resetpass')) ?>"><u>forgot it?</u></a>
  </div>
<? if ($can_sign_up) { ?>
  <div class="left" style="border-left: 1px solid #f4f4f4;padding:0 0px 10px 20px; width: 113px;">
    <p><span class="bullet">&raquo;</span> <b>New</b> here?</p>
    <input id="go" type="submit" name="submit" value="Join now" class="button medium-button green-button" tabindex="5" style="padding: 0.5em 1.5em 0.5em;"  />
    <p style="padding:10px 10px; width:100px;color:#888;font-size:10pt;">It's easy to sign up... and free!</p>
  </div>
<? } ?>

<? if (!empty($_GET['pm'])) { 
//payment bypas
?>
</div>
<div style="clear:both; padding-top: 30px;">
  <a id="no-thanks" href="<?= add_query_arg('pass','no-thanks', $to_url) ?>"><u>No thanks!</u> I just want to donate &raquo;</a>
  <div style="display:none;"><div id="signin-notnow"><?=draw_promo_content("signin-notnow","strong")?></div></div>
<? } ?>
</div>

<? break;
}
?>

</div><!--left-->
</form>
<div class="frame-shadow"></div>
</section>
</article>

<script type="text/javascript">
$("#have-password").click(function() {
  $("#password").focus();
});
$("#password").bind("keypress click", function() {
  $("#have-password").attr('checked', true);
});
$("#no-thanks").mouseover(function() {
  $("#signin-notnow").css('visibility', 'visible');
}).mouseout(function() {
  $("#signin-notnow").css('visibility', 'hidden');
});
$("#lostpass, #signin-error a").click(function() {
  window.location = $(this).attr('href') + '&email=' + encodeURIComponent($("#email").val());
  return false;
});
</script>


<? get_footer(); 

function reset_user_password($email) {
  global $errors, $wpdb;

  if (empty($email)) {
    return false;
  }

  $user = get_userdatabylogin($email);
  if ($user == null) 
    $user = get_user_by_email($email);
  $user_id = $user->ID;

  if ($user_id == 0) {
    if ( !is_email( $email ) ) {
      $errors->add( 'email', __( "Please enter a valid e-mail address." ), array( 'form-field' => 'email' ) );
      return false;
    }

    $errors->add( 'email', __( "Sorry, there's no member registered with that e-mail." ), array( 'form-field' => 'email' ) );
    return false;
  }

  $email = $user->user_email;
  confirm_new_password($user_id, $email, $new_password);
  return true;
}

function fix_login_errors($s) {
  $s = str_replace('/wp-login.php','/signin/', $s);
  $s = str_replace('<strong>ERROR</strong>: ','', $s);
  $s = str_replace('Invalid username','Sorry, can\'t find that account..', $s);

  return implode( "</p>\n<p>", $s );
}

function check_password() {
  global $errors;

  $password = trim($_REQUEST['password']);

  /* Check for "\" in password */
  if ( false !== strpos( stripslashes($password), "\\" ) ) {
    $errors->add( 'pass', __( 'Passwords may not contain the character "\\".' ), array( 'form-field' => 'password' ) );
    return null;
  }

  return $password;
}

?>
