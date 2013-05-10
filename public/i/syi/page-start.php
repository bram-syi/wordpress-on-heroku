<?php
/**
 *
 */
define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation

global $bp, $wpdb, $errors, $current_user, $emailEngine, $event_id;
global $this_url;

define('DONOTCACHEPAGE',1);

standard_page();

global $NO_SHARING;
$NO_SHARING = TRUE;

define('FB_PLACEMENT', 'none');

include_once('campaign-core.php');

require_once( ABSPATH . 'wp-admin/includes/misc.php' );
require_once( ABSPATH . 'wp-admin/includes/template.php' );
require_once( ABSPATH . 'wp-admin/includes/user.php' );
require_once( ABSPATH . WPINC . '/registration.php' );

global $TAGS;
$TAGS = array(
  '' => 'any gift on SeeYourImpact.org',
  'water' => 'clean water',
  'education' => 'education and job training',
  'children' => "children's health and education",
  'hunger' => 'hunger prevention',
  'africa' => 'causes in Africa',
  'india,asia' => 'causes in Asia',
  'americas' => 'causes in Latin America'
);

wp_enqueue_script('plupload-all');

remove_action( 'personal_options_update', 'send_confirmation_on_profile_email' );
add_filter('wp_title', 'campaign_editor_title');
add_filter('body_class','profile_edit_body_class');
// add_action('campaign_gallery_editor','campaign_gallery_editor');
add_action('draw_editor_field','draw_editor_field', 0, 3);
add_action('draw_goal_row','draw_goal_row');
add_action('after_form_error', 'campaign_error_message');
add_action('campaign_editor_top', 'campaign_editor_top');

add_filter('sign_in_intro', 'campaign_sign_in_intro');
add_filter('sign_in_password', 'campaign_sign_in_password');

wp_reset_vars(array('action', 'redirect', 'profile', 'user_id', 'wp_http_referer'));

$errors = new WP_Error();
$this_url = remove_query_arg('update');

////////////////////////////////////////////////////////////////////////////////

global $warn_existing;
if ($bp->loggedin_user->id > 0)
  $warn_existing = has_active_campaign($bp->loggedin_user->id);

global $edit_campaign, $bp;
if ($edit_campaign || !empty($_REQUEST['id'])) {
  $id = $_REQUEST['id'];
  $campaign = campaign_init($id, TRUE);

  if ($edit_campaign || ($campaign->id == $warn_existing))
    $warn_existing = NULL;
} else {
  $campaign = campaign_init(-1, TRUE);
}

if ($bp->displayed_user->id == 0)
  $bp->displayed_user->id = $bp->loggedin_user->id;
if($bp->displayed_user->id != 0 && $bp->displayed_user->id != $bp->loggedin_user->id && !is_super_admin()) {
  wp_redirect(eor($bp->displayed_user->domain, "/")); die;
}

$p = array();
$p_media = array();

if ($campaign)
  $id = $campaign->id;
else
  $id = 0;

global $start_step;
$start_step = intval($_POST['step']);
if ($start_step == 0) {
  if ($id > 0)
    $start_step = 3;
  else if (isset($_REQUEST['theme']))
    $start_step = 2;
  else
    $start_step = 1;
}

////////////////////////////////////////////////////////////////////////////////

if ($start_step > 1) {
if (!empty($id)) {
  // TODO: use $campaign directly instead of merging into this array
  $p = array_merge( get_post($id, 'ARRAY_A'), (array)$campaign );

  $p['syi_gift_ids'] = get_post_meta($id, 'syi_gift_ids', true);
  $p['syi_twitter_handle'] = get_post_meta($id, 'syi_twitter_handle', true);
  $p = apply_filters('load_campaign_metadata', $p);
  $p = apply_filters('campaign_editor_defaults', $p);
} else {
  global $bp;
  $user_id = eor($bp->displayed_user->id, $bp->loggedin_user->id);
  $p = create_campaign_post($user_id, NULL);

  // Special case for team passed as parameter
  if (isset($_REQUEST['team']))
    $p['team'] = stripslashes($_REQUEST['team']);

  $p = apply_filters('update_campaign_metadata', $p);
}

////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['is_contact'])) {
  // TODO: generalize this so it's not cart_signin
  $cart = NULL;
  $result = (object)process_cart_signin($cart, stripslashes_deep($_REQUEST));
} else if ($_POST && !is_user_logged_in()) {
  $t = eor($campaign->fundraisers->type, "fundraiser");
  $result = form_error("Please sign in to start your $t.");
}

