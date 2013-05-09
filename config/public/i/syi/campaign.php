<?
global $NO_SIDEBAR, $norm_url, $event_id, $event_theme, $post, $bp, $wpdb, $pledge_id, $header_file, $context;
$NO_SIDEBAR = TRUE;

include_once('campaign-core.php');
include_once(APIPATH.'/campaign.php');

if(isset($_REQUEST['pledge'])) {
  init_pledge_cookie(decrypt($_REQUEST['pledge']));  
  wp_redirect(remove_query_arg('pledge'));
  exit();
}

if (isset($_GET['referrer']) && intval($_GET['referrer'])>0) {
  unset($_COOKIE['referrer']);
  $setcookie = setcookie('referrer', intval($_GET['referrer']), time()+3600, "/", 
	".".str_replace(array("https://","http://"),"",get_bloginfo('url')));		  
  $_COOKIE['referrer'] = intval($_GET['referrer']);  

  log_invite_visit($_GET['referrer']);
  wp_redirect(remove_query_arg('referrer'));
}
global $GIFTS_V2,$GIFTS_EVENT,$GIFTS_LOC,$FB_XID;
$GIFTS_V2 = true;
$GIFTS_EVENT = $event_id;
$GIFTS_LOC = "ev/$post->post_name";
$FB_XID = "ev-$event_id";

function init_campaign_scripts() {
  $js = apply_filters('get_js_dir', '');
  $css = apply_filters('get_css_dir', '');

  wp_enqueue_style('qtip', "$css/jquery.qtip.css");
  wp_enqueue_script('twitter', "http://platform.twitter.com/widgets.js");
  wp_enqueue_script('qtip', "$js/jquery.qtip.pack.js");
  wp_enqueue_script('cgw', "$js/campaign-gallery.js");

  //campaign scripts
  wp_enqueue_script('youtube-api', 'http://www.youtube.com/player_api');

  add_action('draw_campaign_title', 'draw_campaign_title');
  add_action('draw_campaign_content', 'draw_campaign_content');
  add_action('draw_campaign_stats', 'draw_campaign_stats');
  add_action('draw_campaign_appeal', 'draw_campaign_appeal');

  add_filter('body_class','campaign_skin_body_class');
  add_action('syi_meta_tags', 'campaign_meta_tags');
  add_action('syi_meta_tags', 'campaign_private');
  add_action('init', 'load_custom_skin', 100);
  add_filter('wp_title', 'campaign_title');
  add_action('get_sidebar', 'do_nothing');
  add_action('get_crumbs', 'member_crumbs');
  add_filter('body_class','profile_body_class');
  add_action('after_campaign_appeal_message', 'draw_latest_update');
}
init_campaign_scripts();

campaign_init();
if ($event_id == 0) {
  $uid = $bp->displayed_user->id;
  if ($uid == 0)
    wp_redirect("/");
  else if ($uid == $bp->loggedin_user->id)
    wp_redirect(get_member_link($uid, 'campaign/edit'));
  else
    wp_redirect(get_member_link($uid));
  die;
}

// Is this the holding account for a specific campaign?
$camp = CampaignApi::getOne(array( 'fr_id' => $event_id ));
if ($camp != NULL && $camp->url != NULL) {
  $url = $camp->url;
  $qs = $_SERVER['QUERY_STRING'];
  if (!empty($qs))
    $url = "$url?$qs";
  wp_redirect($url);
  die;
}

if (!empty($_GET['skip-invite'])) {
  if (wp_verify_nonce($_GET['skip-invite'], 'skip-invite')) 
    update_post_meta($post->ID, "has_invited", true);
  wp_redirect(remove_query_arg("skip-invite"));
  die;
}

