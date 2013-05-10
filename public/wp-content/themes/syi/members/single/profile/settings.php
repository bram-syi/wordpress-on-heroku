<?php
/**
 * Edit user administration panel.
 *
 */
global $bp;
global $wpdb;
global $errors;
global $site_url;

require_once( ABSPATH . 'wp-admin/includes/misc.php' );
require_once( ABSPATH . 'wp-admin/includes/template.php' );
require_once( ABSPATH . 'wp-admin/includes/user.php' );
require_once( ABSPATH . WPINC . '/registration.php' );

wp_reset_vars(array('action', 'redirect', 'profile', 'user_id', 'wp_http_referer'));
remove_action( 'personal_options_update', 'send_confirmation_on_profile_email' );

$user_id = $bp->displayed_user->id;
$user = get_userdata( $user_id );
unset($user->data->user_pass);

if (!$user)
	die( __('Invalid user ID.') );
$errors = new WP_Error();

// Only allow super admins on multisite to edit every user.
if (!current_user_can( 'manage_network_users' ) && !bp_is_my_profile() && ! apply_filters( 'enable_edit_any_user_configuration', true ) )
	die( __( 'You do not have permission to edit this user.' ) );

if (!empty( $_GET['dismiss'] ) && $user_id . '_new_email' == $_GET['dismiss'] ) {
	delete_option( $user_id . '_new_email' );
	wp_redirect( remove_query_arg('dismiss') );
	die();
}

$action = $_REQUEST['action'];
if ($action == 'update') {
  if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'update-user_' . $user_id)
      || !current_user_can('edit_user', $user_id) )
    die(__('You do not have permission to edit this user.'));

  $old_login = $user->user_login;
  if (isset($_POST['user_login'])) {
    $user->user_login = $user->user_nicename = $new_login = clean_username($_POST['user_login'], true);
    if ($new_login == $old_login) {
      // No change
      $new_login = null;
    } else if (strpos($new_login, "@") !== FALSE || strpos($new_login, ".") !== FALSE || !validate_username($new_login, true)) {
      $errors->add( 'user_login', __( "Sorry, your impact page name can only use letters and numbers." ), array( 'form-field' => 'user_login' ) );
    } else if (username_exists($new_login)) {
      $errors->add( 'user_login', __( "Sorry, that profile name is taken." ), array( 'form-field' => 'user_login' ) );
    }
  }

  if (isset($_POST['pass1'])) {
    $pass1 = $_POST['pass1'];
    $pass2 = isset($_POST['pass2']) ? $_POST['pass2'] : $pass1;
    
    /* checking the password has been typed twice */
    do_action_ref_array( 'check_passwords', array ( $user->user_login, & $pass1, & $pass2 ));

    /* Check for "\" in password */
    if ( false !== strpos( stripslashes($pass1), "\\" ) )
      $errors->add( 'pass', __( 'Passwords may not contain the character "\\".' ), array( 'form-field' => 'pass1' ) );

    /* checking the password has been typed twice the same */
    if ( $pass1 != $pass2 )
      $errors->add( 'pass', __( 'Please enter the same password in the two password fields.' ), array( 'form-field' => 'pass1' ) );
 
    if ( !empty( $pass1 ) ) {
      $user->user_pass = $pass1;
    }
  }

  $user->first_name = $first_name = sanitize_text_field( $_POST['first_name'] );
  $user->last_name = $last_name = sanitize_text_field( $_POST['last_name'] );
  $err =  __( "We need both your first and last name for our records.<div style=\"font-size:9pt;\">(You can use the 'Show only my first name' option if you'd prefer to remain anonymous on the site)</div>" );

  if (empty($first_name) && !empty($last_name))
    $errors->add( 'names', $err, array( 'form-field' => 'first_name' ) );
  else if (!current_user_can('level_1') && empty($last_name))
    $errors->add( 'names', $err, array( 'form-field' => 'last_name' ) );

  $user->show_full_name = ($_POST['only_first'] == 0);
  $name = $user->show_full_name ? "$first_name $last_name" : "$first_name";
  $user->nickname = $user->display_name = $name;
  $user->no_thanks_email = $_POST['donate-email'] == 0;
  $user->fb_publish_thanks = $_POST['donate-facebook'] == 1;
  $user->no_story_email = $_POST['story-email'] == 0;
  $user->fb_publish_story = $_POST['story-facebook'] == 1;
  $user->xp_location = sanitize_text_field($_POST['location']);
  $user->xp_about = trim($_POST['about_me']);
  unset($user->data->user_password);

  $new_email = sanitize_text_field($_POST['email']);
  if (!empty($new_email) && $new_email != $user->user_email) {
    $em = change_user_email($user_id, $new_email);
    if (!empty($em))
      $user->user_email = $em;
  }

  if ( !isset( $errors ) || !is_wp_error($errors) || !$errors->get_error_code()) {
    $user_id = wp_update_user( get_object_vars( $user->data ) );

	// if everything OK, sync main donor info as well
	$updated_donor = user_main_donor_sync($user_id);
	//debug("UPDATING user#".$user_id." main donor#".$updated_donor,true);
	//

    update_user_meta($user_id, 'no_thanks_email', $user->no_thanks_email);
    update_user_meta($user_id, 'fb_publish_thanks', $user->fb_publish_thanks);
    update_user_meta($user_id, 'no_story_email', $user->no_story_email);
    update_user_meta($user_id, 'fb_publish_story', $user->fb_publish_story);
    update_user_meta($user_id, 'full_name', $user->show_full_name);
    xprofile_set_field_data("Location", $user_id, $user->xp_location);
    xprofile_set_field_data("About Me", $user_id, $user->xp_about);
    wp_cache_delete($user_id, 'users');

    if (!empty($new_login)) {
      $wpdb->update( $wpdb->users, array('user_login' => $new_login, 'user_nicename' => $new_login), array('ID' => $user_id) );
      wp_cache_delete($user_login, 'userlogins');
      wp_cache_delete("bp_user_domain_$user_id", 'bp');
      wp_cache_delete("bp_user_username_$user_id", 'bp');
      wp_cache_delete("bp_core_userdata_$user_id", 'bp');
      do_action('username_changed', $user, $old_login);
      $bp->displayed_user->userdata = bp_core_get_core_userdata( $user_id );

      $new_url = $site_url."/members/$new_login";
      debug("Changed to: ".$new_url, true, "Username change: $old_login to $new_login -- ");

      // Update the cookies if the password changed.
      if ($bp->loggedin_user->id == $user_id) {
        $bp->loggedin_user->userdata = bp_core_get_core_userdata( $user_id );
        wp_clear_auth_cookie();
        wp_set_auth_cookie($user_id);
      }
    }
    else 
      $user->user_login = $old_login;
    
	if (empty($new_url)) {
	  $url = get_member_link($user_id, "settings");
	  //$url = wp_login_url($url);
	} else {
	  $url = $new_url;	
	}
	$url = add_query_arg( array('updated' => 'true'), $url );
	
	wp_redirect( $url );
    die();
  }
} else {
  // Fill in the other fields of user
  $user->show_full_name = get_user_meta($user_id, 'full_name', true);
  $user->no_thanks_email = get_user_meta($user_id, 'no_thanks_email', true);
  $user->fb_publish_thanks = fb_can_publish($user_id, 'thanks', true);
  $user->no_story_email = get_user_meta($user_id, 'no_story_email', true);
  $user->fb_publish_story = fb_can_publish($user_id, 'story', true);
  $user->xp_location = bp_get_profile_field_data('field=Location');
  $user->xp_about = bp_get_profile_field_data('field=About Me');
}

