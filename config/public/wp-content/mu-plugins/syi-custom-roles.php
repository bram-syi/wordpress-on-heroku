<?php
/*
Plugin Name: SYI Custom Roles
Plugin URI: http://wwww.smallgiving.org
Description: Adds the custom roles for the SeeYourImpact.org network.
Version: 1.0
Author: Mohd Amjed
Author URI: http://smallgiving.org
*/
 
/**
 * Each custom role created below defines a uniques set of capabilities in the SYI system.
 * These roles enables the Charity owners to effectively manage the charity by delegating the releative task to one role.
 * And then assigning the role to a user, who performs the activities in the system.
 */
 
 
/**
 * Roles definition
 * Each role has its display name and an associated set of capabilities
 */
$syi_roles=array(
	'volunteer_author'=>array(
		'display_name'=> 'Field Staff',
		'capabilities'=>array(
			//Privileges to perform the design related scenarios 
	    	'switch_themes',
			'edit_themes',
			
			//Plugin management
			'activate_plugins',
			'edit_plugins',
			
			'edit_users',
			'edit_files',
			'manage_options',
			'moderate_comments',
			'manage_categories',
			'manage_links',
			'upload_files',
			'import',
			
			//Posts and pages
			'edit_posts',
			'edit_others_posts',
			'edit_published_posts',
			'edit_pages',
			'read',
			
			//Legacy
			'level_9',
			'level_8',
			'level_7',
			'level_6',
			'level_5',
			'level_4',
			'level_3',
			'level_2',
			'level_1',
			'level_0'
			)
		)
	);

/**
 * Define custom permission roles
 * @param unknown_type $name Name of the custom role
 * @param unknown_type $role_def Role definition for the custom role
 * 
 * @return none
 */
function define_role($name, $role_def)
{
    if( (empty($name)) || !is_array($role_def) )
     	return ;
    $is_role = get_role($name);

    if (isset($is_role))  return;
    
    add_role($name, $role_def['display_name']);
    $new_role = get_role($name);

    foreach ($role_def['capabilities'] as $cap) {
        $new_role->add_cap($cap);
    }
}
		
/**
 * Check for each custom role in the SYI system, if the role by the name does not exists only then it created a role
 * with the corresponding definition.
 */
function add_syi_roles()
{
	global $syi_roles;
	
	foreach($syi_roles as $role_name => $role){
		define_role($role_name, $role);
	}
}
?>