switch ($_REQUEST['action']) {
  case 'update':
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'update-post')) {
      // die(__('You do not have permission to edit this.'));
    }

    // SANITIZE TO STORE

    $args = stripslashes_deep($_REQUEST);
  
    $content = $_POST['post_content'];
    $c = to_store_html($content, FALSE);
    $p['post_content'] = to_store_html($content);
    $contains_html = ($c != $p['post_content']);

    $p['post_title'] = to_store($args['post_title']);
    $p['post_type'] = CAMPAIGN_POST_TYPE;
    $p['goal'] = absint(from_money($args['goal']));
    if ($p['goal'] <= 0)
      $p['goal'] = NULL;
    $p['syi_gift_ids'] = $args['syi_gift_ids'];
    $p['syi_twitter_handle'] = strip_non_alphanumunderscore(str_replace(" ","_",$args['syi_twitter_handle']));

    $p['team'] = to_store($args['team']);
    if ($p['team'] == 'other')
      $p['team'] = to_store($args['other_team']);

    $p['post_author'] = eor($bp->displayed_user->id, $bp->loggedin_user->id);
    $p['owner'] = $p['post_author'];
    $p = apply_filters('update_campaign_metadata', $p);
    $p = apply_filters('campaign_editor_defaults', $p);

    $p['custom'] = eor($args['custom'], array());

    // Saved whatever data was passed, but there's already an error?
    if (is_error_result($result) || ($start_step < 2))
      break;

    $errors = apply_filters('campaign_editor_form_errors', $errors, $campaign, $p);

    if (empty($p['post_title']))
      $errors->add( 'post_title', __( "give your page a title" ), array( 'form-field' => 'post_title' ) );

    if (empty($p['post_content'])) 
      $errors->add( 'post_content', __( "enter your personal message" ), array( 'form-field' => 'post_content' ) );

    if (isset( $errors ) && is_wp_error($errors) && $errors->get_error_code())
      break;

    if ( campaign_differences($p, $warn_existing) ) {
      $result = start_campaign($p, $warn_existing);
      if (!is_error_result($result))
        $start_step = 3;
    }
    else {
      wp_redirect( get_member_link($bp->displayed_user->id ));
    }

    break;

  case 'archive':
    if ($id==0 || !wp_verify_nonce($_REQUEST['_wpnonce'], 'archive')) {
      wp_redirect( $this_url );
      die;
    }

    end_campaign($id);
    $result = form_success( get_member_link($bp->displayed_user->id) );
    break;
}

if ( isset( $errors ) && is_wp_error( $errors ) && count($errors->errors) > 0 ) {
  $fields = array();
  foreach ($errors->get_error_codes() as $code) {
    $data = $errors->get_error_data($code);
    if (is_array($data)) 
      $fields[] = $data['form-field'];
  }
  $result = form_error("Please " . comma_list( $errors->get_error_messages() ) . ".", $fields);
} 

if (isset($IS_AJAX) && $IS_AJAX) {
  if ($result !== NULL) 
    die(json_encode($result));

  do_action('form_ajax_result');
  die;
} else if ($result !== NULL) {
  switch ($result->status) {
    case 'success':
    case 'OK':
      wp_redirect($result->data); die;
  }
}
}


if ($start_step == 1 && $_POST && is_user_logged_in() && !is_error_result($result))
  $start_step = 2;

$blah = 1;
if (!empty($p['theme']))
  $blah = 2;

$c = CampaignApi::getOne($p['theme']);
if ($c) {
  if ($c->can_join === FALSE) {
    wp_redirect($c->url);
    die;
  }
}

// Which fields to display, in what order?
if ($start_step > $blah) {
  $fields = apply_filters("campaign_editor_fields", array('title', 'body', 'team', 'goal')); 
} else {
  $fields = apply_filters("campaign_editor_fields", NULL);
/* TODO: should we do this?
  if ($is_themed && ($fields == NULL || count($fields) == 0))
    $fields = apply_filters("campaign_editor_fields", array('title', 'body', 'goal')); 
*/
}
$fields = eor($fields, array());
if (!($id > 0 || isset($_REQUEST['theme'])))
  array_unshift($fields, 'tags');
