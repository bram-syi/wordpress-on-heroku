<?php
/*
Plugin Name: Champion referral tracking 
Description: Identify the chamption who sent each donating user
Author: Steve Eisner  (but based on "Affiliate Plus" plugin)
Version: 1.0
*/

function capture_referrals()
{
        if(isset($_GET['champ'])) {
                $champ = $_GET['champ'];

		// check if cookie already exists
		if(isset($_COOKIE['referral']))
		  return;

		$exp = time()+60*60*24*30; /* 30 days */
                $wp_root = get_option('home');
                $htp            = "http://";
                $htps           = "https://";
                $domain = str_replace($htp, ".", $wp_root);
                $domain = str_replace($htps, ".", $domain);
                $domain = explode("/",$domain);
                // set cookie
                setcookie('referral', $champ, $exp, '/', $domain[0]);
        }
}

add_action("init", "capture_referrals");
?>
