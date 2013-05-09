<?

include_once('campaign-core.php');

global $NO_SHARING;
$NO_SHARING = TRUE;

function update_post_open_graph_meta() {
  global $post;

  if ($_REQUEST['update']) {
    $update = SyiFacebook::update_as_array($_REQUEST['update'], $post->ID);
    echo SyiFacebook::array_as_metatags($update);
  }
}
add_action('syi_meta_tags', 'update_post_open_graph_meta');

add_filter('wp_title', 'updates_title');
add_action('get_sidebar', 'do_nothing');
add_filter('body_class','cart_body_class');
add_filter('wp_footer', 'updates_footer_scripts');

$campaign = campaign_init();

handle_update_post($campaign);

get_header();

draw_campaign_updates($campaign);

get_footer();

function draw_campaign_updates($campaign) {
  global $more, $event_id, $current_user, $event_theme;
  get_currentuserinfo();

  draw_sharing_vertical();
?>
<section class="profile-panel">
<div></div>
<div class="campaign-page updates-page">
  <div class="campaign-sidebar page-sidebar">
    <? do_action('draw_campaign_sidebar', $campaign); ?>
  </div>
  <div class="campaign-content box">
    <? draw_update_actions($campaign); ?>
    <div class="based">
      <? draw_sharing_horizontal(); ?>
      <? 
      if ($_REQUEST['update'])
        draw_update($campaign, $_REQUEST['update']);
      else
        draw_all_updates($campaign);
      ?>
    </div>
  </div>
</div>
</section>
<?
}

function draw_update_actions($campaign) {
  if (!is_my_campaign($campaign->id))
    return;

  $links = get_campaign_action_links($campaign->id);

  $hid = is_showing_form() ? " hidden" : "";
  $sent = $_REQUEST['posted'] !== NULL;

  if ($sent)
    $title = "Your update has been sent!";
  else
    $title = "Share your story!";

?>
  <div class="update-actions <?=$hid?>">
    <h2 class="left"><?=$title?></h2>
    <div class="right">
      <? if (!$sent) { ?>
        <a id="updates-send" href="" class="green-button button ev">Send an update</a>
      <? } ?>
      <?= draw_invite_link("campaign/".encrypt($links->id), '', TRUE); ?>
    </div>
  </div>
<?
}

function add_update(&$campaign, $args) {
  global $wpdb;
  global $bp;

  // Check post variables for problems first
  $title = trim($args['title']);
  if (empty($title)) {
    $c = count(get_updates($campaign->id));
    $title = "Update #" . ($c+1);
  }

  $embed = null;
  if ($_POST['type'] == 'video') {
    $video = trim($args['video']);
    if (empty($video)) 
      return form_error('Please enter the link to your video on YouTube or Vimeo.', 'video');

    $embed = wp_oembed_get($video);
    if (empty($embed))
      return form_error("Sorry, that video link isn't recognized as a YouTube or Vimeo video.", 'video');
  }

  $body = trim($args['body']);
  if (empty($body))
    return form_error('Please enter some text for your update.', 'body');

  $p = array();
  $p['post_status'] = 'publish';
  $p['post_type'] = 'update';
  $p['post_title'] = to_store($title);
  $p['post_content'] = to_store_post_content($body);

  $id = wp_insert_post($p);
  add_post_meta($id, 'fr', $campaign->id, TRUE);
  $error = is_wp_error($id);

  if (!$error) {
    if (!empty($video))
      update_post_meta($id, 'video', $video);

    $facebook = new SyiFacebook(get_current_user_id());
    if (array_key_exists('post_to_facebook', $args)) {
      $facebook->set_permission('publish_update', $args['post_to_facebook'] ? 1 : 0);
    }
    $facebook->publish_update($id, $campaign->id);

    update_updates($campaign);

    $recipients = array();
    if ($args['send_updates'] == 1) {
      $supporters = get_supporters($campaign->id);
      foreach ($supporters as $supporter) {
        if (strpos($supporter->email, '@') === FALSE)
          continue;
        if (!empty($supporter->name)) { 
          $to = "\"$supporter->name\" <$supporter->email>";
          $recipients[] = $to;
        } else {
          $recipients[] = $supporter->email;
        }
      }
    }

    $theme_contents = $wpdb->get_var( $wpdb->prepare(
      'select contents from theme_data where name = %s', $campaign->theme
    ));
    if ($theme_contents) {
      $json = json_decode($theme_contents, 1);
      if ($json) {
        $h2o = $json['h2o'];
      }
    }

    if (!$h2o) {
      $h2o = array(
        'first_banner_bg' => '#000000',
        'first_banner_fg' => '#ffffff',
        'second_banner_bg' => '#e17342',
        'second_banner_fg' => '#ffffff',
        'template_name' => 'update'
      );
    }

    // normalize video url using wp_oembed_get()
    if (preg_match('/ src="(.*?)"/', $embed, $m)) {
      $video = $m[1];
    }

    $owner_id = get_campaign_owner($campaign->id);
    $name = get_displayname($owner_id);
    $vars = array_merge(
      array(
        'campaign' => $campaign->theme,
        'url' => $campaign->guid, //  . "updates" <- later when page is more useful
        'owner' => $name,
        'title' => $title,
        'video' => $video,
        'photo' => image_src(fundraiser_image_src($campaign), 150,150),
        'message' => to_store_html($body)
      ),
      $h2o
    );

    $user = get_userdata($owner_id);
    $reply_to = $user !== false ? $user->user_email : 'impact@seeyourimpact.org';

    $headers = array(
      'From' => '"' . $name . ' via SeeYourImpact.org" <impact@seeyourimpact.org>',
      'Reply-to' => $reply_to,
      'X-Tag' => "email:fr_update,theme:$campaign->theme",
      'syi-bcc' => get_email_address('outreach'),
      'syi-update' => $id
    );

    if ($args['post_to_facebook']) {
      $headers['syi-post-facebook-update'] = 1;
    }

    SyiMailer::send(
      $recipients,
      $title,
      $h2o['template_name'],
      $headers,
      $vars
    );

    return form_success();
  }

  return form_error("Your update could not be sent.");
}

