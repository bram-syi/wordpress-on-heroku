<?php
/*
Plugin Name: Email Engine
Plugin URI:http: //www.seeyourimpact.com
Version: 1.1
Author: Mohd Amjed, Yosia Urip
Description: Adds the capability to organize and manage the email templates.
*/

include_once(ABSPATH.'syi.php');
include_once(ABSPATH.'wp-includes/syi/syi-functions.php');

define(EMAIL_TEMPLATE_URL_BASE, $_SERVER['DOCUMENT_ROOT'] . '/wp-content/templates/');

class EmailEngine{  

  protected $syimailer_testing = true;

  public $EMAILS = array(
   '1'=> 
   array('id'=>'thanksEmail','title'=>'Single Donation Thank You Email'
    ,'desc'=>'Notifies the donor of a gift after successful payment.')
   ,'2'=> 
   array('id'=>'updateEmail','title'=>'Single Donation Update Email'
    ,'desc'=>'Email sent to the donor when impact story is published.')
   ,'3'=> 
   array('id'=>'adminNotify','title'=>'Single Donation Admin Notification'
    ,'desc'=>'Notifies all admins when a donation has been received.')
   ,'5'=> 
   array('id'=>'aggThankEmail','title'=>'Aggregate Donation Thank You Email'
    ,'desc'=>'Notifies the donor of an aggregated gift after successful payment.')
   ,'6'=> 
   array('id'=>'aggUpdateEmail','title'=>'Aggregate Donation Update Email'
    ,'desc'=>'Email sent to the donors when aggregated impact story is published.')
   ,'7'=> 
   array('id'=>'aggAdminNotify','title'=>'Aggregate Donation Admin Notification'
    ,'desc'=>'Notifies the donor/donors of a gift after successful payment.')
   ,'4'=> 
   array('id'=>'storySubmission','title'=>'Story Submission Admin Notification'
    ,'desc'=>'Notifies all admins when an impact story has been submitted.')
   ,'11'=> 
   array('id'=>'thanksFb','title'=>'Single Donation Thank You FB Status'
    ,'desc'=>'Status update on Facebook wall after donation.')
   ,'12'=> 
   array('id'=>'updateFb','title'=>'Single Donation Update FB Status'
    ,'desc'=>'Status update on Facebook wall after impact story published.')
   ,'13'=> 
   array('id'=>'aggThanksFb','title'=>'Aggregate Donation Thank You FB Status'
    ,'desc'=>'Status update on Facebook wall after donation.')
   ,'14'=> 
   array('id'=>'aggUpdateFb','title'=>'Aggregate Donation Update FB Status'
    ,'desc'=>'Status update on Facebook wall after impact story published.')
   );

public $displayVars=array();
public $postvars=array();
function __construct(){
  global $wpdb,$current_blog,$blog_id;
  $blog_id=$current_blog->blog_id;   
  $this->postvars['blogid']=$blog_id;   
  $this->displayVars['blogid']=$blog_id;   
}
function printAdminPage()
{
  global $wpdb,$current_blog,$blog_id;


  if($_POST['emailEngineAction']=='Update'){
    $this->postvars['blogid']=$blog_id;    
    foreach($this->EMAILS as $k=>$v){
      $this->updateEmailDetails(intval($k),
        $_POST[$v['id'].'Subject'], $_POST[$v['id'].'Content']);
    }    
    if(mysql_error()){
      echo mysql_error();
    }else{
      echo "<b><font color='green'>Email updated.</font></b><br/>" ;
    }
  }else if($_POST['emailEngineAction']=='Restore Defaults'){
      //restoring email default to mail type id 1 to 7
    if($this->restoreDefault($current_blog->blog_id,array(1,2,3,4,5,6,7))){
      echo "<b><font color='red'>Problems, try again.</font></b><br/>" ;
    }else{
      echo "<b><font color='green'>Restored.</font></b><br/>" ;
    }
  }else if($_POST['emailEngineAction']=='Send'){
    $this->postvars['blogid']=$blog_id;    
    $this->sendMailSimple($_POST['blankEmailRecipient'], 
      $_POST['blankEmailRecipient'], 
      $_POST['blankEmailSubject'], 
      $_POST['freeEmailContent'], '', false);
  }
  ?>
  <div class="wrap">
    <script type="text/javascript" src="../wp-includes/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce.js"></script>
    <script type="text/javascript">
    tinyMCE.init({mode:"textareas",verify_html:false,theme:"advanced",width:"520px",height:"300px",entity_encoding:"raw",valid_elements:"*[*]"});
    </script>
    <style type="text/css">
    .emailSubject{width:520px;}
    .emailContent{width:520px;height:300px;}
    #emailEngineVariableRef td,#emailEngineNav{font-size:11px;}
    </style>
    <script type="text/javascript">
function hideAllForms(){//Hide all tabs
  <?
  foreach($this->EMAILS as $k=>$v){
    echo "jQuery('#".$v['id']."Form').hide();";
  }
  ?>
}
function showForm(formName){//Show a tab
  hideAllForms();
  jQuery('#'+formName).show();
  return false;
}
jQuery(function(){
  showForm('<?=$this->EMAILS['1']['id'].'Form'?>');
});
</script>
<form method="post" action="<?= $_SERVER['REQUEST_URI'];?>" style="width:960px;">
  <h2>Edit Email and Notification Template</h2>
  <div id="emailEngineNav">
    <?
    echo '<select onchange="showForm(this.value)" id="formSelector">';
    foreach($this->EMAILS as $k=>$v){
      echo '<option value="'.$v['id'].'Form">'.$v['title'].'</option>'; 
    }
    echo '</select> ';
    echo '<input type="button" name="update" value="Go" '
    .'onclick="showForm(jQuery(\'#formSelector\').val())" />';
    ?>
  </div>
  <div style="float:right;padding:0 20px 0 20px;width:380px;">
    <h3>Template Variables Reference List</h3>
    <ul style="font-size:11px;">
      <li><b>$TRANSACTION_DATE</b>: date/time of donation</li>
      <li><b>$GIFT_NAME</b>: name of gift donated</li>
      <li><b>$GIFT_AMOUNT</b>: amount of gift donated</li>
      <li><b>$GIFT_DESCRIPTION</b>: description of gift donated</li>
      <li><b>$FIRST_NAME</b>: first name of donor</li>
      <li><b>$DONOR_NAME</b>: name of donor</li>
      <li><b>$DONOR_EMAIL</b>: email of donor</li>
      <li><b>$CHARITY_NAME</b>: name of receiving charity</li>
      <li><b>$CHARITY_DESCRIPTION</b>: description of charity</li>
      <li><b>$CHARITY_SITE_URL</b>: charity url/web address</li>
      <li><b>$CHARITY_ADMIN_EMAIL</b>: charity admin email</li>
      <li><b>$POST_LINK</b>: link to published impact story</li>
      <li><b>$TIP_AMOUNT</b>: amount of tip</li>
      <li><b>$DONATION_AMOUNT</b>: amount of donation</li>
      <li><b>$AGGREGATE_NAME</b>: name of the agg. gift</li>
      <li><b>$AGGREGATE_QUANTITY</b>: quantity of agg. gifts</li>
      <li><b>$AGGREGATE_AMOUNT</b>: total amount of agg. gifts</li>
      <li><b>$MULTIPLE_QUANTITY</b>: if multiple, " (x2)"</li>
      <li><b>$REFER_CODE</b>: the user's personal referral code</li>
    </ul>
  </div>
  <div id="emailEngineForms">
    <?
    foreach($this->EMAILS as $k=>$v){
      list($subject,$content)=$this->getEmailDetails($blog_id,intval($k));
      echo '<div id="'.$v['id'].'Form">';
      echo '
      <h3>'.$v['title'].'</h3>
      <em>'.$v['desc'].'</em>
      <br/><br/>
      '.
      (intval($k)>10?
        '
        <strong>Content:</strong><br/>
        <input size="95" type="text" name="'.$v['id'].'Content"'
        .' value="'.htmlentities(stripslashes($content)).'"/><br/>'  
        :'
        <strong>Subject:</strong><br/>
        <input type="text" class="emailSubject" name="'.$v['id'].'Subject" value="'
        .stripslashes($subject).'"/><br/>
        <strong>Content:</strong><br/>
        <textarea class="emailContent" name="'.$v['id'].'Content">'
        .stripslashes($content).'</textarea>
        ').
      '
      <br/>
      <input type="submit" name="emailEngineAction" class="button" value="Update"/>
      &nbsp;&nbsp;
      <input type="submit" name="emailEngineAction" class="button" value="Restore Defaults"/>
      '; 
      echo '</div>';
    }
    ?>
    <div id="blankEmailForm" style="margin-top: 50px;">      
      <h3>Blank Email Form</h3>
      <em>Send any email to anybody on behalf of impact@seeyourimpact.org</em>
      <br/><br/>  
      <strong>Recipient:</strong><br/>
      <input type="text" class="emailSubject" name="blankEmailRecipient" value="y0s1a@yahoo.com"/><br/>
      <strong>Subject:</strong><br/>
      <input type="text" class="emailSubject" name="blankEmailSubject" value="Testing"/><br/>
      <strong>Content:</strong><br/>
      <textarea class="emailContent" name="freeEmailContent"><h1>Here is some HTML code.</h1></textarea>
      <br/>
      <input type="submit" name="emailEngineAction" class="button" value="Send" />
    </div>
    <br/><br/>
  </div>
</form>

</div>  
<? }

  //Get email subject and content based on email template type and blog id 
public function getEmailDetails($blog_id,$mail_id){
  $query=sprintf(
    "SELECT MAIL_SUBJECT,MAIL_CONTENT FROM EE_EMAIL_TEMPLATE "
    ."WHERE blog_id=%d AND mail_type_id=%d",(int)$blog_id,(int)$mail_id);
  $mailResult=mysql_query($query);
  $countMail=@mysql_num_rows($mailResult);
  if($countMail > 0){
    $emailTemplate=mysql_fetch_assoc($mailResult);
    return array($emailTemplate['MAIL_SUBJECT'],
      $emailTemplate['MAIL_CONTENT']);
  }else if($blog_id == 1){
    return null;
  }else{
    return $this->getEmailDetails(1,$mail_id);
  }
}

  //Update email template table
public function updateEmailDetails($mailType,$subject,$body){
  $blog_id =(int)$this->postvars['blogid'];
  $check=mysql_query(sprintf("SELECT * FROM EE_EMAIL_TEMPLATE "
    ."WHERE blog_id=%d AND mail_type_id =%d",$blog_id,$mailType));
  if(mysql_num_rows($check)>0){
    //If already exist, update old row
    $query=sprintf("UPDATE EE_EMAIL_TEMPLATE SET "
      ."MAIL_SUBJECT='%s',MAIL_CONTENT='%s' "
      ."WHERE blog_id='%d' AND mail_type_id ='%d'",
      mysql_real_escape_string($subject), 
      mysql_real_escape_string($body),
      $blog_id,
      $mailType);
    if(mysql_query($query)===FALSE){
      echo '<pre>'.htmlentities($query).'</pre>'; exit();
    }
  }else{
    //If not exist, insert a new one
    mysql_query(sprintf("INSERT INTO EE_EMAIL_TEMPLATE(blog_id,"
      ."MAIL_SUBJECT,MAIL_CONTENT,mail_type_id)".
    "values(%d,'%s','%s','%d')",
    $blog_id,
    mysql_real_escape_string($subject),
    mysql_real_escape_string($body),
    $mailType));
  }     
}
public function restoreDefault($blogId=null,$mailType=array()){
  if($blogId==null || intval($blogId)==0)
    return -1;
  foreach($mailType as $type){
    $type=intval($type);
    if($type!=0){
      list($subject,$body)=$this->getEmailDetails(1,$type);
      $this->updateEmailDetails($type,$subject,$body);
    }
  }
  return 0;
}

function get_facebook_message($typeID, $donationID = 0, $postID = 0){
  global $wpdb;
  $d = NULL; $dg = NULL; $dd = NULL; $agg_dg = NULL;

  if($donationID > 0){
    $d = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM donation WHERE donationID = %d",$donationID
      ), ARRAY_A);  
    if($d != NULL) {
      $dg = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM donationGifts "
        ."LEFT JOIN gift ON donationGifts.giftID = gift.id "
        ."WHERE donationID = %d AND gift.ID > 10",
        $donationID
        ), ARRAY_A);

