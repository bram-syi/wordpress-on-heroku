<?

include_once('../wp-load.php');
include_once('../wp-admin/includes/taxonomy.php');
include_once('../wp-includes/wp-db.php');
include_once('../wp-includes/registration.php');
include_once('../wp-admin/includes/user.php');
include_once('../wp-includes/syi/syi-includes.php');
include_once('../database/db-functions.php');

global $current_user;

get_currentuserinfo();

ensure_logged_in_admin();

//merge donationGiver

global $wpdb;

if(isset($_REQUEST['merge_donors'])) {


}

if(isset($_REQUEST['merge_users'])) {


}

echo '<form type="post">';

//Affected tables:

//ignored:
//donationContact -- its empty
//donor because -- its empty
//donationStory - donor_id -- not used
//donorUsername
//gift_Donations -- donorID only 2 entries 2008

//need update:
//cart - userID

//cartItemDetails - recipientID (donor??)
//donation - donorID
//donationAcct - donorId
//notificationHistory - donorID

//???
//donationGiver - donationOwner(donor??)

if(!isset($_REQUEST['view_users'])) {

////////////////////////////////////////////////////////////////////////////////
//merge donors only

$dgs = $wpdb->get_results("SELECT * FROM donationGiver ORDER BY ID");

$id_name = array();
$id_email = array();
$id_user = array();

echo '<pre>';
  $echo2.= 'USER-DONOR CAN ONLY BE MERGED IF USER, NAME, EMAIL ARE MATCHED'."\n";

foreach($dgs as $dg) {

//donation - donorID
//donationAcct - donorId
//notificationHistory - donorID
//cartItemDetails - recipientID (donor??)


  $echo = '';
  $echo2 = '';
  //echo '<pre>'.print_r($dg,true).'</pre>';

  //fix the names to the right format first
  $dg->email=trim(strtolower($dg->email));
  $dg->firstName=fix_name2($dg->firstName);
  $dg->lastName=fix_name2($dg->lastName);

  //lookup name from previous donors
  $name_found = array_search($dg->firstName.' '.$dg->lastName,$id_name);
  if($name_found===FALSE){$id_name[$dg->ID] = $dg->firstName.' '.$dg->lastName;}

  //lookup email from previous donors
  $email_found = array_search($dg->email,$id_email);
  if($email_found===FALSE){$id_email[$dg->ID] = $dg->email;}

  $primary = false;
  $user_id = 0;
  $user_matching = '';

  if(intval($dg->user_id) > 0){//get user id straight from donor
    $user_id = $dg->user_id;
    $user_matching = 'user';
  } else if(email_exists($dg->email)) {//lookup using email
    $user_id = email_exists($dg->email);
    $user_matching = 'email';
  } else if(get_user_by_name($dg->firstName,$dg->lastName)){//look up using name
    $user_id = get_user_by_name($dg->firstName,$dg->lastName);
    $user_matching = 'name';
  } else if($name_found!==false && $name_found==$email_found && isset($id_user[$name_found])) {
    $user_id = $id_user[$name_found];
    $user_matching = 'dg';
  }

  //lookup user from prev donors
  if(intval($user_id)>0) {
    $user_found = array_search($user_id,$id_user);
    if($user_found===FALSE) {
      $id_user[$dg->ID] = $user_id;
      $primary = true;
    } else { //user already exists
      $primary = false;
    }
  } else {
    $user_found = false;
  }

  if($email_found===false && $name_found===false && $user_found===false){
    $primary = true;
  }

////////////////////////////////////////////////////////////////////////////////
  $email_parts = explode("@",$dg->email);

  $echo.= '-- ';

  $num_d = $wpdb->get_var($wpdb->prepare(
  "SELECT COUNT(*) FROM donation WHERE donorID = %d",$dg->ID));
  $num_da = $wpdb->get_var($wpdb->prepare(
  "SELECT COUNT(*) FROM donationAcct WHERE donorId = %d",$dg->ID));
  $num_nh = $wpdb->get_var($wpdb->prepare(
  "SELECT COUNT(*) FROM notificationHistory WHERE donorID = %d",$dg->ID));
  $num_cid = $wpdb->get_var($wpdb->prepare(
  "SELECT COUNT(*) FROM cartItemDetails WHERE recipientID = %d",$dg->ID));

////////////////////////////////////////////////////////////////////////////////

  $echo2.= 'donor #'.space_filler($dg->ID,5).' ';
  
  if(intval($user_id) > 0) {

    $echo2.=($user_matching=='email'?
    'same email w   ':($user_matching=='name'?
    'same name w    ':
    ($user_matching=='dg'?
    'same e+n w d w ':
    'belongs to     ')
    ));
    
    $echo2.='user #'.space_filler($user_id,4);
  } else {
    $echo2.=
    '                         ';
  }
  $echo2.=($user_found!==FALSE?
    'main donor #'.space_filler($user_found,4):
    ($primary?
    '----------------':
    '                '));
  $echo2.=" ".space_filler($dg->email,50).' '.
  ($email_found!==FALSE?'same email w d #'.space_filler($email_found,4):
                        
  ($name_found!==FALSE && $user_found!==FALSE && $name_found == $user_found?
                        '---odd email only---':
                        "                    ")
                        );
  $echo2.=" ".space_filler($dg->firstName,18);
  $echo2.=" ".space_filler($dg->lastName,18).' '.
  ($name_found!==FALSE? 'same name w d  #'.space_filler($name_found,4):
  ($email_found!==FALSE && $user_found!==FALSE && $email_found == $user_found?
                        '---odd name only ---':
                        "                    ")

                        );
  $echo2.="\n";
  
////////////////////////////////////////////////////////////////////////////////

  $echo.= 'd#'.space_filler($dg->ID,5).' ';

  $echo.= space_filler($num_d,2).' '.space_filler($num_da,2).' '.
  space_filler($num_nh,2).' '.space_filler($num_cid,2).' ';

  if(intval($user_id) > 0) {
    $echo.=($user_matching=='email'?'e':($user_matching=='name'?'n':
    ($user_matching=='dg'?'x':'u')
    ));
    $echo.='#'.space_filler($user_id,4);
  } else {
    $echo.='      ';
  }

  $echo.=($user_found!==FALSE?'*'.space_filler($user_found,4):
    ($primary?'main ':'     '));

  $echo.="\t".space_filler($email_parts[0],30).' '.($email_found!==FALSE?'*'.space_filler($email_found,4):"\t");
  $echo.="\t".space_filler($dg->firstName,15);
  $echo.="\t".space_filler($dg->lastName,15).' '.($name_found!==FALSE?'*'.space_filler($name_found,4):"\t");
  $echo.="\t".$dg->referrer;
  $echo.="\n";

if($num_d>0 || $num_da>0 || $num_nh>0 || $num_cid>0) {

  if(!isset($_REQUEST['notmain']) || !$primary)

  if(!isset($_REQUEST['update']) && !isset($_REQUEST['create'])) {
    if(isset($_REQUEST['v']))
      echo $echo2;
    else
      echo $echo; }

}

  if(

(
    ($user_found!==false) &&
    ($user_found===$name_found) &&
    ($name_found===$email_found)
) 

  ) {
  

//donation - donorID
//donationAcct - donorId
//notificationHistory - donorID
//cartItemDetails - recipientID (donor??)


    if(isset($_REQUEST['update'])) {

if($num_d>0 || $num_da>0 || $num_nh>0 || $num_cid>0) {
//    echo "\n".'-- ------------------------------MATCHING USER, NAME AND EMAIL-------------------'."\n";
    echo $wpdb->prepare("UPDATE donationGiver dg1, donationGiver dg2
    SET dg1.referrer=IF(dg2.referrer='' OR dg2.referrer=0 OR dg1.referrer=dg2.referrer,dg1.referrer,IF(dg1.referrer='' OR dg1.referrer=0,dg2.referrer,CONCAT(dg1.referrer,',',dg2.referrer)))
    WHERE dg1.ID=%d AND dg2.ID=%d;",$user_found,$dg->ID)."\n";
    echo $wpdb->prepare('UPDATE donation SET donorID=%d WHERE donorID=%d;',$user_found,$dg->ID)."\n";
    echo $wpdb->prepare('UPDATE donationAcct SET donorId=%d WHERE donorId=%d;',$user_found,$dg->ID)."\n";
    echo $wpdb->prepare('UPDATE notificationHistory SET donorID=%d WHERE donorID=%d;',$user_found,$dg->ID)."\n";
    echo $wpdb->prepare('UPDATE cartItemDetails SET recipientID=%d WHERE recipientID=%d;',$user_found,$dg->ID)."\n";
//    echo "-- ------------------------------------------------------------------------------\n\n";
}


    }

  } else if ($primary && intval($user_id)==0) { //primary without user

    if(isset($_REQUEST['create'])) {

    if(strpos($dg->email,'wall+')!==FALSE) {
      $username = str_replace('wall+','',$email_parts[0]);
    } else {
      $username = strtolower($dg->firstName);
    }

    $username = str_replace(array('.comcast.net','.'),'',$username);

    //echo "\n".'// ------------------------------LONE DONOR NO MATCH FOUND-----------------------'."\n";
    echo $wpdb->prepare('createWpAccount(%s, %s, %s, %s, %s,true,false);',
      $dg->email,$dg->firstName,$dg->lastName,$username,'')."\n";
    //echo "// ------------------------------------------------------------------------------\n\n";


    }

  } else if($user_id>0) {

if($primary && $dg->main==0) {
    if(isset($_REQUEST['update'])) {
      echo $wpdb->prepare("UPDATE donationGiver dg SET main=1 WHERE dg.ID=%d;",$dg->ID)."\n";
    }
}

    if(isset($_REQUEST['update'])) {

    if($user_matching=='user') {
//      echo'-- USER ID EXISTS'."\n";
    } else if($user_matching=='email') {
      //echo'-- USER MATCHED BY EMAIL'."\n";
    echo $wpdb->prepare("UPDATE donationGiver dg SET user_id=%d WHERE (user_id=0 OR user_id IS NULL) AND dg.ID=%d;",$user_id,$dg->ID)."\n";
    } else if($user_matching=='name') {
      //echo'-- USER MATCHED BY NAME'."\n";
    echo $wpdb->prepare("UPDATE donationGiver dg SET user_id=%d WHERE (user_id=0 OR user_id IS NULL) AND dg.ID=%d;",$user_id,$dg->ID)."\n";
    }

    }
  }

}//end foreach

echo '</pre>';

} else {

////////////////////////////////////////////////////////////////////////////////

}

echo '</form>';


function get_user_by_name($first,$last) {
  global $wpdb;

  if(empty($first) || empty($last)) return 0;

  $user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM wp_users wu
    LEFT JOIN wp_usermeta wum1 ON wum1.user_id = wu.ID
    LEFT JOIN wp_usermeta wum2 ON wum2.user_id = wu.ID
    WHERE wum1.meta_key = 'first_name'
    AND wum1.meta_value = %s
    AND wum2.meta_key = 'last_name'
    AND wum2.meta_value = %s
    ",$first,$last));
    
  if($user_id == NULL) return 0;
  else return $user_id;
}

function fix_name2($name) {
  $name=stripslashes($name);
  $name=str_replace(array("\"","'"),"",$name);
  $name=trim($name);
  if (ctype_upper($name) || ctype_lower($name)) {
    return ucwords(strtolower($name));
  } else {
    return $name;
  }
}

function space_filler($word,$length) {
  return (strlen($word)<=$length?
    $word.str_repeat(' ',$length-strlen($word))
    :substr($word,0,$length).'');
}

function search_donor_donor($first,$last,$email) {


}

function search_donor_user($first,$last,$email) {
  global $wpdb;

  $ids = array();
  $ids_by_name = array();

  $users_by_email = $wpdb->get_results($wpdb->prepare("
  SELECT * FROM wp_users wu
  WHERE wu.user_email=%s
  AND wu.spam=0 AND wu.deleted=0
  ORDER BY wu.ID",$email));


  foreach ($users_by_email as $user) {
    $ids[] = $user->ID;
    $email_parts = explode("@",$user->user_email);

    echo "\t\t".'user #'.$user->ID.' e:'.$email_parts[0];
    echo "\n";
  }


  $users_by_name = $wpdb->get_results($wpdb->prepare(
  "SELECT *, wum_f.meta_value first, wum_l.meta_value last
  FROM wp_users wu
  LEFT JOIN wp_usermeta wum_f ON
  (wum.user_id = wu.ID AND wum.meta_key = 'first_name' AND LOWER(wum.meta_value)=LOWER(%s))
  LEFT JOIN wp_usermeta wum_l ON
  (wum.user_id = wu.ID AND wum.meta_key = 'last_name' AND LOWER(wum.meta_value)=LOWER(%s))
  AND wu.ID NOT IN (".implode(",",$ids).")
  ",$first,$last));

//  if(is_array($users_by_name))
  foreach ($users_by_name as $user) {
    $ids[] = $user->ID;
    echo "-- \t\t".'user #'.$user->ID."\t f:".$user->first."\t l:".$user->last;
    echo "\n";
  }

  if(!empty($ids)) echo "\n";
}


?>
