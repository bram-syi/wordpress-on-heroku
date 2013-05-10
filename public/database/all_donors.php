<?
include_once('../wp-load.php');

ensure_logged_in_admin();

$start = intval($_GET['start']);
$length = intval($_GET['count']);
if (empty($length))
  $length = 40;
$next_url = add_query_arg('start', $start + $length);
$info_url = add_query_arg('donor', $_REQUEST['donor'], '/reports.php?report=donorinfo');
$auto_advance = $_GET['auto'];
if ($auto_advance != 0)
  $auto_advance = TRUE;

$actions = $_GET['action'];
if (empty($actions))
  $actions = 'update';
else if ($actions == 'all')
  $actions = 'stories,fb,location,total,tip';

$donors = as_ints($_REQUEST['donor']);
if (count($donors) > 0) 
  $where = "where dg.ID in (" . implode(',', $donors) . ")";

global $wpdb;

$rows = $wpdb->get_results($sql=
  "select dg.*,u.user_login
   from donationGiver dg
   left join wp_users u on dg.user_id=u.id
   $where
   group by dg.user_id
   order by dg.ID
   limit $start,$length");

if (count($rows) == 0) {
  wp_redirect($info_url);
  die();
}
?>
<html><head>
<title>Updating donor info</title>
<? if ($auto_advance) { ?>
  <meta http-equiv="refresh" content="1;url=<?=$next_url?>" /> 
<? } ?>
</head><body style="background:white;"><pre>
<?
if (empty($actions) || $actions == 'update') {
?>Available actions:
  <a href="?action=stories">stories</a> = update most recent stories
  <a href="?action=fb">fb</a> = update facebook info
  <a href="?action=location">location</a> = guess location
  <a href="?action=total">total</a> = update tax info
  <a href="?action=tip">tip</a> = update tip rate
  <a href="?action=fixname">fixname</a> = set user first/last name if empty
<?
}

foreach ($rows as $donor) {
  $data = get_donor_info($donor->ID);

  update_donor($donor);
  foreach (as_array($actions) as $action) {
    $r = null;
    switch ($action) {
      case 'stories': $r = get_last_stories($donor); break;
      case 'fb': $r = get_facebook_info($donor); break;
      case "total": 
        $r = sum_donations_by_year($donor, 2011);
        $r += sum_donations_by_year($donor, 2010);
        break;
      case 'location': $r = infer_location($donor); break;
      case 'tip': $r = calculate_tip_rate($donor); break;
      case 'fixname': fixed_name($donor); break;
    }
    if ($r != null)
      $data = $r + $data;
  }

  $data['user_id'] = $donor->user_id;
  update_donor_info($donor->ID, $data);
}
?>
</pre><br><br>
<a href="<?= $next_url ?>">NEXT</a>
<a href="<?= $next_url ?>&auto=true">AUTO</a>
<a href="<?= $info_url ?>">INFO</a>
</body></html>
<?
function update_donor($donor) {
  echo $donor->ID . ': ' . $donor->email . ' (' . $donor->user_login . ") #{$donor->user_id}\r\n";
}

function infer_location($donor) {
  if (!$donor->main)
    return;

  $a = array();

  // Try to fetch from user profile
  $loc = xprofile_get_field_data('Location', $donor->user_id);
  if (empty($loc)) {
    // Try to fetch from Facebook
    $u = get_fb_user($donor->user_id);
    if ($u != NULL) {
      $me = fb_query_user($u->uid, "current_location,sex,birthday_date");
      if (count($me) > 0) {
        $me = $me[0];
        $loc = $me['current_location']['name'];
      }
    }
  }
  if (empty($loc)) {
    // Could continue to infer from payments
  }

  if (!empty($loc))
    $a['i_location'] = $loc;
  return $a;
}

function fixed_name($donor) {
  global $wpdb;

  $row = $wpdb->get_row($sql = $wpdb->prepare("
    select u.ID, um1.meta_value as first, um2.meta_value as last,dg.firstName, dg.lastName
    from wp_users u
    left join wp_usermeta um1 on um1.user_id=u.id and um1.meta_key = 'first_name'
    left join wp_usermeta um2 on um2.user_id=u.id and um2.meta_key = 'last_name'
    left join donationGiver dg on dg.user_id=u.id
    where dg.id=%d
    limit 1", $donor->ID));

  $userdata = array(
    'ID' => $row->ID
  );

  $fix = FALSE;
  if (empty($row->first) && !empty($row->firstName)) {
    $userdata['first_name'] = $row->firstName;
    $fix = TRUE;
  } else {
    $userdata['first_name'] = $row->first;
  }

  if (empty($row->last) && !empty($row->lastName)) {
    $userdata['last_name'] = $row->lastName;
    $fix = TRUE;
  } else {
    $userdata['last_name'] = $row->last;
  }

  if ($fix == TRUE) { 
    if (empty($userdata['first_name']) || empty($userdata['last_name']))
      return NULL;

    $userdata['display_name'] = $userdata['first_name']; // if we wanted to add last: . ' ' . $userdata['last_name'];
    echo "updating $row->firstName $row->lastName\n";
    wp_update_user($userdata);
  }

  return NULL;
}

function calculate_tip_rate($donor) {
  global $wpdb;

  $r = $wpdb->get_row($sql = $wpdb->prepare(
    "SELECT sum(dg.tip) as tip, sum(dg.amount) as amount
    FROM donationGifts dg
    LEFT JOIN donation d ON d.donationID=dg.donationID
    WHERE d.donorID = %d", $donor->ID));

  if (count($r) == 0)
    return null;

  $tip = $r->tip;
  $amount = $r->amount;
  if ($tip == 0 || $amount == 0)
    $rate = 0;
  else
    $rate = ($tip / $amount) * 100;
  $rate = number_format($rate, 1);

  echo "  tip rate: $rate%\r\n";
  return array('tip_rate' => $rate);
}

function get_last_stories($donor) {
  if (!$donor->main)
    return;

  $stories = get_stories_by_user($donor->user_id, 3, "post_modified DESC");
  if (count($stories) == 0)
    return array('storiesHTML' => '');

  $profile_url = get_site_url(1, "/members/{$donor->user_login}");

  $a = array(
    'story_title_1' => '',
    'story_url_1' => '',
    'story_img_1' => '',
    'story_title_2' => '',
    'story_url_2' => '',
    'story_img_2' => '',
    'story_title_3' => '',
    'story_url_3' => '',
    'story_img_3' => ''
  );
  $i = 1;

  ?><div><?
  ob_start();
  foreach ($stories as $story) {
    $a["story_title_$i"] = fixencoding($story->post_title);
    $a["story_url_$i"] = $story->guid;
    $a["story_img_$i"] = get_thumbnailed($story->blog_id, $story->post_image, 135,158);
    ?><a href="<?= $profile_url ?>" target="_new"><?
    draw_thumbnail($story->blog_id, $story->post_image, 135,158, false, $story->post_title, 'img style="background: url(http://seeyourimpact.org/wp-content/themes/syi/images/story-shadow.gif) no-repeat 0 0; margin: 5px 10px; padding: 10px;" border="0"');
    ?></a><?
    $i ++;
  } 
  $a['storiesHTML'] = ob_get_contents();
  ob_end_flush();
  ?></div><?

  return $a;
}

function get_facebook_info($donor) {
  if (!$donor->main)
    return;

  $isFB = false;

  $fb_id = get_user_meta($donor->user_id, 'fb_id');
  if ($fb_id) {
    $isFB = true;
    echo "<img src=\"http://graph.facebook.com/$fb_id/picture?type=large\" />\r\n";
  }

  return array('isFB' => $isFB);
}
