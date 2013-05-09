    <a rel="nofollow" href="<?php site_url('/wp-login.php'); ?>/wp-login.php" target="_blank"><img src="<?php echo plugins_url('my-brand/loader/images/mybrandlogin.png'); ?>" alt="" style="float:right;margin-top:-120px;margin-bottom:-6px;margin-right:104px;"/></a>
  <div class="left">
<?php
if(isset($_POST['upload']))
{
    $uploaddir = '../wp-content/plugins/my-brand/loader/templates/uploads/';
    foreach ($_FILES["load"]["error"] as $key => $error)
    {
        if ($error == UPLOAD_ERR_OK)
        {
            $tmp_name = $_FILES["load"]["tmp_name"][$key];
            $name = $_FILES["load"]["name"][$key];
            $uploadfile = $uploaddir . basename($name);
 
            if (move_uploaded_file($tmp_name, $uploadfile))
            {
                echo "<div id='message' style='font-size:9px;'><b style='color:#468b5d;'>Successful Upload.</b> <em>../wp-content/plugins/my-brand/loader/templates/uploads/".$name."</em></div><br/>";
            }
            else
            {
                echo "<div id='message' style='font-size:9px;'><b style='color:#b63e46;'>Error Uploading</b> <em>".$name."</em>!! If Problem Persists Report It.</div><br/>";
            }
        }
    }
}
?>
      <div class="usual">
<ul class="idTabs"> 
  <li class="krs"><a href="#logo">Logo</a></li> 
  <li class="krs"><a href="#bgclr">BG Color</a></li> 
  <li class="krs"><a href="#bgpic">BG Picture</a></li> 
  <li class="krs"><a href="#topclr">Label Color</a></li> 
  <li class="krs"><a href="#lgnpic">Login Box</a></li> 
  <li class="krs"><a href="#lrclr">Links</a></li> 
  <li class="krs"><a href="#tempupload">Template</a></li>  
</ul>