      $dd = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM donationGiver WHERE ID = %d",$d['donorID']
        ), ARRAY_A);
      if($dg != NULL){    
        if($dg['towards_gift_id']>0){
          $agg_dg = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM gift "
            ."WHERE gift.ID = %d ",
            $dg['towards_gift_id']), ARRAY_A); 
        }
      } else {return;}
    } else {return;}
  } else {return;}

  //type 11: Single Donation, Thank You Note
  //type 12: Single Donation, Impact Update
  //type 13: Aggregate Donation, Thank You Note
  //type 14: Aggregate Donation, Impact Update
  
  if($d!=NULL && $dg!=NULL && ($typeID == 11 || $typeID == 12
    || $typeID == 13 || $typeID == 14)){   

  //echo print_r($agg_dg,true); exit ();
  //echo 'Hello world then exit! '.$donationID; exit();

  //Values for template
    $keys=array('$TRANSACTION_DATE','$GIFT_NAME','$GIFT_AMOUNT',
      '$GIFT_DESCRIPTION','$DONOR_NAME',
      '$DONOR_EMAIL','$CHARITY_NAME',
      '$CHARITY_DESCRIPTION',
      '$CHARITY_SITE_URL',
      '$CHARITY_ADMIN_EMAIL',
      '$POST_LINK',
      '$DONATION_AMOUNT','$DONATION_DUMP',
      '$GIFT_DUMP','$TIP_AMOUNT',
      '$AGGREGATE_NAME','$AGGREGATE_QUANTITY','$AGGREGATE_AMOUNT');
  
  $values=array($d['donationDate'],as_html($dg['displayName']),as_money($dg['amount']),
    as_html($dg['excerpt']),as_html($dd['firstName']),
    $dd['email'],as_html(stripslashes(get_blog_option($dg['blog_id'],'blogname'))),
    as_html(stripslashes(get_blog_option($dg['blog_id'],'blogdescription'))),
    get_blog_option($dg['blog_id'],'siteurl'),
    get_blog_option($dg['blog_id'],'admin_email'),
    get_blog_option($dg['blog_id'],'siteurl').'?p='.$postID,
    $d['amount'],var_export($d,true),
    var_export($dg,true),as_money($d['tip']),
    $agg_dg['displayName'],intval($agg_dg['unitAmount']/$dg['unitAmount']),as_money($agg_dg['amount']));

  list ($subject,$content) = $this->getEmailDetails($dg['blog_id'],$typeID);
  $content = str_replace($keys,$values,stripslashes($content));
  
  return $content;
  
  //return str_replace($search,$replace,$raw_text);
}
}