function handle_update_post(&$campaign) {
  if (!can_manage_campaign($campaign->id))
    return;

  if ($_POST && wp_verify_nonce($_POST['action'], 'post-update')) {
    $args = stripslashes_deep($_POST);
    $campaign->add_result = add_update($campaign, $args);
    if (!is_error_result($campaign->add_result)) {
      wp_redirect(add_query_arg("posted", 1, remove_query_arg('new'))); die;
    }
  } 
}

function is_showing_form() {
  return ($_POST || isset($_REQUEST['new']));
}

function new_update(&$campaign, $c = 0) {
  if (!can_manage_campaign($campaign->id))
    return NULL;

  if (is_error_result($campaign->add_result))
    display_form_error($campaign->add_result);

  $args = stripslashes_deep($_POST);
  $type = eor($_POST['type'], 'text');
  $hid = is_showing_form() ? "" : " hidden";
  if (!array_key_exists('submit', $args)) {
    $send_email = 1;
    $owner_id = get_campaign_owner($campaign->post_id);
    $facebook = new SyiFacebook($owner_id);
    $post_to_facebook = $facebook->get_permission('publish_update') ? 1 : 0;
  }
  else {
    $send_email = $args['send_updates'];
    $post_to_facebook = $args['post_to_facebook'];
  }
  ?>
  <form class="standard-form update-form <?=$hid?>" method="POST">
    <h2>What's new? <span class="normal"> Send an update to your supporters!</span></h2>
    <div class="update-tabs">
      <div id="text-tab" class="update-tab <? if ($type == 'text') echo 'active'; ?>"><input type="radio" name="type" value="text"><b>Message</b> only</div>
      <!--<div id="photo-tab" class="update-tab <? if ($type == 'photo') echo 'active'; ?>"><input type="radio" name="type" value="photo"><b>Photo</b> &amp; message</div>-->
      <div id="video-tab" class="update-tab <? if ($type == 'video') echo 'active'; ?>"><input type="radio" name="type" value="video">include a <b>video</b></div>
    </div>

    <? wp_nonce_field("post-update", "action"); ?>
    <div class="fields">
      <div class="labeled" style="width: 600px;">
        <label for="update-text">Give this update a subject line? (optional)</label>
        <input type="text" name="title" id="update-title" class="update-title" value="<?= esc_attr($args['title']) ?>"/>
      </div>
      <div class="hidden video-field">
        <div class="labeled" style="width: 300px;">
          <div class="upload-instructions">
           You can paste in a youtube or vimeo address, such as:<br>
           http://www.youtube.com/watch?v=Q8B3yMPbXkg
          </div>
          <label for="update-video">Web address of your video</label>
          <input type="text" name="video" id="update-video" class="update-title focused" value="<?= esc_attr($args['video']) ?>"/>
        </div>
      </div>
      <div class="labeled" style="width:600px;">
        <textarea name="body" class="update-text focused" id="update-text"><?=$args['body'] ?></textarea>
      </div>
      <div style="width:600px;">
        <input type="submit" class="button green-button medium-button" name="submit" value="Post this update">
        <? if ($campaign->supporters_count > 0) { ?>
          <span class="right mail-warning">
          <input type="checkbox" name="send_updates" value="1" id="send_updates" <?= $send_email ? 'checked="1"' : '' ?>/>
            <label for="send_updates"> send this update via e-mail to your <b><?= plural($campaign->supporters_count, "supporter") ?></b></label>
          <br/>
          <? $x = get_user_meta($campaign->owner, 'fb_id'); if ($x) { ?>
          <input type="checkbox" name="post_to_facebook" value="1" id="post_to_facebook" <?= $post_to_facebook ? 'checked="1"' : '' ?>/>
            <label for="post_to_facebook">post this update to my Facebook wall</label>
          <? } ?>
          </span>
        <? } ?>
      </div>
    </div>
  </form>
  <?
}

