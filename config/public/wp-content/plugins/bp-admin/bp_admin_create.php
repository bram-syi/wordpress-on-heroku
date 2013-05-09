<?php
/*
 Plugin Name: To create BP specifi Admin
 Plugin URI: http://www.smallgiving.org
 Description: BP admin, who can create accounts for other members
 Author: Ageesh
 Version: 0.1
 Author URI: http://seeyourimpact.com/
 */


// create hook for widget
function add_create_admin_menu(){
	add_menu_page('BP Admin','BP Admin',10,__FILE__,'create_BP_admin');
	}
	
function create_BP_admin()
{
	
	$wp_user_search = new WP_User_Search('','','');
	$switch=false;
	?>
<script language="JavaScript" type="text/javascript">
function createBPAdmin() 
{
	selected = false;
 var arrayElements= document.getElementsByName('userID');
 var check_value="";
 for (var i =0; i < arrayElements.length; i++) 
	{
		if(arrayElements[i].checked)
		{
		  check_value = check_value+"," + arrayElements[i].value;
		  selected = true;
		}
	}
	if(selected)
	{
  	document.getElementById('userIds').value=check_value;	
	}
	else
	{
		alert("Please select one donation to proceed");
		return false;
	}
	var selectedValue = document.getElementById('actionStatus').value;
	if(selectedValue.length < 1)
	{
		alert("Please select member badge to proceed");
		return false;
	}
	
}	
</script>
<div class="wrap">
<br/>
<h2><?php _e('Manage Member Badge : ');?></h2>
<form name="create-bpAdmin" id="create-bpAdmin" action="#" method="post">
<div class="tablenav">
<div class="alignleft">
<select id="actionStatus" name="actionStatus"><option value="">Select</option>
<?
 $groupIDs = get_memberBadges();
 global $wpdb; 
foreach($groupIDs as $group_id ) {?>
 <option value="<?=$group_id?>"><?=$group_id?></option>
<? } ?>	
</select>
<input type="submit" class="button-secondary delete"  name="bpAdmin" onclick="return createBPAdmin()" value="Change Member Badge"/>	
<input type="hidden" value="None" id="userIds" name="userIds"/>
</div>
</div>
<br/>
<br/>
<?php
  
if(isset($_POST['userIds']))
{
 	 
 $selectedUserIds = substr($_POST['userIds'],1);//removing the first comma from the User IDs
 $userIds = explode(',',$selectedUserIds);
 foreach($userIds as $userid)
 {
 	update_usermeta($userid,$wpdb->prefix.'member_badge',$_POST['actionStatus']);
 }	
 	_e('Member badge changed successfully ');
}	
else
{
	_e('Select the users to change to Member Badge ');
}
?>

<br/>
<br/>
<table class="widefat" cellspacing="0" cellpadding="0" border="1" width="100%" >
	<tr style="background-color:#2583AD;">
		<th width="10%"><?php _e('Select '); ?></th>
		<th width="20%"><?php _e('User Name'); ?></th>
		<th width="20%"><?php _e('Name'); ?></th>
		<th width="30%"><?php _e('email'); ?></th>
		<th width="20%"><?php _e('member badge'); ?></th>
	</tr>
<? 
	 foreach ( $wp_user_search->get_results() as $userid ) {
      $user_object = new WP_User($userid); 
      $roles = $user_object->roles;
      	  $role = array_shift($roles);
      ?>
      
	<tr <?php if($switch) echo 'class="alternate"'; ?> >
	<td ><input type="checkbox" name="userID" id="userID" value="<?= $user_object->ID ?>"></td>
	<td><?= $user_object->user_login ?></td>	
	<td><?= $user_object->display_name?></td>	
	<td><?= $user_object->user_email ?></td>
	<td><?=  get_usermeta($userid,$wpdb->prefix.'member_badge') ?></td>	
	</tr>
<?php
	if($switch)
		$switch=false;
	else
		$switch=true;
	  }
?>
</table>
</form>
</div>	
<?	
}
add_action('admin_menu','add_create_admin_menu');

?>