//API for sending email
// $via_syimailer: this is the empty string by default, otherwise it is a non-empty string
// has the reason for the email (ie, "donationthankyou"), and this is passed along via the
// email "X-Tag" header for critsend tracking purposes
public function sendMail($email, $blogid, $mailType, $aggMailType,
  $items, $postID, $ccList, $mailSubject='', $mailContent='', $moreMsg='', $resend = false, $via_syimailer = '') {

  global $wpdb;
  global $id;
  global $phpmailer;

  try {
  // Require a blogid
    if (!isset($blogid)) { return false; }

    if (!empty($items)) { 
      list($donationId, $i) = explode('/', $items);
    }

  // Check whether we even need to send this mail.
    switch ($mailType) {
    case 2: case 6: // Both types of thank you are equivalent
    $mm = "(mailType=2 OR mailType=6)"; break;
    default:
    $mm = "mailType=$mailType"; break;
  }
  $sql = $wpdb->prepare(
    "SELECT COUNT(1) FROM notificationHistory
    WHERE donationID=%d AND postID=%d AND blogID=%d AND success=1 AND $mm",
    $donationId,$postID, $blogid);
  $upd = intval($wpdb->get_var($sql));

  //Do not send the same mail twice UNLESS forced to resend
  if (notification_has_been_sent($mailType, $donationId, $postID, $blogid) && !$resend) 
    return false;  // Note: This leaves no record of the mail having been tried.

  syi_init_phpmailer();
  $mail = $phpmailer;
  
  //Init variables
  $this->postvars['blogid']=$blogid;
  $this->postvars['email_type']=(int)$mailType;
  $donorID=0; $donorName=NULL; $donorEmail=NULL;
  $giftId=NULL; $giftName=NULL; $giftUnitAmount=NULL; $giftDescription=NULL;
  $transactionDate=NULL; $tipAmount=0;
  $donatedGift=NULL; $donationId = 0; $itemId = 0;
  $postLink = ""; $postLinkLong = ""; 
  $postObject = NULL; $postTitle = ''; $postContent = ''; 
  $postImages = NULL; $postImage = '';
  $giftLink = "";
  $tweetText = "";
  $aggGiftName = '';
  $aggGiftQuantity = '';
  $aggGiftUnitAmount = '';

  
  //////////////////////////////////////////////////////////////////////////////

  if (!empty($items)) { 
    list($donationId, $items) = explode('/', $items);
    $donationId = intval($donationId);
    $items = as_ints($items);

    $donation = Syi::get_donation($donationId);
    if (!empty($donation)) {
      //Donation details here
      $transactionDate = $donation['donationDate'];
      $donationAmount = $donation['donationAmount_Total'];
      $tipAmount = $donation['tip'];
      $gifts = Syi::get_giftsOfDonation($donationId);
      if (count($items) == 0)
        $items = $gifts;
      $quantity = count($items); // just the selected items
      // the total items in the donation (so we do the right math below)
      $totalQuantity = count($gifts); 
      $donatedGift = Syi::get_gift_from_item($items[0]);
      if (!empty($donatedGift)) {
        $giftId = $donatedGift->id;
        $giftName = stripslashes($donatedGift->displayName);
        $giftUnitAmount = $donatedGift->unitAmount;
        $giftDescription = stripslashes($donatedGift->excerpt);

        if(intval($donatedGift->towards_gift_id) > 0 && $giftUnitAmount > 0){
          $mailType=$aggMailType;
          $aggGift = Syi::get_gift($donatedGift->towards_gift_id);
          $aggGiftName = stripslashes($aggGift->displayName);
          $aggGiftUnitAmount = $aggGift->unitAmount;
          $aggGiftQuantity = intval($aggGiftUnitAmount/$giftUnitAmount);
        }
      }

      $donationAmount= as_money($donationAmount);
      $donorID = $donation['donorID'];
      $donor = Syi::get_donor($donorID);
      if (!empty($donor)) {
        $user_id = $donor['user_id'];
        $donorName = $donor['firstName'] . ' ' . $donor['lastName'];
        $firstName = $donor['firstName'];
        $donorEmail = $donor['email'];

        if ($user_id > 0) {
          $data = get_userdata($user_id);
          if(trim($data->first_name)!='' && trim($data->last_name)!='') {
            $donorName = "$data->first_name $data->last_name";
            $firstName = $data->first_name;
          } 

          if(is_email($data->user_email))
            $donorEmail = $data->user_email;
        }
      }
    }
  }

  //Get DB email template(subject and content)
  list ($subject,$content) = $this->getEmailDetails($blogid,$mailType);

  $mailSubject = ($mailSubject==''?$subject:$mailSubject);
  $mailContent = $content . $mailContent;
  $mailContent = stripslashes($mailContent); //Remove escape slashes

  //Get email recipient(s)  

  //Prevent dummy email from being sent
  $real_email = '';
  if (strpos($email,'.seeyourimpact.com')!==FALSE)
    $real_email = get_site_option("payments_email");
  if (strpos($real_email,'.seeyourimpact.com')!==FALSE)
    $real_email = '';
  else
    $real_email = $email;

  if (!empty($real_email))
    $mail->AddAddress($real_email,'');

  foreach (as_ints($ccList) as $cc) {
    $cc = new WP_User($cc);
    if(strpos($cc->user_email,'.seeyourimpact.com')===FALSE)
      $mail->AddCc($cc->user_email, "$cc->first_name $cc->last_name");
  }

  $blog_dom = get_blog_domain($blogid);

  if (is_avg($giftId)) $avg = true;
  else $avg = false; 

  //Get charity details
  $vars['CHARITY_NAME'] = as_html(get_blog_option($blogid, 'blogname'));
  $vars['CHARITY_LINK'] = get_site_url($blogid);
  $vars['CHARITY_DESCRIPTION'] = as_html(get_blog_option($blogid, 'blogdescription'));
  $vars['CHARITY_SITE_URL'] = get_blog_option($blogid, 'siteurl');
  $vars['CHARITY_ADMIN_EMAIL'] = get_blog_option($blogid,'admin_email');
  $vars['CHARITY_IMAGE'] = get_site_url(1, "/wp-content/charity-images/charity-$blog_dom.jpg");

  //Get post details
  if (!empty($postID)) { 
    get_post_for_email($postID, $postLinkLong, $postTitle, $postContent, $postImage);
    $postLink = wp_get_shortlink($postID);
  }

  if ($quantity > 1)
    $vars['MULTIPLE_QUANTITY'] = " (x$quantity)";
  else
    $vars['MULTIPLE_QUANTITY'] = "";

  // Referral code
  $vars['REFER_CODE'] = get_refcode_from_donor($donorID);
  $vars['TRANSACTION_DATE'] = $transactionDate;

  if(!$avg)
    $vars['GIFT_NAME'] = as_html($giftName);
  else 
    $vars['GIFT_NAME'] = str_replace(AVG_NAME_PREFIX, as_money($donatedGift->amount)." for ", as_html($giftName));

  $vars['GIFT_AMOUNT'] = as_money($giftUnitAmount);
  $vars['GIFT_DESCRIPTION'] = as_html($giftDescription);
  $vars['GIFT_LINK'] =  pay_link($giftId, "/m");
  $vars['GIFT_TITLE'] = $vars['CHARITY_DESCRIPTION'];
  $vars['GIFT_DESC'] =  '<strong>For only ' . as_money($giftUnitAmount) ."</strong>: " . $vars['GIFT_DESCRIPTION'];

  $vars['DONOR_NAME'] = as_html($donorName);
  $vars['FIRST_NAME'] = as_html($firstName);
  $vars['DONOR_EMAIL'] = $donorEmail;

  $vars['POST_LINK'] = '<a href="'.esc_attr($postLinkLong).'">'.esc_html($postLinkLong).'</a>';
  $vars['POST_TITLE'] = $postTitle;
  $vars['POST_CONTENT'] = $postContent;
  $vars['POST_IMAGE'] = $postImage;

  $vars['DONATION_AMOUNT'] = $donationAmount;
  $vars['TIP_AMOUNT'] = as_money($tipAmount);

  $vars['AGGREGATE_NAME'] = $aggGiftName;

  if(!$avg)
    $vars['AGGREGATE_QUANTITY'] = $aggGiftQuantity;
  else
    $vars['AGGREGATE_QUANTITY'] = as_money($aggGiftUnitAmount);
  
  $vars['AGGREGATE_AMOUNT'] = as_money($aggGiftUnitAmount);
  
  $vars['DONATION_DUMP'] = var_export($donation,true);
  $vars['GIFT_DUMP'] = var_export($donatedGift,true);

  $keys = array();
  $values = array();
  foreach ($vars as $k=>$v) {
    $keys[] = '$' . $k;
    $values[] = $v;
  }
  
  //Replace all the template variable's occurences with the corresponding values
  $mailContent = str_replace($keys,$values,$mailContent);
  $mailSubject = str_replace($keys,array_map('html_entity_decode', $values),$mailSubject);
  if ($moreMsg!='') { $mailContent .= $moreMsg; }

  ////////////////////////////////////////////////////////////////////////////// 
  
  $mail->Subject = $mailSubject;

  //////////////////////////////////////////////////////////////////////////////
  
  //Get HTML body template
  if($mailType == 1 || $mailType == 5) {
    $htmlFilePath = EMAIL_TEMPLATE_URL_BASE . 'donation_thankyou.html';
    $postLink = $charitySiteUrl;
    $postTitle = "I just donated $giftName at http://SeeYourImpact.org. Meet the life YOU change!";
  } else if($mailType == 2 || $mailType == 6) {
    $htmlFilePath = EMAIL_TEMPLATE_URL_BASE . 'donation_story.html';
  } else if($mailType == 3 || $mailType == 7 || $mailType == 4) { 
    $htmlFilePath = EMAIL_TEMPLATE_URL_BASE . 'donation_admin.html';  
  }
  
  // this is the main body of the email, before we apply emailEngine's templating
  // around it
  $inner_content = $mailContent;

  $bodyTemplate = file_get_contents($htmlFilePath);
  if ($bodyTemplate !== false) {
    $vars['MESSAGE'] = $mailContent;
    $vars['POST_LINK'] = $postLink;
    $vars['SHARE_TITLE_ENCODED'] = urlencode($postTitle);
    $vars['SHARE_LINK_ENCODED'] = urlencode($postLink);
    $vars['PATH'] = str_replace($_SERVER['DOCUMENT_ROOT'], get_blog_option(1,'siteurl'), EMAIL_TEMPLATE_URL_BASE);

    $keys = array();
    $values = array();
    foreach ($vars as $k=>$v) {
      $keys[] = "#$k#";
      $values[] = $v;
    }
    
    $mailContent = str_replace($keys, $values, $bodyTemplate);

  } else {
    echo $htmlFilePath;
    echo 'Cannot read HTML body template file.';
    exit();
  }
  $mailContent = xml_entities($mailContent);

  $mail->MsgHTML($mailContent);

  //Convert HTML content to text for the ALT content of the email
  $h2t = &new html2text(preg_replace('/<a[^>]*>\s*<\/a>/i', '',
    strip_tags($mailContent,'<a><br><p><div><br/><h1><h2><h3><table><tr>')));
  $mail->ContentType = "text/html";
  $mail->AltBody = $h2t->get_text();

  //////////////////////////////////////////////////////////////////////////////

  //Increase update counter 

  $sql = $wpdb->prepare("INSERT INTO notificationHistory "
    . "(mailType,donorID,donationID,postID,blogID,sentDate,success,emailTo,emailSubject,emailText) " 
    . "VALUES(%d,%d,%d,%d,%d,NOW(),0,'%s','%s','%s')",
    $mailType, $donorID, $donationId, $postID, $blogid, $email, $mailSubject, $mailContent);
  $wpdb->query($sql) or die('Error inserting donation Gifts details');
  $n = $wpdb->insert_id;

  //////////////////////////////////////////////////////////////////////////////

  // For live site mails, BCC the mails-sent alias

  $mail->AddBCC(get_email_address("mails-sent"));
  if (is_live_site() && ($mailType == 3 || $mailType == 7)) {
    $mail->AddCC("donationnotify$blog_dom@seeyourimpact.org");
  }

  if(!empty($real_email)) {   
    $sent = $mail->Send();
    if ($via_syimailer) {
      $this->send_via_syimailer(
        $mail,
        $via_syimailer,
        $inner_content,
        $vars
        );
    }
    $sent = true;
  }

  if(!$sent){
    debug($mail->ErrorInfo."\n=====".print_r($mail,true), true, "Mail Send Failure");
    $wpdb->query($wpdb->prepare("UPDATE notificationHistory SET success=0,error=%s WHERE notificationID=$n", $mail->ErrorInfo));
  } else {
    $wpdb->query("UPDATE notificationHistory SET success=1 WHERE notificationID=$n");
  }

} catch (Exception $e) {
  debug('ERROR: '.$e->getMessage(),true,"CATCH EMAIL ERROR (emailEngine.php)");
}
return true;
}