function get_updates($id) {
  $posts = query_posts(array(
    'post_type' => 'update',
    'post_status' => 'publish',
    'meta_key' => 'fr',
    'meta_value' => $id,
    'posts_per_page' => -1
  ));

  return $posts;
}

function update_updates(&$campaign) {
  global $wpdb;
  $u = $sep = "";

  $updates = get_updates($campaign->id);
  for ($i = 0; $i < count($updates); $i++) {
    $u .= $sep . $updates[$i]->ID;
    $sep = ",";
  }

  $wpdb->update("campaigns",
    array( 'updates' => $u ),
    array( 'post_id' => $campaign->id ),
    array( '%s' )
  );
  $campaign->updates = $u;
}

function draw_update($campaign, $update, $linked = 0) {
  if (is_numeric($update)) {
    $fr = get_post_meta($update, 'fr', TRUE);
    if ($fr != $campaign->id)
      return FALSE;
    $update = get_post($update);
  }

  $title = eor($update->post_title, "Update");

  ?><div class="fr-update"><h3 class="fr-update-title"><?
  if ($linked) {
    ?><a href="?update=<?= $update->ID ?>"><?
  }
  echo xml_entities($title);
  if ($linked) {
    ?></a><?
  }

  $attachment = get_post_meta($update->ID, 'video', TRUE);
  $update->post_content = "$attachment\n$update->post_content";

  ?></h3>
    <div class="fr-update-byline byline"><? /* by
      <a href="<?= get_member_link($update->post_author) ?>"><?= get_displayname($update->post_author, TRUE) ?></a>*/?>Posted
      on <?= date('F j', strtotime($update->post_date)) ?>
    </div>
    <div class="fr-update-body">
      <?= apply_filters('the_content', $update->post_content) ?>
    </div>
  </div>
  <?
}

function draw_all_updates($campaign) {
  $name = eor(get_firstname($campaign->owner), "this fundraiser");

  $updates = as_ints($campaign->updates);
  $c = count($updates);

  $result = new_update($campaign, $c);
  if ($result === NULL && $c == 0 && !is_showing_form()) {
    ?><h2>This fundraiser has no updates.</h2><?
  }

  $updates = query_posts(array(
    'post_type' => 'update',
    'post__in' => explode(',', $campaign->updates),
    'posts_per_page' => 80
  ));
  foreach ($updates as $update) { 
    draw_update($campaign, $update, TRUE);
  }
}

function updates_title($t, $sep = ' - ') {
  return "Updates$sep";
}

function updates_footer_scripts() {
?>
<script>
$(function() {
  $('#updates-send').click(function(e) {
    $(".update-actions").hide();
    set_focus($(".update-form").fadeIn(100));
    return false;
  });

  $(".update-tab").click(function(e) {
    $(this).addClass('active').siblings().removeClass('active');
    set_focus($(".update-form").attr('id', 'update-' + $(this).attr('id')));
    $(this).find('input:radio').attr('checked',true);
  });
  $(".update-tab.active").click();

});
</script>
<?
}
