<?php

require_once('../wp-load.php');
require_once('../wp-admin/includes/user.php'); // wp_delete_user

ensure_logged_in_admin();

print '<pre>';

if ($_REQUEST['u']) {
  $user_id = $_REQUEST['u'];
  if (!is_numeric($user_id)) {
    $users = get_users('search='.$user_id);
    if (count($users) != 1) {
      print "didn't find exactly 1 user to match '$user_id', found ".count($users)." instead";
      exit;
    }
    $user_id = $users[0]->id;
  }

  if (wp_delete_user($user_id)) {
    print "user $user_id deleted successfully";
  }
  else {
    print "failed to delete user $user_id";
  }
}
