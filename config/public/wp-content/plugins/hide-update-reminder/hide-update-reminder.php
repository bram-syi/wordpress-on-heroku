<?php
/*
Plugin Name: Hide Update Reminder
Plugin URI: http://www.stuffbysarah.net/wordpress-plugins/remove-update-reminder/
Description: Allows you to remove the upgrade Nag screen from view for Editors and below
Author: Sarah Anderson
Version: 1.2.1
Author URI: http://www.stuffbysarah.net/

Thanks to Viper007Bond for the code hints
*/

class HideUpdateReminder {
	function __construct() {
		add_action('admin_init', array(&$this, 'check_user'));
	}

	function check_user() {
		global $userdata;
		if (!current_user_can('update_plugins'))
			remove_action('admin_notices', 'update_nag', 3);
	}
}

$hideupdaterem = new HideUpdateReminder();