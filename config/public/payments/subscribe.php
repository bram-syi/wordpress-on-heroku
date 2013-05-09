<?

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

$plan = "";
$acct = explode('/', $_REQUEST['a']);
if (count($acct) > 1) 
  $plan = $acct[1];
$acct = $acct[0];

if (empty($acct)) {
  header("Location: /"); exit();
}

$acct = str_replace(' ','+', $acct); // recurly doesn't urlencode account_code, so a + gets turned into space

if (is_user_logged_in() && preg_match("/event.*/", $plan) == false) {
  get_currentuserinfo();

  $user = get_user_from_monthly_account($acct);
  if (!empty($user) && $user == $current_user->user_login) {
    header("Location: /members/$user/profile/payments/"); 
    exit();
  }
}

function is_mobile_donation($a) {
  $e = explode('-', $a);
  return count($e) == 5;
}

if (is_mobile_donation($acct)) {
  if ($plan == null) {
    header("Location: http://www.foundingdonors.org/Failure.aspx?account=$acct"); exit();
  }
  header("Location: http://www.foundingdonors.org/Success.aspx?account=$acct&plan=$plan"); exit();
}

// TODO: Redirect to thank you page.
$thanks_page = "";
header("Location: /$thanks_page?thanks=$acct");
exit();
?>
