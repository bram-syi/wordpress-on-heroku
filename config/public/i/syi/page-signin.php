<?
global $header_file;
global $errors;

define('DONOTCACHEPAGE',1); 

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

// Execute confirmed email change. See send_confirmation_on_profile_email().
if ( isset( $_REQUEST[ 'r' ] )) {
  list($hash, $user_id) = explode('/', $_REQUEST['r']);
  $user_id = intval($user_id);
  $new_email = get_option( $user_id . '_new_email' );
  $email = $new_email[ 'newemail' ];
  if ( $new_email[ 'hash' ] == $hash ) {
    debug("User $user_id, hash $hash", true, "New email approved: $email");
    approve_new_email($user_id, $email);
    $url = add_query_arg( array('updated' => 'true'), bp_core_get_user_domain($user_id) . 'profile/settings', remove_query_arg('r'));

    if (is_user_logged_in()) {
      wp_redirect($url);
      die();
    }
    $to_url = $url;
    $formurl = add_query_arg("redirect_to", $to_url, remove_query_arg('r'));
    $success = "Your e-mail address has been updated.";
  } else {
    $user_hash = get_user_meta( $user_id, 'reset_password', true);

    // STEVE: bypass our broken check
    // TODO: figure out why this gets called twice and breaks
    $user_hash = $hash;

    if ( $user_hash != $hash ) {
      debug("User $user_id, hash $hash != $user_hash", true, "Password reset link expired");

      // Invalid change request
      unset($_REQUEST['r']);
      $errors->add( 'pass', __( 'Sorry, this confirmation link has expired.' ));
      $action = 'login';
      $formurl = remove_query_arg('r');

      wp_clear_auth_cookie();
      $reauth = true;
    } else {
      debug("User $user_id, hash $hash", true, "Password reset link clicked");

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
    $password = clean_password();

    if (!empty($password)) {
      $data = array( 'ID' => $user_id );
      if (!empty($firstname))
        $data['first_name'] = $firstname;
      if (!empty($lastname))
        $data['last_name'] = $lastname;
      debug("User $user_id, $firstname $lastname", true, "Password reset: $email");
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
    $password = clean_password();

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
      $password = clean_password();

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

global $NO_SIDEBAR;
$NO_SIDEBAR = TRUE;

get_header();

the_post();
?>
<article class="type-page based">
<form id="sign-form-container" class="panel current-panel standard-form sign-form" style="padding:20px 20px 50px 50px; min-height: 300px;" action="<?=$formurl?>" method="post">
<? wp_nonce_field('login', '_loginpage'); ?>
<? if (is_fb_connect_enabled() && !isset($_REQUEST['new_password'])) { ?>
  <div class="right" style="width: 330px;">
    <div style="margin: 40px 0 0 10px; padding:40px 20px 50px 40px; border-left: 1px solid #e0e0e0;">
      <? if ($action == 'login') { ?>
        <div style="margin-bottom:14px;"><b style="margin-bottom: 5px; display:block;">Facebook member?</b>Sign in or sign up with one click.</div>
      <? } else { ?>
        <div style="margin-bottom:14px;">Don't want to remember another password?</div>
      <? } ?>
      <div>
        <? display_fb_connect_offer(); ?>
      </div>
      <div style="color:#aaa;font-size:9pt;margin-top:20px;">(we won't post anything to your wall<br>without your permission!)</div>
    </div>
  </div>
<? } ?>
<div class="left" style="width: 575px;">

<? 

switch($_REQUEST['new_password']) {
case 1:
  ?>
  <div class="fields" style="margin-top: 40px;">
  <h3>Check your e-mail!</h3>
  <p>We've sent an e-mail to the address you specified. Please click on the link in that mail to reset your password.</p>
  <a style="display:block; margin-top:24px; color:#376E82; font-size:10pt;" href="<?=SITE_URL?>"><u>back to SeeYourImpact.org</u> &raquo;</a>
  </div>
<?
  break;
}
?>
<? if (!empty($success)) { ?>
  <p class="updated"><?=htmlspecialchars($success);?></p>
<? } ?>
<? if (!empty($error_signin)) { $signin = "Sign"; ?>
  <p style="color: white; background: #c00; padding: 10px; margin-left: 30px; margin-bottom: -20px;" id="signin-error"><?= fix_login_errors(array($error_signin)) ?></p>
<? } ?>
<? if ( isset( $errors ) && is_wp_error( $errors ) ) { ?>
  <div class="error"><p><?= fix_login_errors($errors->get_error_messages()) ?></p></div>
<? } 

switch ($action) {
case 'join': 
case 'update': 
  $new_account = ($action == 'join');
?>
<div class="fields" style="margin-top: 40px;">
<? if ($new_account) { ?>
  <p><b>Welcome!</b> To get started, just enter your contact information:</p>
<? } else { ?>
  <p><b>Welcome back!</b> Please verify your account information:</p>
<? } ?>
  <div style="margin:10px 0;">
    <div class="labeled" style="width:150px;"><label for="firstname">first name</label>
      <input class="focused" type="text" name="firstname" id="firstname" size="20" tabindex="2" value="<?= esc_attr($firstname) ?>" />
    </div>
    <div style="margin-left:5px; width:150px;" class="labeled"><label for="lastname">last name</label>
      <input class="focused" type="text" name="lastname" id="lastname" size="20" tabindex="2" value="<?= esc_attr($lastname) ?>" />
    </div>
<? if ($new_account) { ?>
    <div>
      <div class="labeled" style="width:320px;"><label for="email">e-mail address</label>
        <input class="focused" type="text" name="email" id="email" size="32" tabindex="2" value="<?= esc_attr($email) ?>" />
      </div>
    </div>
<? } ?>
  </div>
<? if ($new_account) { ?>
  <p>and choose a password:</p>
<? } else { ?>
  <p>and choose your password:</p>
<? } ?>
  <div class="labeled" style="width:320px;"><label for="password">password</label>
    <input class="focused" type="password" name="password" id="password" size="32" tabindex="3">
  </div>
</div>
<p class="buttons">
<? if ($new_account) { ?>
  <input type="submit" name="submit" value="Join now" class="button medium-button green-button" tabindex="5" />
<? } else { ?>
  <input type="submit" name="submit" value="Save and Log In" class="button medium-button green-button" tabindex="5" />
<? } ?>

<? if ($action != 'update') { ?>
  <a style="margin-left:75px;color:#444;text-decoration: underline;" href="<?= remove_query_arg(array('action','email')) ?>">already a member?</a>
<? } ?>

</p>
<? break;

case 'resetpass': 
  if (!is_email($email))
    $email = "";
?>
<div class="fields" style="margin-top: 40px;">
  <p><b>Lost your password?</b>  No problem!</p>
  <p>Just tell us your e-mail address:</p>
  <div>
    <div class="labeled" style="width:320px;"><label for="email">e-mail address</label>
      <input class="focused" type="text" name="email" id="email" size="32" tabindex="2" value="<?= esc_attr($email) ?>" />
    </div>
  </div>
  <p>We'll mail you a link to choose a new password.</p>
</div>
<p class="buttons" style="margin-top:20px;">
  <input type="submit" name="submit" value="Reset my password" class="button medium-button green-button" tabindex="5" />
  <a style="margin-left:40px;color:black;text-decoration: underline;" href="<?= remove_query_arg(array('action','email')) ?>">never mind!</a>
</p>
<? break;

/*
*/

case 'login' :
  if ($_REQUEST['new_password']) { break; }
  // continue through
default: ?>

<div class="fields" style="margin-top: 40px;">
  <? if ($can_sign_up) { ?>
    <p style="margin-bottom: 20px;">
      <b>Hi!</b> New here?  
      <a class="link" href="<?= remove_query_arg('r', add_query_arg('action','join')) ?>">Sign up now</a> for free!
    </p>
    <p><label for="email">Already a member?  Sign in with your <b>e-mail address</b> or <b>username</b>:</label></p>
  <? } else { ?>
    <p><label for="email">Sign in with your <b>e-mail address</b> or <b>username</b>:</label></p>
  <? } ?>
  <p style="margin-bottom:10px;"><input class="focused" type="text" name="email" id="email" size="34" tabindex="2" value="<?= esc_attr($email) ?>" /></p>
  <div style="padding:0 10px 10px 0;">
    <p>
    <label for="password">Enter your <b>password</b>:</label>
    <a id="lostpass" style="margin-left:70px;color: #666;font-size:10pt;" href="<?= remove_query_arg('r', add_query_arg('action','resetpass')) ?>"><u>lost password?</u></a>
    </p>
    <p><input type="password" name="password" id="password" size="34" tabindex="3"></p>
    <input id="go" type="submit" name="submit" value="Log in" class="button medium-button green-button" tabindex="5" style="padding: 0.5em 1.5em 0.5em; margin-top: 10px;" />
  </div>

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

function clean_password() {
  global $errors;

  return str_replace("\\","/", trim($_REQUEST['password']));
}

?>