if (!is_user_logged_in())
  array_unshift($fields, 'signin');

$action = $_REQUEST['action'];
// No fields to fill in? Jump straight to the campaign
if (!is_error_result($result) && count($fields) == 0) {
  $action = 'update';
}


if (!is_user_logged_in())
  remove_action('syi_pagetop', 'draw_the_crumbs', 0);

// DESANITIZE FOR DISPLAY
$p['post_title'] = to_print($p['post_title']);
$p['post_content'] = to_print($p['post_content']);

$labels = apply_filters('campaign_editor_labels', array(
  'title' => "Create a catchy title:",
  'body' => 'Share why you <img src="http://seeyourimpact.org/wp-content/images/heart.png" style="vertical-align: middle;"> this cause:'
));

if ($start_step == 1) {
  // 1st step is just a standard content page

  if ($post == NULL) {
    // TODO: error_log(implode(':', array(__FILE__, __LINE__)));
    wp_redirect("/start");
    die;
  }

  // TODO: error_log(implode(':', array(__FILE__, __LINE__)));
  include_once('standard-page.php');
  exit();
}

// Hide the crumbs during signup
remove_action('get_crumbs', 'member_crumbs');

get_header();

?>
<script type="text/javascript">
var vars_main = 'main&uid=<?=$bp->displayed_user->id?>&id=<?=$id?>';
var vars = 'id=<?=$id?>';
var max_media_count = <?=CGW_MAX_MEDIA?>;
</script>
<script type="text/javascript" src="/wp-content/themes/syi/campaign-editor.js"></script>
<form method="post" class="profile-panel standard-form" onsubmit="return check_wait();"><div></div>

<? if ($event_id > 0) draw_campaign_help($event_id, 1); ?>

<div id="start-page" class="start-page standard-page campaign-editor-page">
  <div class="page-main">
    <input type="hidden" name="syi_gift_ids" value="<?= xml_entities($p['syi_gift_ids']) ?>" id="syi_gift_ids" />
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="id" value="<?=$id?>" />
    <input type="hidden" name="from" value="campaign" />
    <input type="hidden" name="step" value="<?= $start_step ?>" />
    <? 
    wp_nonce_field("update-post");

    $t = eor($TEMPLATE->fundraisers->type, "fundraiser");
    if ($event_id > 0 && apply_filters('can_end_fundraiser', TRUE)) {
      ?><div class="end-fundraiser">
      Finished?
      <a href="<?=wp_nonce_url(add_query_arg(array('action'=>'archive')), 'archive')?>" class="button small-button white-button" onclick="return confirm('Are you sure you want to end this <?=$t?>?');">end this <?=$t?></a>
      </div><?
    }

    do_action('campaign_editor_top', $campaign);

    if (isset($result) && $result->status == "error")
      display_form_error($result);
    else if ($_GET['updated'] == 'true') { 
      ?><p class="updated">Your changes have been saved.</p><?
    }

    ?><div class="fields start-fields"><?
    foreach ($fields as $field) {
      do_action('draw_editor_field', $p, $field, $labels);
    } 
    ?></div><?

    ?>
    <div class="submit-row" style="position: relative; margin-top: 20px;">
      <?campaign_submit_button($event_id, $this_url, FALSE); ?>
    </div>

    <? if ($id > 0) { do_action('campaign_gallery_editor', $id); } ?>
  </div>
</div>
</form>

<script>
$('form').submit(function(){
    // On submit disable its submit button
    $('input[type=submit]', this).
      attr('disabled', true).
      attr('value', 'Please wait...');
});
</script>
<?

get_footer();

