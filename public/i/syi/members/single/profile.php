<?

global $bp;
$act = bp_current_action();

if ($bp->current_component == 'settings' && $bp->current_action == 'general')
  $act = "settings";
else {
  sharing_init(TRUE);
  if (empty($act) || $act == 'public')
    $act = "profile-loop";
}

locate_template( array( "members/single/profile/$act.php" ), true );
