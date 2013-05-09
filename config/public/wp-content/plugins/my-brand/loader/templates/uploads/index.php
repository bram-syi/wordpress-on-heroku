<?php require_once('../../../../../../wp-blog-header.php'); ?> 
<?php // COPYRIGHT (C) 2009-2011 KRIS JONASSON // WWW.KRISJAYDESIGNS.COM - WWW.GDSWEB.CA // RE-USE OF THIS SCRIPT OR ANY PART CONTAINED WITHIN IS PROHIBITED. // RIGHTS RESERVED. ?>

<script type="text/javascript" src="../../js/jquery-latest.js"></script>
<script type="text/javascript" src="../../js/upload-javascript.js"></script>
<script type="text/javascript" src="../../js/upload-javascript.b.js"></script>
<link rel="stylesheet" href="../../images/upload-style.css" type="text/css"/>
<div id="upload_wrap">

<table id="listings">
<tr style="">
<th style="width:50px;background:#000;color:#fff;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;">&nbsp;</th>
<th style="width:300px;background:#000;color:#fff;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;">File Name</th>
<th style="width:120px;background:#000;color:#fff;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;">Modified</th>
<th style="width:100px;background:#000;color:#fff;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;">Size</th>
<th style="width:120px;background:#000;color:#fff;border-bottom:3px #f1f1f1 solid;">View/Delete</th>
</tr>
<tr>

<?php
if( current_user_can('administrator') ) {
$css_file = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
if(isset($_GET['dele'])) { unlink($_GET['dele']);
 } // Define the full path to your folder from root
$set = $_SERVER["DOCUMENT_ROOT"]."/wp-content/plugins/my-brand/loader/templates/uploads";
// Open the folder
$css_handle = @opendir($set) or die("Unable to open $set");
// Loop through the files
while ($css = readdir($css_handle)) { if ($css == "." || $css == ".." || $css == "index.php" || strpos($css, '.gif',0) || strpos($css, '.jpg',0) || strpos($file, '.jpeg',0) || strpos($css, '.png',0) ) continue;
  echo "
<td style=\"margin:0px auto;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;height:33px;\"><img src=\"../../images/css.png\" style=\"\"/></a></td>
<td style=\"margin:0px auto;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;overflow:hidden;\">$css</td>
<td style=\"padding:2px;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;font-size:12px;\"> ". date ("m/d/Y - h:ia", filemtime($css));echo "</td>
<td style=\"padding:2px;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;\">" . round((filesize($css) / 1024), 2) . 'KB'; echo "</td>
<td style=\"padding:2px;text-align:center;background:#ccc;color:#000;border-bottom:3px #f1f1f1 solid;\"><p id=\"cssblok\" style=\"color:transparent;width:24px;height:26px;background:url(../../images/mf.png);\"><a href=\"$css\" id=\"cSsURL\" style=\"color:transparent;cursor:help;\">&nbsp;&nbsp;</a></p>&nbsp;&nbsp;<a href=\"$css_file?dele=$set/$css\"><img src=\"../../images/dlte.png\" alt=\"delete\" style=\"border:0px;margin-right:-25px;\"/></a></td></tr>
<tr>
"; } // Close
closedir($css_handle);
$curr_file = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
if(isset($_GET['del'])) { unlink($_GET['del']);
 } // Define the full path to your folder from root
$path = $_SERVER["DOCUMENT_ROOT"]."/wp-content/plugins/my-brand/loader/templates/uploads";
// Open the folder
$dir_handle = @opendir($path) or die("Unable to open $path");
// Loop through the files
while ($file = readdir($dir_handle)) { if($file == "." || $file == ".." || $file == "index.php" || strpos($file, '.css',1) ) continue;
  echo "
<td style=\"margin:0px auto;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;height:33px;\"><img src=\"../../images/image.png\" style=\"\"/></a></td>
<td style=\"margin:0px auto;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;overflow:hidden;\">$file</td>
<td style=\"padding:2px;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;font-size:12px;\"> ". date ("m/d/Y - h:ia", filemtime($file));echo "</td>
<td style=\"padding:2px;text-align:center;background:#ccc;color:#000;border-right:1px #1b1b1b solid;border-bottom:3px #f1f1f1 solid;\">" . round((filesize($file) / 1024), 2) . 'KB'; echo "</td>
<td style=\"padding:2px;text-align:center;background:#ccc;color:#000;border-bottom:3px #f1f1f1 solid;\"><p id=\"imgshow\" style=\"color:transparent;width:24px;height:26px;background:url(../../images/mf.png);\"><a class=\"faim\" style=\"cursor:wait;height:20px;\">&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"$file\" class=\"dropshadow\" alt=\"view\"/></a></p>&nbsp;&nbsp;<a href=\"$curr_file?del=$path/$file\"><img src=\"../../images/dlte.png\" alt=\"delete\" style=\"border:0px;margin-right:-25px;\"/></a></td></tr>
<tr>
"; } // Close
closedir($dir_handle); }  else { ?><?php wp_redirect( '<?php echo wp_login_url(); ?>' ); ?><?php }?>

</tr>
</table>

</div>

<div id="end" style="width:700px;margin:0px auto;margin-top:10px;"><img src="../../images/mybrandlogin.png" alt="My Brand Login" style="margin-top:-48px;"/><font style=font-size:10px;">My Brand Login Copyright &copy; 2009-<?php echo date('Y'); ?> Kris Jonasson. All Rights Reserved.</font></div>