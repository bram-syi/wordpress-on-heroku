<?php
/*
Plugin Name: Toggle Admin Menus
Plugin URI: http://wpmudev.org
Description: WPMU plugin. Go to Site Admin-->Options to "Enable or disable WP Backend Menus". All menus are unchecked and disabled by default, except for SiteAdmin.
Author: D Sader
Version: 2.2
Author URI: http://iblog.stjschool.org

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
Known bugs: if you turn all menus off, where should the last redirect go? I disable the dashboard in my install, and keep the profile. Hence no wp_redirect for profile with all other "dead ends" redirecting to profile. Profile is always url accessible, even though all menus can be disabled. Disabling parent menus for pages added by plugins/templates may work, or not. Happy testing.

Changes
1.0 Release
2.0 Added check current_user_can caps and if(!empty menu/sub) to foreach loops: props Andrea and Ron.
2.1 Bug when all Settings menus are disabled. props Nemo
2.2 Bug when all Import/Export menus are disabled.
*/ 

//------------------------------------------------------------------------//
//---Hooks----------------------------------------------------------------//
//------------------------------------------------------------------------//
add_action( 'plugins_loaded', 'ds_delete_blog_menu_disable'); // I really don't want blog Admin to delete their blog
add_action( 'wpmu_options','ds_menu_option', -99 ); // below "Menus (Enable or disable WP Backend Menus) Plugins"
add_action( 'wp_dashboard_setup','ds_hide_dash' );
add_action( 'admin_head', 'ds_media_buttons_remove' );
add_action( '_admin_menu', 'ds_menu_disable' ); 

