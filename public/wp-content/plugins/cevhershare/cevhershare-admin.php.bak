<?php
	/*  Copyright 2010  Rashad Aliyev  (email : contact@rashadaliyev.com)
	      Author Homepage: http://wpusta.com  Author Blog: http://cevhershare.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
		
	$id = $_GET['id'] ? intval($_GET['id']) : intval($_POST['id']);
    if (!is_numeric($id) && !preg_match("/^([0-9]+)$/i",$id))
    {
        die('Cevher Security!');
    }
    
	$pos = $_GET['pos'] ? $_GET['pos'] : $_POST['pos'];
	$status = $_GET['status'] ? $_GET['status'] : $_POST['status'];
	$task = $_GET['t'] ? $_GET['t'] : $_POST['t'];
	$do = $_POST['do'];
	if($do == "update-lang"){
		$uplang = $_POST['update-lang'];
		update_option('cevhershare_language',$uplang);
	}
	if($id)	$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."cevhershare WHERE id=$id");
	if($do == 'update') $wpdb->query("UPDATE ".$wpdb->prefix."cevhershare SET enabled='".$_POST['enabled']."', position='".$_POST['position']."', name='".$_POST['name']."', big='".$_POST['big']."', small='".$_POST['small']."' WHERE id='$id'");
	elseif($do == 'add') $wpdb->query("INSERT INTO ".$wpdb->prefix."cevhershare (position, name, big, small) VALUES('".$_POST['position']."','".$_POST['name']."', '".$_POST['big']."', '".$_POST['small']."')");
	elseif($do == 'delete') $wpdb->query("DELETE FROM ".$wpdb->prefix."cevhershare WHERE id=$id LIMIT 1");
	elseif($do == 'reset') cevhershare_reset();
	elseif($do == 'settings'){
		$auto_posts = $_POST['auto_posts'] ? 1:0; $auto_pages = $_POST['auto_pages'] ? 1:0; $horizontal = $_POST['horizontal'] ? 1:0;
		$width = $_POST['width']; $position = $_POST['position']; $credit = $_POST['credit'] ? 1:0;
		$leftoffset = $_POST['leftoffset']; $rightoffset = $_POST['rightoffset'];
		$swidth = $_POST['swidth']; $twitter_username = $_POST['twitter_username'];
		cevhershare_settings($auto_posts, $auto_pages, $horizontal, $width, $position, $leftoffset, $rightoffset, $credit, $swidth, $twitter_username);
	
		$seffaf = $_POST['seffaf'];
		update_option('cevhershare_seffaf',$seffaf);
		
		$arxaplan = $_POST['arxaplan'];
		update_option('cevhershare_arxaplan',$arxaplan);
		
		$ciziborders = $_POST['ciziborders'];
		update_option('cevhershare_ciziborders',$ciziborder);
							
		$uplangs = $_POST['uplangs'];
		update_option('cevhershare_language',$uplangs);
		
	$uplangs = get_option('cevhershare_language'); //dil ozelliyi
	if($uplangs == 'english'){	
	include_once ('lang/lang_en.php');}
	elseif($uplangs == 'azerbaijan'){
	include_once ('lang/lang_az.php');}
	elseif($uplangs == 'russian'){
	include_once ('lang/lang_ru.php');} 
	elseif($uplangs == 'turkish'){
	include_once ('lang/lang_tr.php');}	//dil bitdi	
	
	}elseif($do == 'update-all'){
		$buttons = $_POST['buttons'];
		$uptask = $_POST['update-task'];
		if($buttons){
			foreach ($buttons as $button)
				cevhershare_update_button($button,$uptask);
			$status = "Buttons have been ".$uptask."d";
		}else
			$status = "No buttons selected.";
	}
	if($task == "linkback"){
		if($credit){
			$current = "disabled";
			update_option('cevhershare_credit','0');
		}else{
			$current = "enabled";
			update_option('cevhershare_credit','1');
		}
		$status = 'Linkback '.$current;
		$credit = get_option('cevhershare_credit');
	}
	$dil = get_option('cevhershare_language'); //dil ozelliyi
	if($dil == 'english'){	
	include_once ('lang/lang_en.php');}
	elseif($dil == 'azerbaijan'){
	include_once ('lang/lang_az.php');}
	elseif($dil == 'russian'){
	include_once ('lang/lang_ru.php');} 
	elseif($dil == 'turkish'){
	include_once ('lang/lang_tr.php');}	//dil bitdi	
	if($pos == 'moveup') $wpdb->query("UPDATE ".$wpdb->prefix."cevhershare SET position=position-1 WHERE id='$id'");
	if($pos == 'movedown') $wpdb->query("UPDATE ".$wpdb->prefix."cevhershare SET position=position+1 WHERE id='$id'");
	if($pos) $status = "<?php echo POSITION_UPDATED;?>";
?>
<style>
	.wrap { width: 700px; }
	.h4title { margin:0 0 20px;overflow:hidden; }
	.wrap form label.wide { width: 100%; float: left; padding: 2px; font-weight: bold; }
	.wrap form .text { width: 400px; }
	.wrap form .mediumtext { width: 160px; }
	.wrap form .smalltext { width: 120px; }
	.wrap form .minitext { width: 50px; margin-right: 5px; }
	.wrap form .checkbox { margin-right: 5px; }
	.wrap form .checkfield { margin: 32px 0 0 15px; }
	.wrap form p.mediumtext { margin-right: 20px; }
	.thebutton { text-align: center; overflow: hidden; background: #fff; padding: 10px; border: 1px solid #ccc; }
	.thebutton td { padding: 0 15px; }
	.thebutton .name { padding: 0 0 10px; }
	.right-button { margin: 15px 0 0 15px; }
	.info-box { width: 400px; float: left; padding: 0 10px; background: #fff; border: 1px solid #ccc; -webkit-border-radius: 6px; -moz-border-radius: 6px; border-radius: 6px; min-height:330px;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FBFBFB', endColorstr='white'); background: -webkit-gradient(linear, left top, left bottom, from(#F8F8F8), to(white)); background: -moz-linear-gradient(top, #F8F8F8, white);}
	.info-box-right { width: 250px; float: right; padding: 0 10px; background: #fff; border: 1px solid #ccc; -webkit-border-radius: 6px; -moz-border-radius: 6px; border-radius: 6px; min-height:330px;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FBFBFB', endColorstr='white'); background: -webkit-gradient(linear, left top, left bottom, from(#F8F8F8), to(white)); background: -moz-linear-gradient(top, #F8F8F8, white);}
	.info-box-right p { font-size: 11px; }
	.info-box-right ul { font-size: 11px; list-style-type: square; list-style-position: inside; }
	.info-box-right ul li { margin: 0 0 15px; }
	.sb-divider { clear: both; width: 100%: float: left; border-bottom: 5px solid #ddd; display: block; height: 20px;}

	#cevhershare-tl{width:700px;margin:0;padding:0; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FBFBFB', endColorstr='white');
background: -webkit-gradient(linear, left top, left bottom, from(#F8F8F8), to(white)); background: -moz-linear-gradient(top, #F8F8F8, white);}
	#cevhershare-tl caption{width:700px;font:italic 11px Arial, "Trebuchet MS", Verdana,  Helvetica, sans-serif;text-align:right;padding:0 0 5px;}
	#cevhershare-tl th{font:bold 11px Arial, "Trebuchet MS", Verdana,  Helvetica, sans-serif;color:#4f6b72;border-right:1px solid #C1DAD7;border-bottom:1px solid #C1DAD7;border-top:1px solid #C1DAD7;letter-spacing:2px;text-transform:uppercase;text-align:left;background:#CAE8EA no-repeat;padding:6px 6px 6px 12px;}
	#cevhershare-tl th.nobg{border-top:0;border-left:0;border-right:1px solid #C1DAD7;background:none;}
	#cevhershare-tl td{border-right:1px solid #C1DAD7;border-bottom:1px solid #C1DAD7;background:#fff;color:#4f6b72;padding:6px 6px 6px 12px;}
	#cevhershare-tl td.alt{background:#F5FAFA;color:#797268;}
	#cevhershare-tl th.spec{border-left:1px solid #C1DAD7;border-top:0;background:#fff no-repeat;font:bold 10px Arial, "Trebuchet MS", Verdana,  Helvetica, sans-serif;}
	#cevhershare-tl th.specalt{border-left:1px solid #C1DAD7;border-top:0;background:#f5fafa no-repeat;font:bold 10px Arial, "Trebuchet MS", Verdana,  Helvetica, sans-serif;color:#797268;}
	#cevhershare-tl td, #cevhershare-tl th { text-align: center; }
	#cevhershare-tl tr { margin-bottom: 10px; }
	#cevhershare-tl tr.disabled td { background: #f2f2f2; }
	#cevhershare-tl .leftj { text-align: left; }
	.cevhershare-button { font-size: 11px; font-family: Verdana, Arial; padding: 2px 4px; background: #f7f7f7; color: #444; border: 1px solid #ddd; display: block; }
	.cevhershare-button:hover { border-color: #aaa; }
	.FBConnectButton_Small{background-position:-5px -232px !important;border-left:1px solid #1A356E;}
	.FBConnectButton_Text{margin-left:12px !important ;padding:2px 3px 3px !important;}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.toggle-all').click(function(){
		var checkboxes = jQuery('form').find(':checkbox');
		checkboxes.attr('checked', !checkboxes.attr('checked'));
		return false;
	});
});
</script>

<div class="wrap">

<?php if($status){?><div id="message" class="updated fade"><?php echo $status; ?></div><?php } ?>

<h2><?php echo CUSTOM_CEVHER;?></h2>

<h4 class="h4title"><div class="alignleft"><?php echo MADE_BY;?> <a href="http://wpusta.com/" target="_blank">R. Aliyev</a> <?php echo OF;?> <a href="http://wpusta.com/" target="_blank">WpUsta.COM</a></div><div class="alignright"><a href="?page=CevherShare"><?php echo HOME;?></a> - <a href="?page=CevherShare&t=settings"><?php echo SETTINGS;?></a> - <a href="../wp-content/plugins/cevhershare/readme.txt" target="_blank"><?php echo CHANGELOG;?></a> - <a href="http://cevhershare.com/support/"><?php echo SUPPORT;?></a> - <a href="?page=CevherShare&t=donate"><?php echo DONATE;?></a></div></h4>

<?php if($task == 'edit' || $task == 'new'){?>

	<h3><?php if($task == 'edit') echo DUZELIS; else echo YENI; ?> "<?php echo BUTTON;?>"</h3>
	<p><?php echo BUTTON_INFO;?></p>
	<p><?php echo TWEET_INFO;?></p>
	<?php
		if($task == 'edit'){
			echo '<table class="thebutton">';
			echo "<tr><th class='name'><strong>".$item->name.":</strong></th></tr>";
			echo "<tr><td>".$item->big."</td>";
			echo "<td>".$item->small."</td></tr>";
			echo '</table>';
		}
		if($item->enabled) $enabled = " checked='true'";
	?>
	<form action="?page=<?php echo $_GET['page']; ?>" method="post">
		<p class="mediumtext alignleft">
			<label for="name" class="wide"><?php echo NAME;?>:</label>
			<input type="text" name="name" id="name" value="<?php echo $item->name; ?>" class="mediumtext" />
		</p>
		<p class="smalltext alignleft">
			<label for="position" class="wide"><?php echo POSITION;?>:</label>
			<input type="text" name="position" id="position" value="<?php echo $item->position; ?>" class="smalltext" />
		</p>
		<p class="checkfield alignleft">
			<input type="hidden" name="enabled" value="0" />
			<input type="checkbox" name="enabled" id="enabled" value="1" <?php echo $enabled; ?> /> <label for="enabled"><?php echo IS_ENABLED;?></label>
		</p>
		<div style="clear:both;"></div>
		<p>
			<label for="big" class="wide"><?php echo BIG_BUTTON;?>:</label>
			<textarea name="big" id="big" class="text" rows=5><?php echo $item->big; ?></textarea>
		</p>
		<p>
			<label for="small" class="wide"><?php echo SMALL_BUTTON;?>:</label>
			<textarea name="small" id="small" class="text" rows=5><?php echo $item->small; ?></textarea>
		</p>
		<input type="hidden" name="do" value="<?php if($task == 'edit') echo "update"; else echo "add"; ?>" />
		<input type="hidden" name="id" value="<?php echo $item->id; ?>" />
		<input type="hidden" name="status" value="<?php echo SHARE_BUTTON;?> <?php if($task == 'edit') echo SHARE_BUTTON_UPDATED; else echo SHARE_BUTTON_ADDED; ?>." />
		<input type="submit" value="<?php if($task == 'edit') echo UPDATE_BUTTON; else echo ADD_BUTTON; ?>" class="alignleft button-primary" />
	</form>
	<a href="?page=<?php echo $_GET['page']; ?>" class="alignleft" style="margin: 2px 0 0 10px;"><?php echo CANCEL;?></a>
		
<?php }elseif($task == 'delete'){ ?>

	<h3><?php echo SILIRSEN;?></h3>
	<?php
		echo '<table class="thebutton">';
		echo "<tr><th class='name'><strong>".$item->name.":</strong></th></tr>";
		echo "<tr><td>".$item->big."</td>";
		echo "<td>".$item->small."</td></tr>";
		echo '</table>';
	?>
	<p><?php echo SILMEYE_EMINSEN;?></p>
	<form action="?page=<?php echo $_GET['page']; ?>" method="post">
		<input type="hidden" name="do" value="delete" />
		<input type="hidden" name="id" value="<?php echo $item->id; ?>" />
		<input type="hidden" name="status" value="<?php echo SHARE_BUTTON;?> <?php echo DELETED;?>." />
		<input type="submit" value="<?php echo YES;?>" class="alignleft button-primary" />
	</form>
	<a href="?page=<?php echo $_GET['page']; ?>" class="alignleft" style="margin: 2px 0 0 10px;"><?php echo CANCEL;?></a>
		
<?php }elseif($task == 'reset'){ ?>

	<h3><?php echo RESET_BUTTONS;?></h3>
	<p><?php echo RESETE_EMINSEN;?></p>
	<form action="?page=<?php echo $_GET['page']; ?>" method="post">
		<input type="hidden" name="do" value="reset" />
		<input type="hidden" name="status" value="All buttons have been reset to inital configuration." />
		<input type="submit" value="<?php echo RESET_ALL_BUTTONS;?>" class="alignleft button-primary" />
	</form>
	<a href="?page=<?php echo $_GET['page']; ?>" class="alignleft" style="margin: 2px 0 0 10px;"><?php echo CANCEL;?></a>
		
<?php }elseif($task == 'settings'){ ?>

	<h3>CevherShare <?php echo SETTINGS;?></h3>
	<form action="?page=<?php echo $_GET['page']; ?>" method="post">
		<h4><?php echo ADD_CEVHERSHARE;?></h4>
		<p><?php echo ADD_DETAILS;?></p>
		<p>
			<input type="checkbox" name="auto_posts" id="auto_posts" value="true" class="checkbox" <?php if($auto_posts) echo "checked"; ?> /><label for="auto_posts"><?php echo ADD_POST;?></label>
		</p>
		<p>
			<input type="checkbox" name="auto_pages" id="auto_pages" value="true" class="checkbox" <?php if($auto_pages) echo "checked"; ?> /><label for="auto_pages"><?php echo ADD_PAGE;?></label>
		</p>
		<h4><?php echo DISPLAY_OPTIONS;?></h4>
		<p>
			<input type="checkbox" name="horizontal" value="true" id="horizontal" class="checkbox" <?php if($horizontal) echo "checked"; ?> /><label for="horizontal"><?php echo DISPLAY_HORIZONTALIF;?> <em><?php echo $width; ?>px</em> <?php echo DISPLAY_HORIZONTAL;?></label>
		</p>
		<p>
			<input type="checkbox" name="credit" value="true" id="credit" class="checkbox" <?php if($credit) echo "checked"; ?> /><label for="credit"><?php echo IF_DONATING;?></label>
		</p>
		<p>
		<select name="position" id="position">
				<option value="left"<?php if($position == 'left') echo " selected"; ?>><?php echo LEFT;?></option>
				<option value="right"<?php if($position == 'right') echo " selected"; ?>><?php echo RIGHT;?></option>
			</select>	 
			<label for="position"><?php echo CEVHERSHARE_POSITION;?></label>
		</p>
		<p>
			<input type="text" name="leftoffset" id="leftoffset" class="minitext" value="<?php echo $leftoffset; ?>" /><label for="leftoffset"><?php echo LEFT_OFFSET;?></label>
		</p>
		<p>
			<input type="text" name="rightoffset" id="rightoffset" class="minitext" value="<?php echo $rightoffset; ?>" /><label for="rightoffset"><?php echo RIGHT_OFFSET;?></label>
		</p>
		<p>
			<input type="text" name="width" id="width" class="minitext" value="<?php echo $width; ?>" /><label for="width"><?php echo MINIMUM_WIDTH;?></label>
		</p>
		<h4><?php echo CUSTOMIZE;?></h4>
		<p>
			<label for="swidth"><?php echo CEVHER_WIDTH;?>:</label>
			<input type="text" name="swidth" id="swidth" class="minitext" value="<?php echo $swidth; ?>" />
		</p>
		<p>
			<label for="twitter_username"><?php echo TWITTER_USERNAME;?>:</label>
			<input type="text" name="twitter_username" id="twitter_username" class="smalltext" value="<?php echo $twitter_username; ?>" />
		</p>
			
		<p>
			<label for="arxaplan"><?php echo ARXAPLAN;?></label>
		 	#<input type="text" name="arxaplan" id="arxaplan" class="smalltext" style="width:60px;" maxlength="6" value="<?php echo $arxaplan; ?>">
		</p>
		
		<!-- p>
			<label for="ciziborder"><?php echo CEVHERBORDER;?></label>
			#<input type="text" name="ciziborders" id="ciziborders" class="smalltext" style="width:60px;" maxlength="6" value="<?php echo $ciziborders; ?>">
		</p -->
				
		<p>
			<input type="checkbox" name="seffaf" value="true" id="seffaf" class="checkbox" <?php if($seffaf) echo "checked"; ?> />
			<label for="seffaf"><?php echo SEFFAF;?></label>
		</p>
		
		
		<p>
			<h4><?php echo SELECT_LANGUAGE;?></h4>
	
			<select id="uplangs" name="uplangs">
			<option value="english"<?php if($dil == 'english' || $uplang == 'english' ) echo " selected"; ?>><?php echo ENGLISH;?></option>
			<option value="azerbaijan"<?php if($dil == 'azerbaijan' || $uplang == 'azerbaijan') echo " selected"; ?>><?php echo AZERBAIJAN;?></option>
			<option value="russian"<?php if($dil == 'russian' || $uplang == 'russian') echo " selected"; ?>><?php echo RUSSIAN;?></option>
			<option value="turkish"<?php if($dil == 'turkish' || $uplang == 'turkish') echo " selected"; ?>><?php echo TURKISH;?></option>
			</select>
					 
			</p>
			<br />
		
		
		
		
		<input type="hidden" name="do" value="settings" />
		<input type="hidden" name="status" value="CevherShare <?php echo SETTINGS_UPDATED;?>." />
		<input type="submit" value="<?php echo UPDATE_SETTINGS;?>" class="alignleft button-primary" />
	</form>
	<a href="?page=<?php echo $_GET['page']; ?>" class="alignleft" style="margin: 2px 0 0 10px;"><?php echo CANCEL;?></a>
		
<?php }elseif($task == 'donate'){ ?>

	<h3><?php echo DONATE;?></h3>
	<p><?php echo CREATED_BY1;?> - <a href="http://twitter.com/wpusta">@wpusta</a>.  <?php echo CREATED_BY2;?>:</p>
		
		<p>
		<form id="donationform" style="text-align: left;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"> 
		<input name="bn" type="hidden" value="PP-DonationsBF" /> 
		<input name="currency_code" type="hidden" value="USD" /> 
		<input name="tax" type="hidden" value="0" /> 
		<input name="no_note" type="hidden" value="1" />
		<input name="item_name" type="hidden" value="CevherShare WordPress Plugin" /> 
		<input name="business" type="hidden" value="donate@wpusta.com" /> 
		<input name="no_shipping" type="hidden" value="1" /> 
		<input name="cmd" type="hidden" value="_xclick" />
		<div style="padding:10px;"><?php echo AMOUNT;?>:</div> 
		<div style="padding:10px;"> 
		<input id="amount" value="10" name="amount" size="4" type="text" style="font-size:22px;" /> USD</div> 
		<input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" type="image" /> <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" border="0" alt="" width="1" height="1" /></p> 
		</form> 
		</p>

 

<?php }elseif($task == 'saygilar'){ ?>

	<h3><?php echo THANKS;?></h3>
	<p><?php echo THANKS_FOR_TRANSLATE;?>:</p>
		<ul>
		 <li>Azerbaijan - <a href="http://www.wpusta.com" >WpUsta.com</a></li>
		 <li>English - <a href="http://www.wpusta.com" >WpUsta.com</a></li>
		 <li>Russian - <a href="http://www.aliyev.us"  rel="nofollow" >Aliyev.us</a></li>
		 <li>Turkish - <a href="http://www.wpusta.com" >WpUsta.com</a></li>
		 
		 <br/>
	     <h3>For Your Language? Contact With Us:  <font color="red">contact @ wpusta . com</font></h3>
		<ul>
		
	</p>	
		

<?php }else{ ?>



	<div class="info-box">
		<p>	<form action="?page=<?php echo $_GET['page']; ?>" method="post">	
			<label for="update-lang"><?php echo SELECT_LANGUAGE;?>:</label>
			<select id="update-lang" name="update-lang">
			<option value="english"<?php if($dil == 'english') echo " selected"; ?>><?php echo ENGLISH;?></option>
			<option value="azerbaijan"<?php if($dil == 'azerbaijan') echo " selected"; ?>><?php echo AZERBAIJAN;?></option>
			<option value="russian"<?php if($dil == 'russian') echo " selected"; ?>><?php echo RUSSIAN;?></option>
			<option value="turkish"<?php if($dil == 'turkish') echo " selected"; ?>><?php echo TURKISH;?></option>
			</select>
			<input type="hidden" name="status" value="CevherShare <?php echo SETTINGS_UPDATED;?>." />
			<input type="hidden" name="do" value="update-lang">
			<input type="submit" class="button" value="<?php echo UPDATE;?>" />
		</form>
		</p>
		
		
		<p><?php echo INFO;?></p>
		<p><?php echo BIG_BUTTONS;?> <strong><?php echo $width; ?>px</strong>.</p>
		<?php if($auto_posts || $auto_pages){
				$amsg .= "<p><strong>".AUTO_MODE_ON."</strong> - ".AUTO_WILL_ADD." ";
				if($auto_posts) $amsg .= "".POSTS."";
				if($auto_posts && $auto_pages) $amsg .= "".ANDS."";
				if($auto_pages) $amsg .= "".PAGES."";
				$amsg .= ".";
			}else
				$amsg .= "<p><strong>".AUTO_MODE_OFF."</strong>, ".AUTO_MODE_OFF_INFO.":</p>
							<blockquote><strong>".VERTICAL." CevherShare:</strong>
							<code>&lt;?php cevhershare(); ?&gt;</code><br />
							<strong>".HORIZONTAL." CevherShare:</strong>
							<code>&lt;?php cevhershare_horizontal(); ?&gt;</code></blockquote>";
			echo $amsg;
		?>
		<p><?php echo INDIVIDUAL;?>:</p>
		<p><code>&lt;?php cevhershare_button('<?php echo ADI;?>','size'); ?&gt;</code></p>
	</div>
	<div class="info-box-right">
		<h3><?php echo SUPPORT_US;?></h3>
		<p><?php echo IF_YOU_LIKE;?>:</p>
		<?php
			$current = $credit ? ''.DISABLE.'':''.ENABLE.'';
		?>
		<ul>
			<li><?php echo LINK_US;?> <a href="?page=<?php echo $_GET['page']; ?>&t=linkback" class="button"><?php echo $current; ?></a></li>
			<li><?php echo TWEET_US;?></li>
			<li><?php echo RAITING_US;?></li>
			<li><?php echo WP_RAITING_US;?></li>
			<li><?php echo BUY_US_COFFEE;?>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_donations">
		<input type="hidden" name="business" value="donate@wpusta.com">
		<input type="hidden" name="lc" value="US">
		<input type="hidden" name="item_name" value="CevherShare WordPress Plugin">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="<?php echo ABOUT_PAYPAL;?>">
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form> </li>
			
			 
			<li><?php echo FOLLOW_US_TWITTER;?></li>
			<li><a href="?page=CevherShare&t=saygilar"><?php echo THANKS;?></a></li>	
		</ul>
	</div>
	<div class="sb-divider"></div>
	
	<h3 class="alignleft"><?php echo AVAILABLE_BUTTONS;?>:</h3>
	<div class="alignright">
		<a href="?page=<?php echo $_GET['page']; ?>&t=reset" class="alignleft button right-button"><?php echo RESET_BUTTONS;?></a> <a href="?page=CevherShare&t=new" class="button-primary alignleft right-button"><?php echo ADD_NEW_BUTTON;?></a>
	</div>
	
	<form action="?page=<?php echo $_GET['page']; ?>" method="post">
	<table id="cevhershare-tl">
		<thead><tr><th><a href="/" class="toggle-all"><?php echo ALL;?></a></th><th class='leftj'><?php echo NAME;?></th><th><?php echo POSITION;?></th><th><?php echo BIG_BUTTON;?></th><th><?php echo SMALL_BUTTON;?></th><th><?php echo ACTIONS;?></th></tr></thead>
		<tbody>	
		<?php $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cevhershare ORDER BY position, id ASC"); echo "\n";
		foreach($results as $result){
			if(!$result->enabled){
				$dis = " class='disabled'";
				$name = '<em>'.$result->name.'</em>';
			}else{
				$dis = "";
				$name = $result->name;
			}
			echo "\t\t<tr$dis><td><input type='checkbox' name='buttons[]' id='buttons' value='".$result->id."' class='checkbox c23' /></td><td class='leftj'>".$name."</td><td width='55'><a href='?page=CevherShare&pos=moveup&id=".$result->id."'><img src='../wp-content/plugins/cevhershare/images/down.png'/></a>".$result->position."<a href='?page=CevherShare&pos=movedown&id=".$result->id."'><img src='../wp-content/plugins/cevhershare/images/up.png'/></a></td><td><div style='width:40px; padding-left:20px;'>".$result->big."</div></td><td>".$result->small."</td><td valign='middle'><a href='?page=".$_GET['page']."&t=edit&id=".$result->id."'><img title=".EDIT." alt=".EDIT." src='../wp-content/plugins/cevhershare/images/edit.png'/></a> | <a href='?page=".$_GET['page']."&t=delete&id=".$result->id."'><img title=".DELETE." alt=".DELETE."  src='../wp-content/plugins/cevhershare/images/del.png'/></a></td></tr>\n";
		} 
		?>
		</tbody>
	</table>
	<div class="alignleft">
		<p>
			<label for="update-task"><?php echo WITH_SELECTED;?>:</label>
			<select id="update-task" name="update-task">
				<option value="enable"><?php echo ENABLE;?></option>
				<option value="disable"><?php echo DISABLE;?></option>
				<option value="delete"><?php echo DELETE;?></option>
			</select>
			<input type="hidden" name="do" value="update-all">
			<input type="submit" class="button" value="<?php echo UPDATE;?>" />
		</p>
	</div>
	<div class="alignright">
		<p><small style="color:#808080;"><?php echo ENABLE_DISABLE_INFO;?></small></p>
	</div>
	</form>
<?php } ?>

</div>