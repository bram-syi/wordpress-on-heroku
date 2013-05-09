<?php
require_once( dirname(__FILE__) . '/../wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/ms.php' );
global $wpdb,$preview;

ensure_logged_in_admin();

$preview = isset($_REQUEST['preview']);

function doit($sql) {
  global $wpdb,$preview;

  if (!$preview) {
    $wpdb->query($sql);
    $sql = "ran>> $sql"; 
  } else 
    $sql = "will $sql";
  echo "<div>$sql;</div>";
}

if ($_POST) {
  $ids = $_REQUEST['donorIDs'];
  if (count($ids) == 0) {
    die('no donations selected');
  }

  $opt = array();
  $opt[] = "validated=1";

  $user_id = 0;
  $old_ids = implode(',', array_map('absint', $ids));

  $user_ids = array_filter($wpdb->get_col("select distinct user_id from donationGiver where ID in ($old_ids)", 0));
  if (count($user_ids) > 1) {
    $metas = array();
    $uids = implode(',', array_map('absint', $user_ids));
     
    // Confirm merge usermeta
    echo "<form action=\"\" method=\"POST\">";
    // re-pass the same POST variables
    foreach ($_REQUEST as $k=>$v) {
      if (is_array($v))
        foreach ($v as $v2) {
          echo "<input type=\"hidden\" name=\"" . esc_attr($k) . "[]\" value=\"" . esc_attr($v2) . "\">";
        }
      else 
        echo "<input type=\"hidden\" name=\"" . esc_attr($k) . "\" value=\"" . esc_attr($v) . "\">";
    }

    $md = $wpdb->get_results($sql = "select distinct meta_key, meta_value from wp_usermeta where user_id in ($uids)");
    foreach ($md as $m) {
      $k = $m->meta_key;
      if (isset($metas[$k])) {
        $metas[$k] = array($metas[$k]);
        $metas[$k][] = $m->meta_value;
      } else
        $metas[$k] = $m->meta_value;
    }

    $merge = FALSE;
    foreach ($metas as $k=>$v) {
      if (!is_array($v))
        continue;

      $v = array_filter($v);
      if (count($v) <= 1) {
        $metas[$k] = $v[0];
        continue;
      }

      if (!empty($_REQUEST["__$k"])) {
        $v = stripslashes($_REQUEST["__$k"]);
        echo "$k: chose $v<br>";
        $metas[$k] = $v;
        continue;
      }

      $merge = TRUE;
      echo "$k: ";
      $k = esc_attr($k);
      $i = 0;
      foreach ($v as $x) {
        $i++;
        echo " <input type=\"radio\" name=\"__$k\" value=\"" . htmlspecialchars($x) . "\" id=\"__$k$i\"";
        if ($i == 1)
          echo " checked";
        echo "><label for=\"__$k$i\">" . esc_html($x) . "</label> ";
      }
      echo "<br>";
    }

    if ($merge == TRUE)
      echo '<input type="submit" value="Merge!">';
    echo '</form>';
    if ($merge == TRUE)
      die;

    $user_id = $user_ids[0];
    echo "new user = #$user_id<br>";

    if ($preview) {
      foreach ($metas as $k=>$v) {
        $s = unserialize($v);
        if ($s !== FALSE) {
          $v = $s;
          echo "will update $k: " . serialize($v) . "<br>";
        } else
          echo "will update $k: $v<br>";
      }
      for ($i = 1; $i < count($user_ids); $i++) {
        $u = $user_ids[$i];
        echo "will delete user #$u<br>";
      }
    } else {
      // Actually do the merge:
      foreach ($metas as $k=>$v) {
        $s = unserialize($v);
        if ($s !== FALSE)
          $v = $s;
        update_user_meta($user_id, $k, $v);
      }
      // Delete the rest of the users
      for ($i = 1; $i < count($user_ids); $i++) {
        $u = $user_ids[$i];
        echo "deleting user #$u<br>";
        wpmu_delete_user($u, $user_id);
      }
    }
  } else if (count($user_ids) == 1) {
    $user_id = $user_ids[0];
  }

  $referrers = array_filter($wpdb->get_col("select distinct referrer from donationGiver where ID in ($old_ids)", 0));
  if (count($referrers) > 1) {
    die("this will lose a referrer (".implode(',', $referrers).")");
  } else if (count($referrers) == 1) {
    $opt[] = $wpdb->prepare("referrer=%s", $referrers[0]);
  }
 
  $new_donor_id = absint(array_shift($ids));
  if ($new_donor_id == 0) {
    die('no donors specified');
  }
  $email = trim($_REQUEST['email']);
  if (!empty($email)) {
    $opt[] = $wpdb->prepare("email=%s", $email);
  }
  $first = trim($_REQUEST['first']);
  if (!empty($first)) {
    $opt[] = $wpdb->prepare("firstName=%s", $first);
  }
  $last = trim($_REQUEST['last']);
  if (!empty($last)) {
    $opt[] = $wpdb->prepare("lastName=%s", $last);
  }

  if ($user_id == 0 && !empty($first) && !empty($last) && !empty($email)) {
    list($username, $user_id) = createWpAccount($email, $first, $last);
    if ($user_id == 0)
      $user_id = email_exists($email);
  } else if ($user_id == 0 && !empty($email)) {
    $user_id = email_exists($email);
  }
  if ($user_id > 0) {
    $opt[] = "user_id = $user_id";

    $new_user = array('ID' => $user_id);

    if (!empty($first))
      $new_user['first_name'] = $first;
    if (!empty($last))
      $new_user['last_name'] = $last;
    if (!empty($email))
      $new_user['user_email'] = $email;

    $user = get_userdata($user_id);
    $username = $user->user_login;

    if (get_user_meta($user_id, 'full_name', true) == true)
      $new_user['display_name'] = "$user->first_name $user->last_name";
    else
      $new_user['display_name'] = "$user->first_name";

    if (!$preview) {
      wp_update_user($new_user);
      if ($_REQUEST['nomail'] == true) {
        update_user_meta($user_id, 'no_thanks_email', true);
      }
    }
  }
  $opt[] = "main=1";
  $opt = implode(',', $opt);

  $old_ids = implode(',', array_map('absint', $ids));

  doit( "update donationGiver set $opt where ID=$new_donor_id" );
  doit( "update donationGiver set user_id=$user_id,main=0 where ID in ($old_ids)" );
  if (count($ids) > 0) {
    doit( $wpdb->prepare("update donation set donorID=%d where donorID in ($old_ids)", $new_donor_id) );
    doit( $wpdb->prepare("update donationStory set donor_id=%d where donor_id in ($old_ids)", $new_donor_id) );
    doit( $wpdb->prepare("update donationAcct set donorID=%d where donorID in ($old_ids)", $new_donor_id) );
    doit( $wpdb->prepare("update notificationHistory set donorID=%d where donorID in ($old_ids)", $new_donor_id) );
    //doit( "delete from donationGiver where ID in ($old_ids)" );
  }
  ?><div><?
  if (!empty($username)) {
    ?><a href="/members/<?=$username?>" target="_profile">profile</a> <?
    ?><a href="/members/<?=$username?>/profile/settings" target="_profile">settings</a> <?
  }
  ?> <a href="fix_donor.php">next</a> <?
  ?></div>

<form method="get" action="fix_donor.php">
Donor name: <input name="first" /><input name="last" /> or Donor ID:<input name="did" /> <input type="submit" value="go" />
</form>

<?

} else {
  $first = trim($_REQUEST['first']);
  $last = trim($_REQUEST['last']);
  $did = trim($_REQUEST['did']);
  $uid = trim($_REQUEST['uid']);

  if (empty($first) && empty($last) && empty($did) && empty($uid)) { ?>
<form method="get" action="fix_donor.php">
Donor name: <input name="first" /><input name="last" /><input type="submit" value="go" />
</form>
<? } else { 
?><form method="post" action="fix_donor.php"><?
  $wh = array();
  if (!empty($did))
    $wh[] = $wpdb->prepare("ID=%d", $did);
  else if (!empty($uid))
    $wh[] = $wpdb->prepare("user_id=%d", $uid);
  else {
    if (!empty($first))
      $wh[] = $wpdb->prepare("firstName like %s", str_replace('*', '%', $first));
    if (!empty($last))
      $wh[] = $wpdb->prepare("lastName like %s", str_replace('*', '%', $last));
  }
  $wh = implode(' AND ', $wh);

  $sql = "select ID,email,firstName,lastName,verified,user_id from donationGiver WHERE $wh";
echo $sql;
  $donors = $wpdb->get_results($sql);
  $rows = array();
  $c = count($donors);
  foreach ($donors as $donor) {
    $report_url = get_site_url(1, "/database/donation_reports.php?donor=$donor->ID");
1320
    ?><div><input type="checkbox" checked="checked" name="donorIDs[]" value="<?=$donor->ID?>" /> <?= $donor->email ?> [<a target="_new" href="<?=$report_url?>" style="text-decoration: none; color:#44a;"><?=$donor->ID ?></a>] <?=$donor->firstName ?> <?=$donor->lastName?><? 
 if ($donor->user_id > 0) { 
   $user = get_userdata($donor->user_id);
   if ($c == 1) {
     $first = $donor->firstName;
     $last = $donor->lastName;
     $email = $donor->email;
   }
   echo " #$donor->user_id: "; 
   echo '<a href="' . bp_core_get_user_domain($donor->user_id) . '" target="_new">' . $user->user_login . '</a>';
   if (get_user_meta($donor->user_id, 'no_thanks_email', true) == true) echo " (no-thanks)";
   if (get_user_meta($donor->user_id, 'no_story_email', true) == true) echo " (no-story)";
}
?></div><?
    $sql = $wpdb->prepare("select * from donation where donorID=%d", $donor->ID);
    $donations = $wpdb->get_results($sql);
    foreach ($donations as $donation) {
      ?><div style="margin-left:20px;"><?
      $date = explode(' ', $donation->donationDate);
      echo '<span style="font-size: 0.8em; color:#888;"> [' . $donation->donationID . "]</span> ";
      echo $date[0];
      echo ': ';
      $sql = $wpdb->prepare("select dg.id,g.displayName,CONCAT(b.domain,b.path) AS blog,dg.story from donationGifts dg join gift g on dg.giftID=g.id join wp_blogs b on b.blog_id=g.blog_id where donationID=%d", $donation->donationID);
      $items = $wpdb->get_results($sql);
      if (count($items) == 0) {
        if ($donation->donationAmount_Total == 0)
          echo "(voided)";
        else 
          echo "$$donation->donationAmount_Total GC";
      }
      foreach ($items as $item) {
        if ($item->story > 0) {
          ?><a href="http://<?= $item->blog ?>publish/?ID=<?= $item->story ?>"><?= $item->displayName ?></a><?
        } else {
          echo $item->displayName;
        }
        echo '<span style="font-size: 0.8em; color:#888;">[' . $item->id . "]</span> ";
      }
      ?></div><?
    }
  }
?>
<input type="submit" value="Combine" />
email: <input name="email" value="<?=$email?>" /> name: <input name="first" value="<?=$first?>" /> <input name="last" value="<?=$last?>" />
<input id="preview" type="checkbox" name="preview" checked="checked" /><label for="preview"> preview</label>
<input id="nomail" type="checkbox" name="nomail" /><label for="nomail"> no mail</label>
</form>
<? }
}
?>