if ($_POST && wp_verify_nonce($_POST['edit-campaign'], 'edit-campaign')) {
  $tags = array();
  if ($_REQUEST['is_public'] == 1 || $_REQUEST['is_featured'] == 1) 
    $tags[] = "public";
  if ($_REQUEST['is_featured'] == 1) 
    $tags[] = "featured";
  $wpdb->query($wpdb->prepare(
    "UPDATE campaigns SET public=%d, featured=%d WHERE post_id=%d",
	  intval($_REQUEST['is_public'] == 1 || $_REQUEST['is_featured'] == 1),
	  intval($_REQUEST['is_featured'] == 1), $event_id));
  wp_set_post_tags($post->ID, implode(',', $tags), false);
  wp_redirect($norm_url);
  die();
}

get_header();

if (can_manage_campaign($event_id)) {
  ?><input id="ajax-key" type="hidden" value="<?= esc_attr(encrypt(json_encode(array('eid' => $event_id)))) ?>" /><?
}

$campaign = get_campaign_stats($event_id);
$campaign->context = $context;
$owner = intval($campaign->owner);
if ($owner == 0)
  $owner = $post->post_author;
else if (is_singular()) {
  // If we're on /support/XX, redirect to member page
  $url = add_query_arg($_GET, get_member_link($owner));
  $base = explode('?', $url);
  if ($base[0] != $_SERVER['SCRIPT_URI']) {
    wp_redirect($url);
    die;
  }
}

$editLink = get_member_link($owner, "campaign", "edit");
$editLink = add_query_arg('id', $event_id, $editLink);
$has_invited = get_post_meta($event_id, 'has_invited', true);

?><section class="profile-panel"><div style="clear:both;"></div><?

/* Messages */
$campaign_step = 0;
if (isset($_GET['msg'])) {
  draw_campaign_help_message($_GET['msg'], $campaign);
} else if (is_my_campaign($campaign->id)) {
  if (array_key_exists('preview', $_GET)) {
    $campaign_step = 1;
    if (!can_manage_campaign($event_id)) {
      wp_redirect(remove_query_arg('preview'));
      die;
    }
  } else if (array_key_exists('invite', $_GET) || empty($has_invited))
    $campaign_step = 2;
  else
    $campaign_step = 3;
} else {
  draw_campaign_help_message('activity', $campaign);
} 

switch ($campaign_step) {
  case 1: add_action('draw_campaign_help', 'preview_campaign_help'); break;
  case 2: add_action('draw_campaign_help', 'invite_campaign_help'); break;
  case 3: add_action('draw_campaign_help', 'finished_campaign_help'); break;
}

draw_campaign_help($event_id, $campaign_step);
do_action('draw_campaign_appeal', $campaign);
do_action('draw_campaign_stats', $campaign);
do_action('before_campaign_content', $campaign);
do_action('draw_campaign_content', $campaign);
?>
<? if (is_campaign_admin()) { ?>
  <form method="POST" class="focus admin-actions">
    <? wp_nonce_field('edit-campaign','edit-campaign') ?>

    <span class="edit-link">
      <i class="icon icon-edit"></i> <a href="<?= $editLink ?>" class="post-edit-link">manage campaign</a>
    </span>
    <?php edit_post_link( __( 'advanced edit', $theme_name ), '<span class="sep">|</span><span class="edit-link">', '</span>', $event_id ); ?>
    <? if (!empty($event_theme)) echo "<span class=\"sep\">|</span> theme: $event_theme "; ?>
    <span class="sep">|</span>
    <input id="is_public" type="checkbox" name="is_public" value="1" <? checked(is_campaign_public()) ?>><label for="is_public"> public</label>
    <span class="sep">|</span>
    <input id="is_featured" type="checkbox" name="is_featured" value="1" <? checked(has_tag('featured')) ?>><label for="is_featured"> featured</label>
    <input type="submit" class="button gray-button small-button right" value="save changes"/ />
  </form>
<? } ?>

<div style="clear:both;"><? do_action('campaign_bottom', $campaign); ?></div>

<script type="text/javascript">
var vars_main = 'main&uid=<?=$bp->displayed_user->id?>&id=<?=$id?>';
var vars = 'id=<?=$id?>';
var max_media_count = <?=CGW_MAX_MEDIA?>;