// compares $p to $existing
// return empty string if the campaigns are the same, or a string describing what is different
function campaign_differences($p, $existing) {
  global $wpdb;
  $campaign_differences = array();

  if ($existing > 0) {
    $old_post = get_post($existing);

    foreach (array("post_content", "post_title") as $field) {
      if ($p[$field] != $old_post->$field) {
        $campaign_differences[$field] = 1;
      }
    }

    $old_team = $wpdb->get_var($wpdb->prepare(
      "select team from campaigns where post_id = %d", $existing
    ));
    if ($old_team != $p['team']) {
      $campaign_differences['different_team'] = 1;
    }
  }
  else {
    $campaign_differences['no_previous_campaign'] = 1;
  }

  if (count($campaign_differences)) {
    $campaign_differences = implode(",", array_keys($campaign_differences));
  }
  else {
    $campaign_differences = '';
  }

  return $campaign_differences;
}

function get_contact_link() {
  global $TEMPLATE;

  $name = $email = "contact@seeyourimpact.org";
  if ($TEMPLATE->contact) {
    $email = eor($TEMPLATE->contact->email, $email);
    $name = eor($TEMPLATE->contact->name, $TEMPLATE->contact->email);
  }
  if (!empty($name) && $name != $email)
    $name .= " at {$email}";
  return "<a href=\"mailto:{$email}\">" . xml_entities($name) . "</a>";
}
add_shortcode('contact', 'get_contact_link');
 
function campaign_error_message() {
  $contact = get_contact_link();
  $t = eor($TEMPLATE->fundraisers->type, "fundraiser");
?>
  <span style="display:block; font-size:80%; margin-top:10px; color: #A44;">
  Having trouble setting this up? Contact <?=$contact?> for help!
  </span>
<?
}


function campaign_gallery_editor($id) {
  $posts = get_cgw_posts($id, 100, null, true);
  ?>
  <section class="editor-section" id="photo-gallery" style="margin-right: -20px;">
    <h2 class="title">Tell your story with pictures and video.</h2>
    <? syi_campaign_gallery_widget($posts, 1, true); ?>
  </section>
  <?
}

function draw_option($tag, $selected, $label) {
  $selected = ($tag == $selected);
?>
  <option value="<?= $tag ?>" <? selected($selected) ?>><?= $label ?></option>
<?
  return $selected;
}

