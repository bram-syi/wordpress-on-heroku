<?php
/*
Plugin Name: IWG Hide Dashboard
Plugin URI: http://www.im-web-gefunden.de/wordpress-plugins/iwg-hide-dashboard/
Description: "Hide Dashboard" hides the dashboard for all users with the capability "hide_dashboard". You need also the "<a href="http://www.im-web-gefunden.de/wordpress-plugins/role-manager/">Role Manager Plugin</a>" for this Plugin.
Author: Thomas Schneider
Author URI: http://www.im-web-gefunden.de/
Version: 1.0.3
License: MIT License - http://www.opensource.org/licenses/mit-license.php
*/

function iwg_hide_dashboard() {
	global $menu, $parent_file;

	if ( current_user_can('hide_dashboard') ) {
		unset($menu[0]);
		if ($parent_file == 'index.php') {
			if ( !headers_sent() ) {
				wp_redirect('profile.php');
				exit();
			} else {
				$iwg_hide_dest_url = get_option('siteurl') . "/wp-admin/profile.php";
				?>
				<meta http-equiv="Refresh" content="0; URL=<?php echo $iwg_hide_dest_url; ?>">
				<script type="text/javascript">
				<!--
					document.location.href = "<?php echo $iwg_hide_dest_url; ?>"
				//-->
				</script>
				</head>
				<body>
				Sorry. Please use this <a href="<?php echo $iwg_hide_dest_url; ?>" title="Your Profile">link</a>.
				</body>
				</html>
				<?php
				exit();
			}
		}
	}
}


add_action('admin_head','iwg_hide_dashboard', 0);
?>