$(function() {
  var Months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  $(".read-now").live('click', function(ev) {
    $("html,body").animate({
      scrollTop: $('section.posts').offset().top - 40
    }, 1500);
    return false;
  });

  $(".stories a[title!=''], .stats .meter, .see-more-gifts .butt, .item-thumb, .item-buttons a img").addTip();

  // Media gallery
  var scr = $(".stories .right").scrollable({
    circular: true,
    next: '.next-story',
    prev: '.prev-story'
  });
  var stories = scr.data("scrollable");
  $(".stories .left a").live('click', function() {
    var i = $(this).index();
    var dist = Math.abs(i - stories.getIndex());
    stories.seekTo(i, dist * 200 + (dist < 3 ? 300 : 0));
    // start animating? moveit($(".stories .impact:eq(" + i + ") .impact-photo"));
    return false;
  });

  var twitter_user = null;
  var twitter_url = <?= json_encode($norm_url) ?>;
  var twitter_term = twitter_url;
  var nextMsg = null;

  function process_timeline(data) {
    process_twitter(data, true);
  }
  function process_twitter(data, filter) {
    try {
    var dh = $('.donation-history');

    if (data == null)
      return;
    if (data.results)
      data = data.results;
    var msgs = data; //.results;
    for (var i = 0; i < msgs.length; i++) {
      if (filter == true &&
          msgs[i].text.toLowerCase().indexOf(twitter_term) == -1 &&
          msgs[i].text.toLowerCase().indexOf(twitter_url) == -1)
        continue;
      msgs[i].text = replace_links(msgs[i].text);

      var s = msgs[i].created_at;
      var date = new Date(
        s.replace(/^\w+ (\w+) (\d+) ([\d:]+) \+0000 (\d+)$/,
        "$1 $2 $4 $3 UTC"));
      msgs[i].date = Months[date.getMonth()] + " " + date.getDate();
      var h = date.getHours();
      if (h < 10) h = "0"+h;
      var dateSort = "msg-" + date.toYMD() + "-" + h;
      msgs[i].dateSort = dateSort;

      if (msgs[i].user) {
        msgs[i].profile_image_url = msgs[i].user.profile_image_url;
        msgs[i].from_user = msgs[i].user.screen_name;
        msgs[i].real_name = msgs[i].user.name;
      } else {
        msgs[i].real_name = msgs[i].from_user;
      }
      var msg = $("#twitter_msg").render(msgs[i]);

      // Move this to an insert-activity function
      merge_activity(dh, dateSort, msg);
    }
    } catch (err) {}

    $('.progress-widget').trigger('rszpw');
  }
  function merge_activity(dh, dateSort, msg) {
    try {
    if (nextMsg == null)
      nextMsg = dh.find('tr:first');
    while (nextMsg.length > 0) {
      if (nextMsg.attr('id') < dateSort)
        break;
      nextMsg = nextMsg.next();
    }
    if (nextMsg.length == 0)
      msg.appendTo(dh);
    else
      msg.insertBefore(nextMsg);
    nextMsg = msg.slideDown();

    var dd = msg.attr('id').substring(0,14);
    if ((msg.prev().attr('id') || '').substring(0,14) == dd)
      msg.find(".date").html('');
    if ((msg.next().attr('id') || '').substring(0,14) == dd)
      msg.next().find(".date").html('');
    } catch (err) {}
  }

  // Merge in Twitter results
  $(".progress-widget").each(function() {

    var url = "http://search.twitter.com/search.json?callback=?";
    $.getJSON(url, {
      include_rts: 0,
      q: twitter_url
    }, process_twitter);

    // When enabled, TODO: make sure there are no duplicate entries; handle t.co shortener
    if (twitter_user != null) {
      url = "http://api.twitter.com/1/statuses/user_timeline.json?callback=?";
      $.getJSON(url, {
        screen_name: twitter_user,
        page: 1,
        include_rts: 0,
        count: 60
      }, process_timeline);
    }

  });

  function replace_links(text) {
    var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    return text.replace(exp,"<a href='$1' target='_new'>$1</a>");
  }

});
</script>
<script type="text/html" id="twitter_msg">
  <tr id="${dateSort}" class="twitter-message">
    <td class="date">${date}</td>
    <td>
      <a href="http://twitter.com/${from_user}" target="_new" class="avatar-link">
        <div class="user-tag"><div>@${from_user} <u>view on Twitter</u></div></div>
        <img src="${profile_image_url}" class="avatar" width="50" height="50" />
      </a> 
      <div class="info">
        <span class="from">${real_name}</span> said:
        <span class="message">${text}</span>
      </div>
    </td>
  </tr>