$profileuser = get_user_to_edit($user_id);

if ( !current_user_can('edit_user', $user_id) )
	die(__('You do not have permission to edit this user.'));

$user_id = $bp->displayed_user->id;
$new_email = get_option( $user_id . '_new_email' );
$show_password_fields = apply_filters('show_password_fields', true, $profileuser);

$action = remove_query_arg('updated');

?>

<div class="wrap" id="profile-page">

<form id="your-settings-form" action="<?=$action?>" method="post"<?php do_action('user_edit_form_tag');?> class="standard-form">
<?php wp_nonce_field("update-user_$user_id");?>
<input type="hidden" name="from" value="profile" />
<input type="hidden" name="checkuser_id" value="<?=$user_id?>" />

<? if (isset($_REQUEST['new'])) { ?>
  <h1>Welcome to SeeYourImpact.org!</h1>
<? } ?>

<? if ( isset( $errors ) && is_wp_error( $errors ) ) { ?>
  <div class="error"><p><?= implode( "</p>\n<p>", $errors->get_error_messages() ); ?></p></div>
<? }
if ($_GET['updated'] == 'true') { ?>
  <p class="updated">Your changes have been saved.</p>
<? } ?>

<? if ( $new_email && ($new_email != $user->user_email) ) { ?>
  <p class="updated">We've sent an email to <?= esc_html($new_email['newemail']) ?>.<br/>Please click on the link in that email to verify your new e-mail address.</p>
<? } ?>

<h3>Please introduce yourself!</h3>
<div class="fields">

<div class="editfield field_2 field_name">
  <label class="field-label" for="field_1">Name <span class="optional">(required)</span></label>
  <div class="left">
    <input type="text" name="first_name" id="first_name" value="<?= esc_attr($user->first_name) ?>" class="regular-text" placeholder="first name" />
    <input type="text" name="last_name" id="last_name" value="<?= esc_attr($user->last_name) ?>" class="regular-text" placeholder="last name" />
    <div class="description">
      <? draw_check_option("only_first", "Show only my first name", $user->show_full_name != true); ?>
    </div>
  </div>
</div>

<div class="editfield about_me field_about-me">
  <label class="field-label" for="about_me">About Me <span class="optional">(optional)</span>
    <span style="display: block; font-size:0.9em; color: #666; padding: 15px 10px;">Why do you give? What causes do you support?</span></label>
  <textarea id="about_me" name="about_me" cols="48" rows="5"><?= esc_html($user->xp_about); ?></textarea>
