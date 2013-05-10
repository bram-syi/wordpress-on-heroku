<?php
/*
 Plugin Name: Admin Edit/Manage Profile Fields
 Plugin URI: http://www.smallgiving.org
 Description: Admin will be able to edit or manage the profile details of the user
 Author: Ageesh
 Version: 0.1
 Author URI: http://seeyourimpact.com/
 */


// create hook for widget


/**
 * This will add the meta box to the post page
 *
 */
function show_profile_fields() 
{
    global $bp, $current_blog;
    
    if ($current_blog->blog_id == 1)
		return; // Don't display.
    
    if(isset($_REQUEST['user_id']))
    {
        $user_id = (int) $_REQUEST['user_id'];
    }
    else
    {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
    }
    
    if(isset($user_id))
    {
        $user = new WP_User( $user_id );
        $user->job   = attribute_escape($user->job);
        $user->location_city   = attribute_escape($user->location_city);
        $user->location_country   = attribute_escape($user->location_country);
        
        if(isset($user->with_charity))
        {
            $user->with_charity   = attribute_escape($user->with_charity);
        }
        else
        {
            $date_value = explode(" ",attribute_escape($user->user_registered));
            $user->with_charity = $date_value[0];
        }
?>
        <script type="text/javascript">
        function validateDate(aff_with_charity)
        {
            var datePattern = /(?:19|20\d{2})\-(?:0[1-9]|1[0-2])\-(?:0[1-9]|[12][0-9]|3[01])/;
            
            if ((aff_with_charity.value.match(datePattern)) && (aff_with_charity.value!='')) 
            {
                var trimmed = aff_with_charity.value.replace(/^\s+|\s+$/g, '') ;
                if(trimmed.length != 10 )
                {
                alert("Please enter affiliation date as yyyy-mm-dd");
                document.getElementById('with_charity').focus();
                }
            }
            else
            {
                alert("Please enter affiliation date as yyyy-mm-dd");
                document.getElementById('with_charity').focus();
            } 
        }
        </script>
        
        <h3><?php _e('Extra profile fields') ?></h3>


        <table class="form-table">
        	<tr>
        		<th><label for="job"><?php _e('Occupation') ?></label></th>
        		<td><input type="text" name="job" id="job"
        			value="<?php echo $user->job  ?>" /></td>
        	</tr>
        
        	<tr>
        		<th><label for="location_city"><?php _e('Location City/Town') ?></label></th>
        		<td><input type="text" name="location_city" id="location"
        			value="<?php echo $user->location_city ?>" /></td>
        	</tr>
        	
        	<tr>
                <th><label for="location_country"><?php _e('Location Country') ?></label></th>
                <td><input type="text" name="location_country" id="location"
                    value="<?php echo $user->location_country ?>" /></td>
            </tr>
        
        	<tr>
        		<th><label for="with_charity"><?php _e('Affiliate with Charity Since') ?></label></th>
        		<td><input type="text" name="with_charity" id="with_charity"
        			onblur="validateDate(this);" value="<?php echo $user->with_charity?>" /></td>
        	</tr>
        	
        	<tr>
        		<th><label for="group"><?php _e('Assign to Group') ?></label></th>
        		<td><select name="group">
<?php
        		$charity_groups = get_charity_groups();
        		
        		foreach ( $charity_groups as $charity_group )
        		{
        		    if(BP_Groups_Member::check_is_member( $user_id, $charity_group->id ) )
        		    {
?>
        			<option selected="selected" value="<?=$charity_group->id?>"><?=$charity_group->name?></option>
<?php
        		    }
        		    else
        		    {
?>
        			<option value="<?=$charity_group->id?>"><?=$charity_group->name?></option>
<?php
        		    }
        		}
?>
        		</select></td>
        	</tr>
        </table>

<?php
    }
}


function show_message_link()
{
    global $bp,$current_blog;
    if ($current_blog->blog_id == 1)
    return; // Don't display.
    
    $messages_link = $bp->loggedin_user->domain . $bp->messages->slug . '/'; 
?>
    <h3><?php _e('Messaging') ?></h3>
    <table class="form-table">
        <tr>
            <th>
                <a href="<?= $messages_link?>" ><?php _e('Click here to send message ') ?></a>
            </th>
        </tr>
    </table>
<?php    
}

function update_profile_fields($user_id, $old_data = NULL) {
  if (!isset($user_id))
    return;

  // Sync donor from user
  $updated_donor = user_main_donor_sync($user_id);
}

add_action('show_user_profile', 'show_message_link');
add_action('show_user_profile', 'show_profile_fields');
add_action('edit_user_profile', 'show_profile_fields');
add_action('profile_update', 'update_profile_fields');

function get_charity_groups()
{
    global $bp, $blog_id;

    $charityGroups = array ();

// STEVEE: quick fix to bypass this because BP_Groups_Group usage below is broken.
    return $charityGroups;

	if (BP_Groups_Group::search_groups) {
		$groups = BP_Groups_Group::search_groups('');
		if ($groups['groups']) 
		{
			foreach ($groups['groups'] as $group) 
			{
				if (groups_get_groupmeta($group->group_id, 'blogid') == $blog_id)
				$charityGroups[] = new BP_Groups_Group($group->group_id);
			}
		}
	}
    return $charityGroups;
}

?>
