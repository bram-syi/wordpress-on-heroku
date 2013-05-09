<?

global $wpdb;
global $error_signin;
global $payment_method_pages;

global $header_file;
global $errors;

require_once($ABSPATH . 'wp-load.php');
require_once($ABSPATH . 'database/db-functions.php');
//require_once($ABSPATH . 'wp-admin/includes/file.php');
//require_once($ABSPATH . 'wp-admin/includes/post.php');

if ($_REQUEST['request'] == 'login') {

   $email = $_REQUEST['email'];
   $password = $_REQUEST['password'];

   $user_id = try_login(array(
        "account" => $email,
        "password" => $password,
        "register" => false
        ));

   if ($user_id == null) {
      $result = array("error" => "login failed");
   } else {
      $result = array("id" => $user_id);
   }
   echo json_encode($result);
   die();

//$user = get_current_user();
//$result = get_user_option( "user_nicename");
//$result = get_userdata(1);
}

$user_id = get_current_user_id();

$result = array("user_id" => $user_id, "blogs" => array());

foreach(get_blogs_of_user($user_id) as $blog) {
  if (current_user_can_for_blog($blog -> userblog_id, "edit_posts")) {
    $blog_id = $blog -> userblog_id;
/*    echo ($wpdb->get_results($wpdb->prepare("
	SELECT option_value FROM wp_%d_options
	WHERE option_name='siteurl'", $blog_id)));*/

	$gifts = $wpdb->get_results($wpdb->prepare("
            SELECT * FROM gift
    	    WHERE blog_id=%d AND towards_gift_id=0 AND active=1",
    	    $blog_id));
	
	  $donations = $wpdb->get_results($wpdb->prepare(
    "select g.id,g.displayName,count(*) as qty, g2.unitAmount / g.unitAmount as of
    from donationGifts dg
    left join donation d on d.donationID=dg.donationID
    left join gift g on dg.giftID=g.id
    left join gift g2 on g.towards_gift_id=g2.id
    where dg.blog_id=%d and (dg.story=0 or dg.story is null) and d.test = 0
    group by dg.giftID", $blog_id));
  
	$qty = array();
	
	foreach ($gifts as $gift) {
		foreach ($donations as $d) {
			if ($gift -> id == $d -> id) {
				if (empty($d -> qty)) {
					$gift -> quantity = "0";
				} else {
				//$gift -> quantity = "$d->qty" . (!empty($d->of) ? " of $d->of" : "");
				$gift -> quantity = (!empty($d->of) ? round($d->qty / $d->of, 1) : "$d->qty");
				}
				break;
			}
		}
		if (empty($gift -> quantity))
			$gift -> quantity = "0";
	}
	
    $result["blogs"][] = array("blog_id" => $blog_id, "gifts" => $gifts,
		"blog_url" => $wpdb->get_results($wpdb->prepare("
	    SELECT option_value FROM wp_%d_options
	    WHERE option_name='siteurl'", $blog_id)));
  }
}

echo json_encode($result);
die();

?>