</div>

<div class="editfield location field_location alt">
  <label class="field-label" for="location">Location <span class="optional">(optional)</span></label>
  <input type="text" value="<?= esc_attr($user->xp_location) ?>" id="location" name="location" placeholder="city, country, etc.">
</div>

</div>

<h3>Set up your account:</h3>
<div class="fields">

<div class="editfield field_email">
  <label class="field-label" for="user_login">Your page</label>
  <div class="left" style="width:500px;">
    <label for="user_login"><?= str_replace("http://","",site_url()) ?>/members/ </label>
    <input type="text" name="user_login" id="user_login" size="16" value="<?= esc_attr($user->user_login) ?>" class="regular-text" placeholder="username" />
  </div>
</div>

<div class="editfield field_email">
  <label class="field-label" for="email">E-mail address</label>
  <div class="left" style="width:500px;">
	<?
	if ( $new_email && ($new_email != $user->user_email) ) { ?>
    <div style="margin:4px 0 8px 0;">
      <b><?= esc_html($user->user_email) ?></b>
      <span style="font-size: 0.9em; color: #666;">
        until new address is confirmed (<a style="color: #c00; text-decoration: underline;" href="<?= esc_url( add_query_arg('dismiss', $user_id . '_new_email') )?>">cancel change</a>)
      </span>
    </div>
  <? } else {
    $e = isset($_POST['email']) ? $_POST['email'] : $user->user_email;
    ?>
    <input type="text" name="email" id="email" size="48" value="<?= esc_attr($e) ?>" class="regular-text" placeholder="e-mail address" />
  <? } ?>
  </div>
</div>

<? if (is_fb_connect_enabled()) { ?>
<div class="editfield field_facebook">
  <label class="field-label" for="facebook">Facebook</label>
  <div class="left" style="width:400px;">
    <?

if ($bp->loggedin_user->id == $user_id) {
  $connected = display_fb_connect_offer();
} else {
  $connected = intval(get_user_meta($user_id, 'fb_id', true)) > 0;

  echo '<div style="padding:8px 0;">';
  echo $connected ? '<b>Connected</b>'
                  : '(not connected)';
  echo '</div>';
}

 ?>
  </div>
</div>
<? 
  if ($connected && !current_user_can('level10')) 
    $show_password_fields = false;
} ?>

<? if ($show_password_fields ) { ?>
<div class="editfield field_email">
  <label class="field-label" for="email">New password?</label>
  <div class="left" style="width:400px;">
    <input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" class="left" style="margin-right:15px;" /><div class="description" style="margin-top:-2px; color: #666;">To change your password, enter a new one here.</div>
    <!-- todo: pop in a pass2 -->
  </div>
</div>
<? } ?>

</div>

<h3>How would you prefer we contact you?</h3>
<div class="fields">

<div class="editfield">
  <label class="field-label" for="donate-email">When I donate:</label>
  <div class="left" style="padding-top: 4px;">
    <? draw_check_option('donate-email', "Send me a confirmation in e-mail", !$user->no_thanks_email) ?>
    <? if ($connected) draw_check_option('donate-facebook', "Post a message to my Facebook wall", $user->fb_publish_thanks); ?>
  </div>
</div>

<div class="editfield">
  <label class="field-label" for="donate-email">My new stories:</label>
  <div class="left" style="padding-top: 4px;">
    <? draw_check_option('story-email', "Send me the story in e-mail", !$user->no_story_email) ?>
    <? if ($connected) draw_check_option('story-facebook', "Post the story to my Facebook wall", $user->fb_publish_story) ?>
  </div>
</div>

</div>

<div class="submit-row">
  <input class="button green-button medium-button" type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<? _e( 'Save Changes', 'buddypress' ) ?> " />
  or <a href="<?= $bp->displayed_user->domain ?>" class="button gray-button small-button">Cancel</a>
</div>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="user_id" id="user_id" value="<?= esc_attr($user_id); ?>" />

</form>
</div>
<?

function change_user_email($user_id, $new_email) {
  global $errors, $wpdb, $bp;

  if ( ! is_object($errors) )
    $errors = new WP_Error();

  // admins can change anyone's email
  if (!current_user_can('level_10') && $user_id != $bp->displayed_user->id) {
    return false;
  }

  if ( !is_email( $new_email ) ) {
    $errors->add( 'email', __( "Please enter a valid e-mail address." ), array( 'form-field' => 'email' ) );
    return false;
  }

  if ( $wpdb->get_var( $wpdb->prepare( "SELECT email FROM {$wpdb->users} WHERE email=%s", $new_email ) ) ) {
    $errors->add( 'email', __( "That e-mail address is already in use." ), array( 'form-field' => 'email' ) );
    delete_option( $user_id . '_new_email' );
    return false;
  }

  if (confirm_new_email($user_id, $new_email))
    return $new_email;
}

function clean_username($login) {
  $login = sanitize_user($login);
  return str_replace(array(' ','&'),array('-','and'), $login);
}

?>