function campaign_submit_button($id, $this_url) {
  global $bp;
  global $start_step;
  global $warn_existing;
  global $event_theme;

  if ($id > 0) { 
    ?><input type="hidden" name="is_campaign" value="yes"><?
    ?><input class="button green-button medium-button" type="submit" name="submit" id="submit" value="Save changes &raquo" /><?
  } else {
    
    if ($start_step == 2) {
      $label = "Check out your page";
      ?><input type="hidden" name="is_campaign" value="yes"><?
    } else {
      $label = "Get started - it's easy!";
    }

    ?>
<!--
-->
    <div style="padding: 20px; margin: 0 auto; border: 4px solid #92D4EC; width: 280px; border-radius: 10px;">
      <p style="margin: 0 0 25px;">
<b>Thanks! That's all we need to create your personal fundraising page.</b><br>
(Don't worry - you can still customize it before you share it with the world.)
</p>
      <div style="text-align:center;"> <input class="button green-button medium-button" type="submit" name="submit" id="submit" value="<?=$label?>" /> </div>
      <? if ($start_step > 1) { ?>
        <!-- <div style="margin: 15px 0 20px 40px;">or <a class="link" href="/start">select a different cause</a></div> -->
      <? } ?>
    </div>

    <? if ($start_step > 1 && $warn_existing > 0) { ?>
        <div style="color:#F60; margin: 20px auto; font-size: 21px">
          <img src="<?= __C('images/warn_msg.png') ?>" style="float:left; padding-right: 10px; width:30px;">
          <div style="padding-top: 2px;">
          Your <a class="link" target="_new" href="<?= get_campaign_permalink($warn_existing) ?>">existing fundraiser</a> will be ended when you start this one.
          </div>
        </div>
    <? } ?>
    <?
  } 
}

function draw_select_option($option, $value, $text = NULL) {
?>
  <option value="<?=$option?>" <?= selected($option,$value) ?>><?= xml_entities(eor($text,$option)) ?></option>
<?
}

function draw_editor_field($p, $field, $labels) {
  global $TAGS, $start_step;

  switch ($field) {
    case 'signin':
      $cart = new stdClass;
      ?><div class="signin-form"><?
      display_cart_signin_form(stripslashes_deep($_REQUEST));
      ?></div><?
      break;

    case 'title':
      ?>
      <div class="editfield full-width" id="title-row">
        <label class="above full-width" for="post_title"><?= $labels['title']?></label>
        <input type="text" name="post_title" id="post_title" value="<?=esc_attr($p['post_title'])?>" size="70" maxlength="100" class="full-width focused" />
      </div>
      <?
      break;

    case 'body':
      ?>
      <div class="editfield full-width" id="body-row">
        <label for="post_content" class="above full-width"><?= $labels['body']?></label>
        <? 
        $contains_html = strlen($p['post_content']) != strlen(strip_tags($p['post_content'],'<b><strong><strike><i><em><p><br>'));
        if (current_user_can("level_2") || !$contains_html) { 
          $content = xml_entities($p['post_content']);
          ?>        
          <textarea id="post_content" name="post_content" class="full-width focused" cols="60" rows="8"><?=$content?></textarea>
          <? 
        } else {
          ?>
          <div style="padding:5px; border: 1px solid #ccc; color:#999; font-weight:bold;">This campaign has been customized -- 
          please <a href="mailto:contact@seeyourimpact.org"><u>contact us</u></a> if you'd like to make changes to the body text</div>
          <?    
        }
        ?>
      </div>
      <?
      break;

    case 'team':
      global $TEAMS;
      if (count($TEAMS) == 0) {
        if (array_key_exists('organization', $p)) {
          $k = $p['organization'] . '/' . $p['theme'];
          $TEAMS = Team::get_teams($k);
        }
        else {
          break;
        }
      }

      $team = $p['team'];
      if (empty($team) || in_array($team, $TEAMS))
        $hidden = "hidden ";
      else {
        $other_team = $team;
        $team = "other";
      }

      ?>
      <div class="editfield full-width" id="location-row">
        <label for="team" class="above full-width">Are you on a fundraising team? (Optional)</label>
        <select name="team" id="team" style="margin-left:20px;">
           <?
           draw_select_option("", $team, "choose a team...");
           foreach($TEAMS as $c) {
             draw_select_option($c, $team);
           }
           draw_select_option("other", $team, "other...");
           ?>
        </select>
        <input type="text" name="other_team" id="other_team" value="<?=esc_attr($other_team)?>" size="25" maxlength="300" class="<?=$hidden?>focused" />
      </div>
      <?
      break;

    case 'goal':
      ?>
      <div class="editfield full-width" id="goal-row">
        <? do_action('draw_goal_row', $p); ?>
      </div>
      <?
      break;

    case 'twitter_handle':
      ?>
      <div class="editfield full-width" id="twitter-row">
        <label for="syi_twitter_handle">Include messages from Twitter: </label>
        @ <input type="text" value="<?= $p['syi_twitter_handle'] ?>" id="syi_twitter_handle" name="syi_twitter_handle" size="20" maxlength="50" class="with-tip"/> <span class="optional">(optional)</span>
      </div>
      <?
      break;

  }
}

function draw_goal_row($p) {
?>
  <label for="goal">Set a goal to raise $</label>
  <input type="text" value="<?=$p['goal']?>" id="goal" name="goal" size="5" maxlength="5" class="with-tip"/><span class="optional">(You can always increase this later!)</span>
<?
}

function campaign_editor_title($title, $sep = ' - ') {
  global $event_id;

  if (bp_is_my_profile()) {
    if ($event_id > 0)
      return "Update your fundraiser page$sep";

    return "Start a fundraiser page$sep";
  }

  return get_campaign_title($event_id) . $sep;
}

function draw_page_title($title) {
  ?><h1 class="entry-title heading full-wide"><?= $title ?></h1><?
}

function campaign_editor_top($campaign = NULL) {
  if ($campaign->id > 0) { 
    echo sidebar_widget(array('id' => 'edit-fundraiser-sidebar'));
    draw_page_title('Update your fundraiser page');
  } else { 
    echo sidebar_widget(array('id' => 'start-fundraiser-sidebar'));
    draw_page_title('Start a fundraiser!');
  }
}

function campaign_sign_in_intro($text) {
  return "Please sign in:";
}
function campaign_sign_in_password($text) {
  return '';
}