public function send_via_syimailer($mail, $email_type, $content, $vars=array()) {
  $unsubscribe = $email_type == 'donationthankyou' ? 1 : 0;

  if ($this->syimailer_testing) {
    $to = $mail->GetToAddresses();
    $list = "<div style=\"color:#888; font-size:10px; background:#f0f0f0; margin:10px; padding:10px; border:2px solid #ccc;\"><pre>";
    $list .= "\noriginal recipient count: ".count($to)."\n";
    foreach ($to as $t) {
      $list .= "- ".htmlentities($t)."\n";
    }
    $list .= "</pre></div>";

      // prepend this <ul> to the content
    $content = "$content$list";
  }

  $template = 'clean';
  if ($email_type == 'storyready') {
    $template = 'storyready';
  }

  SyiMailer::send(
    $this->syimailer_testing ? 'devs@seeyourimpact.org' : $mail->GetToAddresses(),
    $this->syimailer_testing ? "TEST: $mail->Subject" : $mail->Subject,
    $template,
    array(
      'From' => 'SeeYourImpact.org <impact@seeyourimpact.org>',
      'X-Tag:' => 'why:direct,email:'.$email_type,
      ),
    array_merge(
      array(
        'content' => $content,
        'show_unsubscribe' => $unsubscribe,
        ),
      $vars
      )
    );
}