</script>
<?
get_footer();

function draw_campaign_appeal($campaign) {
  global $more, $event_id, $current_user, $event_theme;
  get_currentuserinfo();
  $more = 0;

  global $post;
  setup_postdata($post);

?>
<? draw_sharing_vertical(); ?>
<section id="appeal" class="appeal based">
  <? do_action('before_campaign_appeal', $campaign); ?>
  <? draw_pledge_info($event_id); ?>
  <div class="appeal-container">
  <div id="appeal-image" class="appeal-image">
    <? draw_campaign_photo($campaign); ?>
  </div>
  <div class="appeal-msg msg">
    <? do_action('before_campaign_appeal_message', $campaign); ?>
    <div id="appeal1"></div>
    <div id="appeal2"></div>
    <div id="appeal3"></div>
    <div id="appeal4"></div>
    <div id="appeal5"></div>
    <? do_action('draw_campaign_title', $campaign); ?>
    <? do_action('before_campaign_appeal_content', $campaign); ?>
    <? the_content('<u>read more</u> &raquo;'); ?>
    <? draw_sharing_horizontal(); ?>
    <? do_action('after_campaign_appeal_message', $campaign); ?>
    <? if ($event_theme == 'scoutreach') { draw_promo_c2('scoutreach-team'); } ?>
  </div>
  <div class="campaign-sidebar">
    <? do_action('draw_campaign_sidebar', $campaign); ?>
  </div>
  </div>
  <? do_action('after_campaign_appeal', $campaign); ?>
</section>
<?
}

function draw_campaign_title() {
  the_title('<h1>', '</h1>');
}

function campaign_title($title, $sep = ' - ') {
  global $event_id;
  
  return get_campaign_title($event_id) . $sep;
}

function draw_campaign_content() {
  global $TEMPLATE;

  ?><div class="campaign-content"><?

  if (is_showing($TEMPLATE->fundraisers, 'gifts', TRUE))
    syi_give_section();
  if (is_showing($TEMPLATE->fundraisers, 'stories', TRUE))
    syi_stories_section();
  if (is_showing($TEMPLATE->fundraisers, 'comments', TRUE))
    syi_social_section();
  ?></div><?
}

function draw_campaign_stats($campaign = NULL) {
  global $TEMPLATE;

  if (is_showing($TEMPLATE->fundraisers, 'progress', TRUE)) {
    ?><div id="stats-bar"><?
    syi_stat_section();
    ?></div><?
  }
}

function unslash($s) {
  if (substr($s, -1, 1) == '/')
    return substr($s, 0, -1);
  return $s;
}

function draw_latest_update($campaign) {
  $updates = as_ints($campaign->updates);
  if (count($updates) == 0)
    return;

  $url = $campaign->guid;

  $update = get_post($updates[0]);

  $attachment = get_post_meta($update->ID, 'video', TRUE);
  $update->post_content = "$attachment\n$update->post_content";

  ?>
  <div class="fr-latest">
  <h3 class="fr-latest-title"><a href="<?=$url?>/updates"><?= xml_entities($update->post_title) ?> (sent <?= date('F j', strtotime($update->post_date))?>)</a></h3>
  <div class="fr-update-body fr-latest-body">
    <?= apply_filters('the_content', $update->post_content) ?>
  </div>
  <?
  if (count($updates) > 1) {
    ?><a href="<?=$url?>/updates" class="fr-latest-more"><u class="link">read more updates</u> &raquo;</a><?
  }
  ?></div><?
}