//------------------------------------------------------------------------//
//---Functions to Enable/Disable admin menus------------------------------//
//------------------------------------------------------------------------//
function ds_menu_disable() {
	global $submenu, $menu;
		$menu_perms = get_site_option( "menu_items" );
		if( is_array( $menu_perms ) == false )
		$menu_perms = array();
		
			if((( $menu_perms[ 'Site Administrator Gets Limited Menus?' ] != '1' ) && (is_site_admin())) ) // TODO: let there be settings first
			return;
	// 'Settings'
		if( $menu_perms[ 'Settings' ] != '1' && current_user_can('manage_options')) {
			if(!empty($menu)) {
		foreach($menu as $key => $sm) {
			if(__($sm[0]) == "Settings" || $sm[2] == "options-general.php") {
				unset($menu[$key]);
				break; 
				}
			}
			}
		if( strpos($_SERVER['REQUEST_URI'], 'options-general.php'))		
			wp_redirect('profile.php');
	}
	// 'Settings General'
	if( $menu_perms[ 'Settings General' ] != '1' && current_user_can('manage_options')) {
		if(!empty($submenu['options-general.php'])) {
		foreach($submenu['options-general.php'] as $key => $sm) {
			if(__($sm[0]) == "Settings" || $sm[2] == "options-general.php") {
				unset($submenu['options-general.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'options-general.php'))		
			wp_redirect('profile.php');
		
	}
	// 'Settings Writing'
	if( $menu_perms[ 'Settings Writing' ] != '1' && current_user_can('manage_options')) {
		if(!empty($submenu['options-general.php'])) {
		foreach($submenu['options-general.php'] as $key => $sm) {
			if(__($sm[0]) == "Writing" || $sm[2] == "options-writing.php") {
				unset($submenu['options-general.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'options-writing.php'))		
			wp_redirect('options-general.php');
		
	}
	// 'Settings Reading'
	if( $menu_perms[ 'Settings Reading' ] != '1' && current_user_can('manage_options')) {
		if(!empty($submenu['options-general.php'])) {
		foreach($submenu['options-general.php'] as $key => $sm) {
			if(__($sm[0]) == "Reading" || $sm[2] == "options-reading.php") {
				unset($submenu['options-general.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'options-reading.php'))		
			wp_redirect('options-general.php');
		
	}
	// 'Settings Discussion'
	if( $menu_perms[ 'Settings Discussion' ] != '1' && current_user_can('manage_options')) {
		if(!empty($submenu['options-general.php'])) {
		foreach($submenu['options-general.php'] as $key => $sm) {
			if(__($sm[0]) == "Discussion" || $sm[2] == "options-discussion.php") {
				unset($submenu['options-general.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'options-discussion.php'))		
			wp_redirect('options-general.php');
		
	}
	// 'Settings Privacy'
	if( $menu_perms[ 'Settings Privacy' ] != '1' && current_user_can('manage_options')) {
		if(!empty($submenu['options-general.php'])) {
		foreach($submenu['options-general.php'] as $key => $sm) {
			if(__($sm[0]) == "Privacy" || $sm[2] == "options-privacy.php") {
				unset($submenu['options-general.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'options-privacy.php'))		
			wp_redirect('options-general.php');
		
	}
	// 'Settings Permalinks'
	if( $menu_perms[ 'Settings Permalinks' ] != '1' && current_user_can('manage_options')) {
		if(!empty($submenu['options-general.php'])) {
		foreach($submenu['options-general.php'] as $key => $sm) {
			if(__($sm[0]) == "Permalinks" || $sm[2] == "options-permalink.php") {
				unset($submenu['options-general.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'options-permalink.php'))		
			wp_redirect('options-general.php');
	}
	// 'Settings Miscellaneous'
	if( $menu_perms[ 'Settings Miscellaneous' ] != '1' && current_user_can('manage_options')) {
		if(!empty($submenu['options-general.php'])) {
		foreach($submenu['options-general.php'] as $key => $sm) {
			if(__($sm[0]) == "Miscellaneous" || $sm[2] == "options-misc.php") {
				unset($submenu['options-general.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'options-misc.php'))		
			wp_redirect('options-general.php');
	}
	// 'Users'
	if( $menu_perms[ 'Users' ] != '1' && current_user_can('edit_users') ) {
		if(!empty($menu)) {
		foreach($menu as $key => $sm) {
			if(__($sm[0]) == "Users") {
				if( $menu_perms[ 'Users Your Profile' ] == '1' && current_user_can('read')) {
				$menu[$key] = array(__('Profile'), 'read', 'profile.php'); // promote
				} else {
				unset($menu[$key]);
				}
				break;
				}
			}
		} //	the redirect here is not possible, must also disable Author & Users to enable the redirect
	}
	// 'Users Authors and Users'
	if( $menu_perms[ 'Users Authors and Users' ] != '1' && current_user_can('edit_users')) {
		if(!empty($submenu['users.php'])) {
		foreach($submenu['users.php'] as $key => $sm) {
			if(__($sm[0]) == "Authors &amp; Users" || $sm[2] == "users.php") {
				unset($submenu['users.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'users.php'))		
			wp_redirect('profile.php');
	}
	// 'Users Your Profile'
	if( $menu_perms[ 'Users Your Profile' ] != '1' && current_user_can('read')) {
		if(!empty($submenu['users.php'])) {
		foreach($submenu['users.php'] as $key => $sm) {
			if(__($sm[0]) == "Your Profile" || $sm[2] == "profile.php") {
				unset($submenu['users.php'][$key]);
				break;
				}
			} 
		} elseif(( $menu_perms[ 'Users Your Profile' ] != '1' && current_user_can('read')) && !empty($menu)) {
			foreach($menu as $key => $sm) {
				if(!empty($sm[0])) {
			if(__($sm[0]) == "Profile" || $sm[2] == "profile.php") {
				unset($menu[$key]);
				break; 
					} // enabling a redirect here may be more trouble than it is worth. Shouldn't every user at least see a profile page?
				}
			}
		}
	}
	// 'Write'
	if( $menu_perms[ 'Write' ] != '1' && (current_user_can('edit_posts') || current_user_can('manage_links') || current_user_can('edit_pages'))) {
		if(!empty($menu)) {
			foreach($menu as $key => $sm) {
			if(__($sm[0]) == "Write" || $sm[2] == "post-new.php" || $sm[2] == "page-new.php" || $sm[2] == "link-add.php") {
				unset($menu[$key]);
				break; 
				}
			}
		} // disable child menus to add a redirect
	}
	// 'Write Post'
	if( $menu_perms[ 'Write Post' ] != '1' && current_user_can('edit_posts') ) {
		if(!empty($submenu['post-new.php'])) {
		foreach($submenu['post-new.php'] as $key => $sm) {
			if(__($sm[0]) == "Post" || $sm[2] == "post-new.php") {
				unset($submenu['post-new.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'post-new.php'))		
			wp_redirect('profile.php');
	}	
	// 'Write Page'
	if( $menu_perms[ 'Write Page' ] != '1' && current_user_can('edit_pages')) {
		if(!empty($submenu['post-new.php'])) {
		foreach($submenu['post-new.php'] as $key => $sm) {
			if(__($sm[0]) == "Page" || $sm[2] == "page-new.php") {
				unset($submenu['post-new.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'page-new.php'))		
			wp_redirect('post-new.php');
	}
	// 'Write Link'
	if( $menu_perms[ 'Write Link' ] != '1' && current_user_can('manage_links') ) {
		if(!empty($submenu['post-new.php'])) {
		foreach($submenu['post-new.php'] as $key => $sm) {
			if(__($sm[0]) == "Link" || $sm[2] == "link-add.php") {
				unset($submenu['post-new.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'link-add.php'))		
			wp_redirect('post-new.php');
	}
	// 'Manage'
	if( $menu_perms[ 'Manage' ] != '1'  && (current_user_can('edit_posts') || current_user_can('manage_links') || current_user_can('edit_pages'))) {
		if(!empty($menu)) {
		foreach($menu as $key => $sm) {
			if(__($sm[0]) == "Manage" || $sm[2] == "edit.php") {
				unset($menu[$key]);
				break; 
				}
			}
		}	//	disable the children to redirect
	}	
	
	// 'Manage Posts'
	if( $menu_perms[ 'Manage Posts' ] != '1' && current_user_can('edit_posts')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Posts" || $sm[2] == "edit.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}

		if( strpos($_SERVER['REQUEST_URI'], 'edit.php'))	// plugin settings will redirect, too.	
			wp_redirect('profile.php');
	}	
	
	// 'Manage Pages'
	if( $menu_perms[ 'Manage Pages' ] != '1' && current_user_can('edit_pages')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Pages" || $sm[2] == "edit-pages.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'edit-pages.php'))		
			wp_redirect('edit.php');
	}
	// 'Manage Links'
	if( $menu_perms[ 'Manage Links' ] != '1' && current_user_can('manage_links')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Links" || $sm[2] == "link-manager.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'link-manager.php'))		
			wp_redirect('edit.php');
	}
	// 'Manage Categories'
	if( $menu_perms[ 'Manage Categories' ] != '1' && current_user_can('manage_categories') ) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Categories" || $sm[2] == "categories.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'categories.php'))		
			wp_redirect('edit.php');
	}
	// 'Manage Link Categories'
	if( $menu_perms[ 'Manage Link Categories' ] != '1' && current_user_can('manage_categories')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Link Categories" || $sm[2] == "edit-link-categories.php") { // props sub001
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'edit-link-categories.php'))		
			wp_redirect('edit.php');
	}
	// 'Manage Tags'
	if( $menu_perms[ 'Manage Tags' ] != '1' && current_user_can('manage_categories')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Tags" || $sm[2] == "edit-tags.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'edit-tags.php'))		
			wp_redirect('edit.php');
	}
	// 'Manage Media Library'
	if( $menu_perms[ 'Manage Media Library' ] != '1' && current_user_can('upload_files')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Media Library" || $sm[2] == "upload.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'upload.php'))	// needs testing	
//			wp_redirect('edit.php'); // creates iframes within iframes in the popup media uploader
			wp_die('Sorry, uploads are closed.'); // kinda dumb if the media_buttons are not hidden in the post edit form.
	}
	// 'Manage Import'
	if( $menu_perms[ 'Manage Import' ] != '1' && current_user_can('import')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Import" || $sm[2] == "import.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'import.php'))	
			wp_redirect('edit.php');
	}
	// 'Manage Export'
	if( $menu_perms[ 'Manage Export' ] != '1' && current_user_can('import')) {
		if(!empty($submenu['edit.php'])) {
		foreach($submenu['edit.php'] as $key => $sm) {
			if(__($sm[0]) == "Export" || $sm[2] == "export.php") {
				unset($submenu['edit.php'][$key]);
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'export.php'))	
			wp_redirect('edit.php');
	}
	if( $menu_perms[ 'Comments' ] != '1' && current_user_can('edit_posts')) {
		if(!empty($menu)) {
		foreach($menu as $key => $sm) {
			if(__($sm[0]) == "Comments" || $sm[2] == "edit-comments.php") {
				unset ($menu[$key]); // kinda dumb if comments are open and awaiting moderation
				break;
				}
			}
		}
		if( strpos($_SERVER['REQUEST_URI'], 'edit-comments.php'))		
			wp_redirect('profile.php');
	}	
	// 'Design Themes'
	if( $menu_perms[ 'Design Themes' ] != '1' && current_user_can('switch_themes')) { 
		if(!empty($submenu['themes.php'])) {
		foreach($submenu['themes.php'] as $key => $sm) {
			if(__($sm[0]) == "Themes" || $sm[2] == "themes.php") {
				unset($submenu['themes.php'][$key]);
				break;
				}
			}
		}
		/*********
		//  redirecting themes may break more than it is worth ... needs testing with your theme options pages
		if( strpos($_SERVER['REQUEST_URI'], 'themes.php'))	
			wp_redirect('widgets.php'); 
			***********/
	} 

	// 'Move Design Menu to the Right'
	if( $menu_perms[ 'Move Design Menu to the Right' ] == '1' && current_user_can('switch_themes')) {
		if(!empty($menu)) {
		foreach($menu as $key => $sm) {
			if(__($sm[0]) == "Design") {
				$menu[31] = $menu[$key]; // New position 
				// Menus numbered 26-40 appear on the right ... Site Admin is 29
				unset($menu[15]); // orignial position
				break;
				}
			}
		}
	}
		// If 'Design' is hidden, Widgets and Themes are still url accessible
	if( $menu_perms[ 'Design' ] != '1' && current_user_can('switch_themes')) {
		if(!empty($menu)) {
		foreach($menu as $key => $sm) {
			if(__($sm[0]) == "Design") {
				unset ($menu[$key]); 
				break;
				}
			}
		}
	}	 // best not to redirect here, either	
}
// 'Delete Blog'
function ds_delete_blog_menu_disable() {
	global $submenu, $menu, $delete_blog_obj;
	$menu_perms = get_site_option( "menu_items" );
	if( is_array( $menu_perms ) == false )
		$menu_perms = array();
			if(( $menu_perms[ 'Site Administrator Gets Limited Menus?' ] != '1' ) && (is_site_admin()))
			return;
	if( $menu_perms[ 'Settings Delete Blog' ] != '1' ) {
		if (isset($delete_blog_obj)) {
			remove_action('admin_menu', array(&$delete_blog_obj, 'admin_menu'));
			}
	} // no rediect needed in my tests
}

//------------------------------------------------------------------------//
//--- Function to toggle Media Buttons in edit forms----------------------//
//----Media buttons are limited by default in WPMU because they can't synch with allowed upload file types--//
function ds_media_buttons_remove () {
	$menu_perms = get_site_option( "menu_items" );
	if( is_array( $menu_perms ) == false )
		$menu_perms = array();
			if(( $menu_perms[ 'Site Administrator Gets Limited Menus?' ] != '1' ) && (is_site_admin()))
			return;
	if( $menu_perms[ 'Mu Media Buttons' ] != '1' ) 
	 	remove_action( 'media_buttons', 'mu_media_buttons' );
					
 	if( $menu_perms[ 'Media Buttons' ] == '1' ) 
	 	add_action( 'media_buttons', 'media_buttons' );
}
//------------------------------------------------------------------------//
//---Functions to redirect from Dashboard --------------------------------//
//---Warning: any other plugin using dashboard setup may be affected------//

function ds_hide_dash() {
	$menu_perms = get_site_option( "menu_items" );
	if( is_array( $menu_perms ) == false )
		$menu_perms = array();
			if(( $menu_perms[ 'Site Administrator Gets Limited Menus?' ] != '1' ) && (is_site_admin()))
			return;
			if( $menu_perms[ 'Dashboard' ] != '1' ) {
			wp_redirect('profile.php');
			exit();
		}
}

//------------------------------------------------------------------------//
//---Function SiteAdmin->Options------------------------------------------//
//---Options are saved as site_options on wpmu-options.php page-----------//
function ds_menu_option() {
	$menu_perms = get_site_option( "menu_items" );
	if( is_array( $menu_perms ) == false )
		$menu_perms = array();
			$menu_items = array(
			// remove or add any menu, just be sure to do same above 
			'Site Administrator Gets Limited Menus?',
			'Settings',
			'Settings General',
			'Settings Writing',
			'Settings Reading',
			'Settings Discussion', 
			'Settings Privacy',
			'Settings Permalinks',
			'Settings Miscellaneous', 
			'Settings Delete Blog',
			'Users', 
			'Users Authors and Users',
			'Users Your Profile',
			'Write',
			'Write Post',
			'Write Page',
			'Mu Media Buttons',
			'Media Buttons',
			'Write Link',
			'Manage',
			'Manage Posts',
			'Manage Pages',
			'Manage Links',
			'Manage Media Library',
			'Manage Categories',
			'Manage Link Categories',
			'Manage Tags',
			'Manage Import',
			'Manage Export',
			'Comments',
			'Design', 
			'Design Themes',
			'Move Design Menu to the Right',
			'Dashboard'
			);
	echo '<table class="form-table">'; 

			foreach ( (array) $menu_items as $key => $val ) {
				$checked = ( $menu_perms[$val] == '1' ) ? ' checked=""' : '';
				echo "<tr><th scope='row'>" . ucfirst( $val ) . "</th><th scope='row'><input type='checkbox' name='menu_items[" . $val . "]' value='1'" . $checked . " /></th></tr>"; 
			}
	echo '
	</table>
	<small>Known Menu Bugs: Enabling a parent menu while all its submenus are disabled may do quirky things. ie. Disabling "Authors and Users" and "Your Profile" but enabling the "Users" parent menu will re-enable the "Authors and Users" menu. Disabling "Your Profile" may not be a good idea, there needs to be a page every user can see. Even though a menu(or submenu) is disabled, access to the menu page(or submenu pages) via the url may still be possible. Happy testing!</small>';
}	
?>