public function sendMailSimple($name, $email, $subject='', $vars='', $template='',
  $debug=false, $readyContent=false, $via_syimailer=false){

  global $phpmailer;
  syi_init_phpmailer();
  $mail = $phpmailer;

  $mail->AddAddress($email,$name);
  $mail->Subject = $subject;

  if (!$readyContent) {
          //Get HTML body template

    $htmlFilePath = EMAIL_TEMPLATE_URL_BASE.($template != '' ? $template : 'syi.html');
    $bodyTemplate = file_get_contents($htmlFilePath);

          // this is the main body of the email, before we apply emailEngine's templating
          // around it
    $inner_content = '';

    if($bodyTemplate !== false){
      if(is_array($vars) && count($vars)>0){
        $vars['#PATH#'] = str_replace($_SERVER['DOCUMENT_ROOT'],
          get_blog_option(1,'siteurl'),
          EMAIL_TEMPLATE_URL_BASE
          );
        $vars['#SHARE_TITLE_ENCODED#'] = urlencode('Choose a cause, change a life at SeeYourImpact.org');
        $vars['#SHARE_LINK_ENCODED#'] = urlencode('http://seeyourimpact.org');
        $mailContent = str_replace(array_keys($vars), array_values($vars), $bodyTemplate);

              // TODO: wtf do we do with SyiMailer here

              // we can be str_replacing any old random set of variables into
              // any old random template, which I thought was the purpose of
              // sendMail() above, but I guess it's cool to have two different
              // ways to do the same thing, too

        error_log('sendEmailSimple: no idea what this code path is for: '.var_export(func_get_args(),1));
        trace_up();
        $via_syimailer = "unknown";
        $inner_content = $mailContent;
      } else {
        $vars = str_replace("\xc2"," ",$vars);
        $inner_content = $vars;
        $mailContent = str_replace(
          array('#MESSAGE#','#PATH#'),
          array($vars,
            str_replace($_SERVER['DOCUMENT_ROOT'],
              get_blog_option(1,'siteurl'),
              EMAIL_TEMPLATE_URL_BASE
              )
            ),
          $bodyTemplate
          );
      }
    }else{
      $mailContent = print_r($vars,true);
      $inner_content = $mailContent;
    }
  } else {
    $mailContent = $template;
    $inner_content = $mailContent;
  }

  $mail->MsgHTML(utf8_encode(xml_entities($mailContent)));

      //Convert HTML content to text for the ALT content of the email
  $h2t = &new html2text($mailContent);
  $mailContentText = $h2t->get_text();
  $mail->ContentType = "text/html";
  $mail->AltBody = $mailContentText;

  if(!empty($email) && strpos($email,'.seeyourimpact.com')===FALSE) {
      //Sending email
    $mail->Send();

    if ($via_syimailer) {
      $this->send_via_syimailer($mail, $via_syimailer, $inner_content);
    }
  }
}
}

require_once('post_notification.php');
$emailEngine=new EmailEngine();
	
function add_email_engine_admin_panel(){
	global $emailEngine, $blog_id;

	add_menu_page('E-mails','E-mails',9,__FILE__,array(&$emailEngine,'printAdminPage'), null, 15);
}
function printAdminPage()
{
	if(isset($emailEngine))
	$emailEngine->printAdminPage();
}
add_action('admin_menu','add_email_engine_admin_panel');

function createAggregatedThankYouMail()
{
    global $wpdb,$current_blog,$blog_id,$emailEngine;
    
    list($subject, $body) = $emailEngine->getEmailDetails($blog_id, 1);
    $emailEngine->updateEmailDetails(5, $subject, $body);
}
