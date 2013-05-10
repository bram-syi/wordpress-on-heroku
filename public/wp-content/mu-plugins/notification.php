<?
/*
Plugin Name: Notifications
Plugin URI: http://seeyourimpact.org/
Version: 1.0
Author: Yosia Urip
Description: email notification settings
Author URI: http://seeyourimpact.org/
Instructions:
*/

include_once(ABSPATH.'syi.php');
include_once(ABSPATH.'wp-includes/syi/syi-functions.php');
include_once(ABSPATH.'wp-content/plugins/wp-mail-smtp/wp_mail_smtp.php');

add_action('admin_menu', 'notifications_add_menu', SW_HOME_PRIORITY);

//mail queue priority
define(MAIL_GCSEND_PRIORITY,0); // repeated
define(MAIL_ADMIN_MATCHINFO_PRIORITY,1); //one time
define(MAIL_ADMIN_GCBUY_PRIORITY,2); // repeated
define(MAIL_ADMIN_FUND_PRIORITY,3); //repeated
define(MAIL_ADMIN_GIFTBUY_PRIORITY,4);  //repeated
define(MAIL_ADMIN_STOCK_PRIORITY,5); //repeated

////////////////////////////////////////////////////////////////////////////////

function notifications_add_menu() {
  add_submenu_page('site-config',
    __('Notifications', 'notifications'),
    __('Notifications', 'notifications'),
    'manage_network', 'notifications', 'notifications_page');
}

function notifications_page() {
  global $wpdb;

?>
<script type="text/javascript" src="../wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
/*
tinyMCE.init({mode:"textareas",verify_html:false,theme:"advanced",
width:"400px",height:"200px",entity_encoding:"raw",valid_elements:"*[*]"});
*/
</script>
<script type="text/javascript" src="../wp-content/themes/syi/jquery.tablesorter.min.js"></script>
<style type="text/css">
  h3{margin:1em 0 0.5em;}
  input[type=text]{width:300px;}
  .email-tpl{width:610px; height:140px; font-size:11px;}
  .email-tpl-sect{width:300px; height:90px; font-size:11px;}
  .field_title{float:left; width:150px;}
  .row_cell{padding: 1px 5px;}
  .row_cell .debug { color: #aaa; }
  #allocate-form .acct-type { display: none; }
  .subtab table th { cursor: pointer; }
  .row{font-size:11px; border-top:1px solid #888;}
  .odd{background:#eee;}
  .errorMsg{font-weight:bold; color:#c00;}
  .tbl_header{background: #333; color: #ccc}
  .header_row{font-weight:bold;text-align:left;}
  .subtabs { border-bottom: 1px solid black; margin-top: 20px; }
  a.subtab { background: #f0f0f0; display: block; width: 150px; float:left; padding: 3px; border: 1px solid white; text-align: center; margin: 2px 5px; }
  form.subtab { clear: both; display: none; padding: 15px; }
  form.selectedTab { display: block; }
  a.selectedTab { font: bold 12pt Arial; border-bottom: 0px none; text-decoration: none; color:black; margin-bottom: -2px; padding-bottom: 6px;  background-color: white; border:1px solid black; border-bottom: 1px solid white;}
  .clear { clear: both; }
  #thankyou-form div{ float:left; margin-right:10px; }
  #thankyou-form br {clear:both;}  
  .tag-list { background:#EFC; padding:10px; font-size:10px; width:590px; }
  .tag-list .tag { font-weight: bold; }
</style>
<div class="wrap">
<h2>Notifications Settings</h2>
<?

////////////////////////////////////////////////////////////////////////////////

  //Get requests for thank you email

  if (isset($_POST['notify_merged_thankyou'])) { update_blog_option(1, 'notify_merged_thankyou', ($_POST['notify_merged_thankyou']==1?1:0)); }
  $notify_merged_thankyou = stripslashes(get_blog_option(1, 'notify_merged_thankyou'));

  $nt_prefix = 'notify_thankyou_';

  $nt_opts = array(
    'main','giftinfo','sglgiftinfo','agggiftinfo','vargiftinfo','tipinfo','gcinfo','campaign_note','matching_note',
    'gift_tpl','agggift_tpl','vargift_tpl','gc_tpl','profile','contact','taxinfo','subject','style'
  );

  $nt = array();

  foreach ($nt_opts as $opt) {
    $opt_name = $nt_prefix.$opt;
    if(isset($_POST[$opt_name])) update_blog_option(1, $opt_name, $_POST[$opt_name]);
    $nt[$opt] = stripslashes(get_blog_option(1, $opt_name));
  }

////////////////////////////////////////////////////////////////////////////////

if ($_POST['submit'] == 'Save Changes') {
  if (isset($_POST['ic_subject'])) { update_blog_option(1, 'ic_subject', $_POST['ic_subject']); }
  if (isset($_POST['ic_main_content'])) { update_blog_option(1, 'ic_main_content', $_POST['ic_main_content']); }
  if (isset($_POST['ic_instructions'])) { update_blog_option(1, 'ic_instructions', $_POST['ic_instructions']); }
  if (isset($_POST['ic_fineprints'])) { update_blog_option(1, 'ic_fineprints', $_POST['ic_fineprints']); }
  if (isset($_POST['ic_card_content'])) { update_blog_option(1, 'ic_card_content', $_POST['ic_card_content']); }
  if (isset($_POST['ic_code_link'])) { update_blog_option(1, 'ic_code_link', $_POST['ic_code_link']); }
  if (isset($_POST['ic_top_content'])) { update_blog_option(1, 'ic_top_content', $_POST['ic_top_content']); }
  if (isset($_POST['ic_share_title'])) { update_blog_option(1, 'ic_share_title', $_POST['ic_share_title']); }
  if (isset($_POST['ic_share_link'])) { update_blog_option(1, 'ic_share_link', $_POST['ic_share_link']); }
}

  $ic_subject = stripslashes(get_blog_option(1, 'ic_subject'));
  $ic_main_content = stripslashes(get_blog_option(1, 'ic_main_content'));
  $ic_instructions = stripslashes(get_blog_option(1, 'ic_instructions'));
  $ic_fineprints = stripslashes(get_blog_option(1, 'ic_fineprints'));
  $ic_card_content = stripslashes(get_blog_option(1, 'ic_card_content'));
  $ic_code_link = stripslashes(get_blog_option(1, 'ic_code_link'));
  $ic_top_content = stripslashes(get_blog_option(1, 'ic_top_content'));
  $ic_share_title = stripslashes(get_blog_option(1, 'ic_share_title'));
  $ic_share_link = stripslashes(get_blog_option(1, 'ic_share_link'));
  
////////////////////////////////////////////////////////////////////////////////

if ($_POST['submit'] == 'Save Changes') {
  if (isset($_POST['notify_taxinfo_main'])) { update_blog_option(1, 'notify_taxinfo_main', $_POST['notify_taxinfo_main']); }
  if (isset($_POST['notify_taxinfo_subject'])) { update_blog_option(1, 'notify_taxinfo_subject', $_POST['notify_taxinfo_subject']); }
  if (isset($_POST['notify_taxinfo_style'])) { update_blog_option(1, 'notify_taxinfo_style', $_POST['notify_taxinfo_style']); }
}

  $notify_taxinfo_main = stripslashes(get_blog_option(1, 'notify_taxinfo_main'));
  $notify_taxinfo_subject = stripslashes(get_blog_option(1, 'notify_taxinfo_subject'));
  $notify_taxinfo_style = stripslashes(get_blog_option(1, 'notify_taxinfo_style'));

////////////////////////////////////////////////////////////////////////////////

if ($_POST['submit'] == 'Save Changes') {
  if (isset($_POST['profile_invite_subject'])) { update_blog_option(1, 'profile_invite_subject', $_POST['profile_invite_subject']); }
  if (isset($_POST['profile_invite_content'])) { update_blog_option(1, 'profile_invite_content', $_POST['profile_invite_content']); }
  if (isset($_POST['profile_invite_personal'])) { update_blog_option(1, 'profile_invite_personal', $_POST['profile_invite_personal']); }
}

  $profile_invite_subject = stripslashes(get_blog_option(1, 'profile_invite_subject'));
  $profile_invite_content = stripslashes(get_blog_option(1, 'profile_invite_content'));
  $profile_invite_personal = stripslashes(get_blog_option(1, 'profile_invite_personal'));

  if (isset($_POST['campaign_invite_subject'])) { update_blog_option(1, 'campaign_invite_subject', $_POST['campaign_invite_subject']); }
  if (isset($_POST['campaign_invite_content'])) { update_blog_option(1, 'campaign_invite_content', $_POST['campaign_invite_content']); }
  if (isset($_POST['campaign_invite_personal'])) { update_blog_option(1, 'campaign_invite_personal', $_POST['campaign_invite_personal']); }

  $campaign_invite_subject = stripslashes(get_blog_option(1, 'campaign_invite_subject'));
  $campaign_invite_content = stripslashes(get_blog_option(1, 'campaign_invite_content'));
  $campaign_invite_personal = stripslashes(get_blog_option(1, 'campaign_invite_personal'));

////////////////////////////////////////////////////////////////////////////////

  if ($_POST['submit'] == 'Resend Notification' || $_POST['submit'] == 'Preview Email') { 
    $code = $_POST['ic_code_lookup'];
    $da = get_donation_account(get_acct_id_by_code($code));
    $da_params = json_decode($da->params,true);    
  }

////////////////////////////////////////////////////////////////////////////////

  //Get requests for tax info email
  
  if (isset($_POST['Send Email'])) { }
  if (isset($_POST['Preview Email'])) { }

////////////////////////////////////////////////////////////////////////////////
  $n = new Notification(); 

?> 
  <div class="subtabs">
    <a class="subtab selectedTab" id="thankyou_email" href="#thankyou_email">Thank You Email</a>
    <a class="subtab" id="impactcard_email" href="#impactcard_email">Impact Card Email</a>
    <a class="subtab" id="impactcard_resend" href="#impactcard_resend">IC Resend</a>
    <a class="subtab" id="invite_email" href="#invite_email">Invite Email</a>
    <a class="subtab" id="unsubscribed_list" href="#unsubscribed_list">Unsubscribed List</a>
<!--
    <a class="subtab" id="taxinfo" href="#">Tax Info Email</a>
-->
    <div class="clear"></div>
  </div>
  <div class="errorMsg"></div>
  <form method="post" class="subtab selectedTab" id="thankyou_email-form" action="admin.php?page=notifications#thankyou_email">
    <p>Merged thank you email is: <br/>
    <input type="radio" name="notify_merged_thankyou" value="1" <?=($notify_merged_thankyou==1?'checked="checked"':'')?> /> ON &nbsp;
    <input type="radio" name="notify_merged_thankyou" value="0" <?=($notify_merged_thankyou==1?'':'checked="checked"')?> /> OFF</p>
    <h2>Thank You Email Template</h2>

    <div><h3>Subject</h3><input type="text" name="<?=$nt_prefix?>subject" value="<?=$nt['subject']?>"/></div><br />
    <div><h3>Default CSS</h3><input type="text" name="<?=$nt_prefix?>style" value="<?=$nt['style']?>"/></div><br />

    <br/><div class="tag-list">
    General tags: <br/>    
    <? foreach ($n->body_tpl_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->body_tpl_tags_desc[$k].'<br/>'; } ?>
    Section tags: <br/>
    <? foreach ($n->section_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->section_tags_desc[$k].'<br/>'; } ?>
    </div><br/>
    <div><h3>Main Section</h3><textarea class="email-tpl" name="<?=$nt_prefix?>main"><?=$nt['main']?></textarea></div><br />

    <div><h3>Gift Section Info</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>giftinfo"><?=$nt['giftinfo']?></textarea></div>
    <div><h3>Single Gift Section Info</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>sglgiftinfo"><?=$nt['sglgiftinfo']?></textarea></div><br />
    <div><h3>Agg. Gift Section Info</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>agggiftinfo"><?=$nt['agggiftinfo']?></textarea></div>
    <div><h3>Var Gift Section Info</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>vargiftinfo"><?=$nt['vargiftinfo']?></textarea></div><br />

    <br/><div class="tag-list">Tip tags: <br/>
    <? foreach ($n->tip_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->tip_tags_desc[$k].'<br/>'; } ?>
    </div><br/>

    <div><h3>Tip Section Info</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>tipinfo"><?=$nt['tipinfo']?></textarea></div>
    <div><h3>GC Section Info</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>gcinfo"><?=$nt['gcinfo']?></textarea></div><br /><br />

    <br/><div class="tag-list">Available single/aggregate/variable/card gift item tags: <br/>
    <? foreach ($n->gift_tpl_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->gift_tpl_tags_desc[$k].'<br/>'; } ?>
    </div><br/>
    <div><h3>Gift Item Template</h3><textarea class="email-tpl" name="<?=$nt_prefix?>gift_tpl"><?=$nt['gift_tpl']?></textarea></div><br />
    <div><h3>Agg. Gift Item Template</h3><textarea class="email-tpl" name="<?=$nt_prefix?>agggift_tpl"><?=$nt['agggift_tpl']?></textarea></div><br />
    <div><h3>Var Gift Item Template</h3><textarea class="email-tpl" name="<?=$nt_prefix?>vargift_tpl"><?=$nt['vargift_tpl']?></textarea></div><br /><br />
    <div><h3>Impact Card Item Template</h3><textarea class="email-tpl" name="<?=$nt_prefix?>gc_tpl"><?=$nt['gc_tpl']?></textarea></div><br /><br />

    <br/><div class="tag-list">Campaign note (after each gift item if campaign gift) tags: <br/>
    <? foreach ($n->campaign_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->campaign_tags_desc[$k].'<br/>'; } ?>
    </div><br/>
    <div><h3>Campaign Note</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>campaign_note"><?=$nt['campaign_note']?></textarea></div><br />

    <br/><div class="tag-list">Matching note (after each gift item if matched gift) tags: <br/>
    <? foreach ($n->matching_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->matching_tags_desc[$k].'<br/>'; } ?>
    </div><br/>
    <div><h3>Matching Note</h3><textarea class="email-tpl-sect" name="<?=$nt_prefix?>matching_note"><?=$nt['matching_note']?></textarea></div><br />

    <div><h3>Profile Section Template</h3><textarea class="email-tpl" name="<?=$nt_prefix?>profile"><?=$nt['profile']?></textarea></div><br />
    <div><h3>Contact Section Template</h3><textarea class="email-tpl" name="<?=$nt_prefix?>contact"><?=$nt['contact']?></textarea></div><br /><br />

    <br/><div class="tag-list">Tax section tags: <br/>
    <? foreach ($n->tax_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->tax_tags_desc[$k].'<br/>'; } ?>
    </div><br/>
    <div><h3>Tax Section Info</h3><textarea class="email-tpl" name="<?=$nt_prefix?>taxinfo"><?=$nt['taxinfo']?></textarea></div><br />

    <p><input type="submit" name="submit" value="Save Changes"/></p>


    <p><h2>Sample Thank You Email</h2>
    <iframe style="width:720px; height:1750px; " src="/payments/sample.php?thankyou&gcbuy&gcuse&giveany"></iframe>

    <? //$n = new Notification(1829,11); $n->build_thankyou_content(); echo $n->get_finished_content(); ?>

    <br/><br/><br/><br/></p>

  </form>
  <form method="post" class="subtab selectedTab" id="impactcard_resend-form" action="admin.php?page=notifications#impactcard_resend">

    <p><h3>Resend Impact Card</h3>Impact Card #: <input type="text" name="ic_code_lookup" value="<?=$_POST['ic_code_lookup']?>"  /></p>
    <p><input type="submit" name="submit" value="Preview Email"/> <input type="submit" name="submit" value="Resend Notification"/></p>

<? 
  if (!empty($da_params)) {
    if ($_POST['submit'] == 'Resend Notification') { 
      list($donor,$d_type) = get_donor_info_by_acct($da->id);
      $r_name = trim($da_params['recipient']['first_name']." ".$da_params['recipient']['last_name']);
      $r_email = $da_params['recipient']['email'];
      $s_name = trim($donor['firstName']." ".$donor['lastName']); 
      $s_email = $donor->email;
      $subject = "Purchased: ".$da->balance." (electronic delivery)";
      if (!empty($r_name)) $body .= "<br><br>for ".$r_name." (".$r_email.")";
      $icn = new Notification();
      $icn->recipient_name = $r_name;
      $icn->recipient_email = $r_email;
      if(empty($s_name)) $s_name = 'A donor';
    
      $icn_args = array(array($code),$code,array($code),
        "a ".as_money($da->balance)." Impact Card",as_money($da->balance),
        as_html($r_name),as_html($r_name),as_html($r_email),
        $gcMsg, as_html($s_name), as_html($s_email));
    
      $icn->build_impactcard_content($icn_args); 
      $gc_content = $icn->get_finished_content();
      $icn_sent = $icn->send(null,false,true,$gc_content);
      if($icn_sent) {
        echo "Impact Card Resent to: ".$r_name." (".$r_email."). <br/>".
          "<strong>PLEASE DO NOT REFRESH THE PAGE</strong>.";  
      } else {
        echo "Resent failed. ";  
      }
    } else {
      ?><iframe style="width:720px; height:1000px; " src="/payments/sample.php?impactcard=<?=$code?>"></iframe><? 
    }          
  } else {
    echo 'Recipient data (params) is empty.';    
  }

?>


  </form>
  <form method="post" class="subtab selectedTab" id="impactcard_email-form" action="admin.php?page=notifications#impactcard_email">

    <div><h3>Subject</h3><input type="text" name="ic_subject" value="<?=$ic_subject?>"/></div>
    <div><h3>Main Content</h3><textarea class="email-tpl" name="ic_main_content"><?=$ic_main_content?></textarea></div>
    <div><h3>Instructions</h3><textarea class="email-tpl" name="ic_instructions"><?=$ic_instructions?></textarea></div>
    <div><h3>Fineprints</h3><textarea class="email-tpl" name="ic_fineprints"><?=$ic_fineprints?></textarea></div>
    <div><h3>Top Content</h3><textarea class="email-tpl" name="ic_top_content"><?=$ic_top_content?></textarea></div>
    <div><h3>Card Content</h3><textarea class="email-tpl" name="ic_card_content"><?=$ic_card_content?></textarea></div><br /><br />

    <div><h3>Code Link</h3><input type="text" name="ic_code_link" value="<?=htmlentities($ic_code_link)?>"/></div>

    <div><h3>Share Title</h3><input type="text" name="ic_share_title" value="<?=htmlentities($ic_share_title)?>"/></div>
    <div><h3>Share Link</h3><input type="text" name="ic_share_link" value="<?=htmlentities($ic_share_link)?>"/></div>

    <p><input type="submit" name="submit" value="Save Changes"/></p>

    <p><h2>Sample Impact Card Email</h2>
    <iframe style="width:720px; height:1000px; " src="/payments/sample.php?impactcard"></iframe>
    <br/><br/><br/><br/></p>  

  </form>
  <form method="post" class="subtab" id="invite_email-form" action="admin.php?page=notifications#invite_email">

  <div class="tag-list">
  <? foreach ($n->invite_tpl_tags as $k=>$v) { echo '<div class="tag">'.$v.'</div> '.$n->invite_tpl_tags_desc[$k].'<br/>'; } ?>
  </div>

  </form>
<?
  if(isset($_REQUEST['remove_unsubscribed'])) {
    $id = intval($_REQUEST['remove_unsubscribed']);
    $sql = $wpdb->prepare("DELETE FROM unsubscribed WHERE id=%d LIMIT 1",$id); 
    $wpdb->query($sql);        
    @wp_redirect(remove_query_arg('remove_unsubscribed')); 
  }
  if(isset($_REQUEST['add_unsubscribed']) && isset($_REQUEST['emails'])) {

    $emails = $_REQUEST['emails'];
    $emails = explode(",",$emails);
    foreach($emails as $e) {
      if (is_email($e)) {
        $sql = $wpdb->prepare("INSERT INTO unsubscribed (email) VALUES (%s)",$e); 
        $wpdb->query($sql);        
      }
    }
  }

?>

  <form method="post" class="subtab" id="unsubscribed_list-form" action="admin.php?page=notifications#unsubscribed_list">
    <input type="text" name="emails" /> <input type="submit" id="" name="add_unsubscribed" value="Add" />
    <br class="clear" />
    <br class="clear" />
  <?
  
  $unsubscribed = $wpdb->get_results("SELECT id, email FROM unsubscribed ORDER BY email");
  if(!empty($unsubscribed))
  foreach ($unsubscribed as $u) {
    echo '<br />'.$u->email.'   <input type="hidden" name="remove_unsubscribed" value="'.$u->id.'" />
    <input style="border:0 none; padding: 0 none; margin: 0 none; text-decoration:underline; color:#21759B; cursor:pointer;" type="submit" onclick="return confirm(\'Delete?\');" value="Delete" />';
  }
  
  ?>
  </form>
<!--
  <form method="post" class="subtab" id="taxinfo-form">
    <h2>Tax Info Email Template</h2>
    <div style="float:left; margin-right:10px;"><h3>Subject</h3><input style="width: 400px" type="text" name="notify_taxinfo_subject" value="<?=$notify_taxinfo_subject?>"/></div><br style="clear:both"/>
    <div style="float:left; margin-right:10px;"><h3>Default CSS</h3><input style="width: 400px" type="text" name="notify_taxinfo_style" value="<?=$notify_taxinfo_style?>"/></div><br style="clear:both"/>
    <div style="float:left; margin-right:10px;"><h3>Main Section</h3><textarea class="email-tpl" name="notify_taxinfo_main"><?=$notify_taxinfo_main?></textarea></div><br style="clear:both"/>

    <p><iframe style="width:800px; height:750px;" src="/payments/sample.php?taxinfo"></iframe>
    <br/><br/><br/><br/></p>
    <p><input type="submit" name="submit" value="Save Changes"/></p>
  </form>
-->
</div>

<script type="text/javascript">
jQuery(function($) {
  function select(tab) {
    tab = $(tab);
    $(".subtab").removeClass("selectedTab");
    tab.addClass("selectedTab");
    $("#" + tab[0].id + "-form").addClass("selectedTab");
  }
  $("a.subtab").click(function() {
    select(this);
//    return false;
  });
  if (window.location.hash)
    select(window.location.hash);
  else 
    select("#thankyou");

  if ($.fn.tablesorter)
    $("form.subtab table").tablesorter();
});
</script>
<?

}

define('NOTIFY_URL_HOME',get_bloginfo('siteurl').'/');
define('NOTIFY_URL_TPL', NOTIFY_URL_HOME.'wp-content/templates/');
define('NOTIFY_SECT_DIV','<hr style="border:0;height:0;border-top: 1px dashed #ddd; margin: 30px 0;">');
define('NOTIFY_GIFT_LINK_TPL','http://seeyourimpact.org/give/#gift=home/#GIFT_ID#');
define('NOTIFY_GIFT_TPL','<table width="300" border="0" cellspacing="0" cellpadding="0"><tr style="vertical-align:top;"><td width="140"><a href="#PATH_HOME#/give/#gift=#GIFT_ID#"><img src="#GIFT_IMG#" height="90" width="120" alt="" style="float:left;margin-right:10px;border:5px solid #ddd;"/></a></td><td width="150"><p style="margin-top:0; line-height:18px; margin-right:10px;"><a href="#PATH_HOME#/give/#gift=#GIFT_ID#" style="color:#656465;"><b>#GIFT_TITLE#</b></a><br/><span style="font-size:11px;"><a href="#CHARITY_LINK#" style="color:#656465;">#CHARITY_NAME#</a></span><br/><span style="font-size:11px;">Share this on: </span><br/><a href="mailto:a-friend@some-domain.com?subject='.urlencode('Check this inspiring website').'&body='.urlencode('Please visit this web page: ').'#GIFT_LINK#'.('I just make a difference through SeeYourImpact, you can too!').'" style="border:0 none;"><img src="#PATH_TPL#/images/email.png" alt="Email" style="border:0 none;"/></a> <a href="http://www.facebook.com/sharer.php?u='.urlencode('http://seeyourimpact.org/give/#gift=home/').'#GIFT_ID#&t=#GIFT_TITLE#" style="text-decoration:none;"><img src="#PATH_TPL#/images/facebook.png" alt="Facebook" style="border:0 none;"/></a> <a href="http://twitter.com/home?status='.urlencode('I just make a difference at SeeYourImpact - see: http://seeyourimpact.org/give/#gift=home/').'#GIFT_ID#" style="text-decoration:none;"><img src="#PATH_TPL#/images/twitter.png" alt="Twitter" style="border:0 none;"/></a> <a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode('http://seeyourimpact.org/give/#gift=home/').'#GIFT_ID#&title=#GIFT_TITLE#" style="text-decoration:none;"><img src="#PATH_TPL#/images/linkedin.png" alt="LinkedIn" style="border:0 none;"/></a></p></td></tr><tr><td><br/></td></tr></table>');
define('NOTIFY_GC_TPL','<table width="300" border="0" cellspacing="0" cellpadding="0"><tr style="vertical-align:top;"><td width="140"><a href="#PATH_HOME#"><img src="#GIFT_IMG#" height="90" width="120" alt="" style="float:left;margin-right:10px;border:5px solid #ddd;"/></a></td><td width="150"><p style="margin-top:0; line-height:18px; margin-right:10px;"><b>#GIFT_TITLE#</b><br/> for #RECIPIENT_NAME#</p><p>"<em style="font-size:10px;">#GIFT_MESSAGE#</em>"</p></td></tr><tr><td><br/></td></tr></table>');

class Notification {

  //primary variables
  public $donationID;
  public $typeID;
  public $tpl_file;

  public $userID;
  public $donorID;
  
  //derived variables
  public $donation;
  public $content;
  public $subject;
  public $recipient_name;
  public $recipient_email;
  private $sent;
  private $postID;
  private $blogID;
  private $notificationID;
  private $nt_prefix;
  public $donations;

  //tags
  public $gift_tpl_tags = array('#GIFT_ID#','#GIFT_TITLE#','#GIFT_DESC#','#GIFT_LINK#',
    '#CHARITY_NAME#','#CHARITY_LINK#','#RECIPIENT_NAME#','#RECIPIENT_EMAIL#',
    '#GIFT_MESSAGE#','#GIFT_PRICE#','#GIFT_QTY#','#AGG_TITLE#','#AGG_LINK#','#AGG_LEFT#','#GIFT_IMG#');
  public $gift_tpl_tags_desc = array (
    'gift ID', 'gift display name', 'gift description', 'gift http link non html',
    'charity name', 'charity http link non html', 'giftcert recipient name', 'giftcert recipient email',
    'giftcert message', 'gift unit price/gift cert amount/var gift amount', 'donated gift quantity',
    'aggregate gift display name', 'aggregate gift http link non html', 'amount of $ needed to fulfill aggregate', 'gift image url'
  );

  public $body_tpl_tags = array('#CONTENT#','#GIVE_NOW_BTN#','#PATH_HOME#','#PATH_TPL#',
    '#DONOR_NAME#','#DONOR_EMAIL#');
  public $body_tpl_tags_desc = array('main content of the email *DO NOT USE*','give now button',
    'http path to homepage','http path to theme directory','donor name', 'donor email');

  public $campaign_tags = array('#CAMPAIGN_LINK#','#CAMPAIGN_TITLE#'); 
  public $campaign_tags_desc = array('campaign http link non html', 'campaign title');

  public $matching_tags = array('#MATCHING_USER_LINK#','#MATCHING_USER_NAME#'); 
  public $matching_tags_desc = array('link to matching account owner profile', 'matching account owner name');

  public $tax_tags = array('#DONOR_NAME#','#DONATION_DATE#','#TAX_LIST#');
  public $tax_tags_desc = array('donor name', 'donation date', 'tax items table');

  public $section_tags = array('#GIFT_SECTION#','#GC_SECTION#','#TIP_SECTION#','#GIFT_LIST#');
  public $section_tags_desc = array('gift list section', 'gift cert list section', 'tip message section',
    'can be used inside each gift section (single/agg./var/gc) so text can wrap around list, if N/A text will be used as header');

  public $ic_tpl_tags = array('#IC_INSTRUCTIONS#','#IC_FINEPRINTS#','#IC_TOP_CONTENT#','#IC_CARD_CONTENT#','#IC_SHARE_TITLE#','#IC_SHARE_LINK#');
  public $ic_card_tpl_tags = array('#IC_CODES#','#IC_CODE_FIRST#','#IC_CODE_LIST#','#IC_GIFT_NAME#','#IC_PRICE#','#IC_RECIPIENT_NAME#','#IC_RECIPIENT_FIRST#',
    '#IC_RECIPIENT_EMAIL#','#IC_MESSAGE#','#IC_SENDER_NAME#','#IC_SENDER_EMAIL#');

  public $invite_tpl_tags = array('#INVITER_MESSAGE#','#INVITER_NAME#','#INVITER_EMAIL#',
    '#INVITEE_NAME#','#INVITEE_EMAIL#', '#INVITE_NAME#','#INVITE_URL#','#TPL_CSS#',
    '#FEATURED_POST_PICTURE#', '#FEATURED_POST_CONTENT#', '#FEATURED_POST_LINK#', '#FEATURED_GIFTS_CONTENT#',
    '#CAMPAIGN_IMG#','#CAMPAIGN_DESC#','#CAMPAIGN_URL#','#CAMPAIGN_NAME#','#UPDATE_STATS#','#UPDATE_ASK#',
    '#GIVE_NOW_CAMPAIGN#');
    
  public $invite_tpl_tags_desc = array('the invitation message', 'person who invites', 'email of the person who invites', 
    'person who is being invited', 'email of the person who is being invited', 'invite name', 
    'the url where the invited should go', 'template style', 'featured post picture', 'featured post content', 'featured post link',
    'featured gifts content', 'campaign image','campaign description','campaign link','campaign name',
    'give now button for campaign');

  public $excl_invite_tpl_tags = array('#INVITEE_NAME#','#INVITEE_EMAIL#');

  public $update_tpl_tags = array('#UPDATE_STATS#','#UPDATE_ASK#');

  public $tip_tags = array('#TIP_AMOUNT#'); 
  public $tip_tags_desc = array('Tip amount in dollar');

  private $gift_tpl;
  private $agggift_tpl;
  private $vargift_tpl;
  private $gc_tpl;
  private $vartgift_meta;

  public $give_now_img;

  public function __construct($donationID = 0, $typeID = 11) {
    global $wpdb;
    //type 11: Single Donation, Thank You Note
    //type 12: Single Donation, Impact Update
    //type 13: Aggregate Donation, Thank You Note
    //type 14: Aggregate Donation, Impact Update 
    
    $this->give_now_img = '<img src="' . __C('templates/givenow.png') . '" alt="Give Now"/>';

    if (intval($donationID)>0) {

      $donation = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM donation d JOIN donationGiver dg ON d.donorID = dg.ID
      JOIN payment p ON d.paymentID = p.id LEFT OUTER JOIN wp_users u ON dg.user_id=u.ID
      WHERE d.donationID=%d", $donationID));

      if (empty($donation)) { return FALSE; }
      $this->donationID = $donationID;
      $this->donation = $donation;

      if($typeID == 11) {
      } else if ($typeID == 12) {
      } else if ($typeID == 13) {
      } else if ($typeID == 14) {
      } else if ($typeID == 15) {
      } else {
      }

      $this->typeID = $typeID;

    } else {
//      return FALSE;
    }
  }

////////////////////////////////////////////////////////////////////////////////

  public function build_taxinfo_content ($userID, $donorID=0, $date_start='', $date_stop='') {
    global $wpdb, $PAYMENT_METHODS;
    $this->tpl_file = 'thankyou_tpl.html';
    $tax_list='';
    $total_donation=0;
    $total_discount=0;
    $this->userID = $userID;
    $this->donorID = $donorID;
    $css = stripslashes(get_blog_option(1, 'notify_taxinfo_style'));
    $this->subject = stripslashes(get_blog_option(1, 'notify_taxinfo_subject'));
    $this->recipient_name = ucwords(stripslashes(trim($this->donations[0]->firstName.' '.$this->donations[0]->lastName)));
    $this->recipient_name = (empty($this->recipient_name)?'Donor':$this->recipient_name);
    $this->recipient_email = get_user_email($this->donations[0]->donorID,'thanks');

    $cond = '1 ';
    if (intval($userID) > 0) { $cond .= $wpdb->prepare('AND u.ID = %d ',$userID); }
    if (intval($donorID) > 0) { $cond .= $wpdb->prepare('AND dg.ID = %d ',$donorID); }
    if (trim($date_stop) != '') { $cond .= $wpdb->prepare('AND d.donationDate >= %s ', date('Y-m-d H:i:s', strtotime($date_start))); }
    if (trim($date_start) != '') { $cond .= $wpdb->prepare('AND d.donationDate <= %s ', date('Y-m-d H:i:s', strtotime($date_stop))); }

    $sql = "SELECT *,
    IF(dat.id IS NULL, 0, dat.amount) AS transAmt
    FROM donation d
    JOIN payment p ON d.paymentID = p.id
    LEFT OUTER JOIN donationGiver dg ON d.donorID = dg.ID
    LEFT OUTER JOIN wp_users u ON dg.user_id=u.ID
    LEFT OUTER JOIN donationAcctTrans dat ON (dat.paymentID=p.id)

    WHERE ".$cond."
    ORDER BY d.donationDate "; // echo $sql;

    $donations = $wpdb->get_results($sql);

    //if (empty($donations)) { return FALSE; }
    $this->donations = $donations;

    $tax_list .= '<table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="70" style="'.$css.'"><b>Date</b></td>
    <td width="280" style="'.$css.'"><b>Donation</b></td>
    <td width="90" align="right" style="'.$css.'padding-right:10px;"><b>Amount</b></td>
    <td width="150" style="'.$css.'"><b>Payment Method</b></td>
    </tr>';

    foreach($this->donations as $d) {

      $dDate = date('m/d/y',strtotime($d->donationDate));
      $pProvider = $PAYMENT_METHODS[$d->provider];
      
      $gifts = $wpdb->get_results($wpdb->prepare(
      "SELECT *,
      COUNT(dg.giftID) AS itemCount,
      SUM(dg.amount) AS itemAmt,
      SUM(dg.tip) AS itemTip
      FROM donationGifts dg
      JOIN gift g ON(dg.giftID=g.id)
      WHERE dg.donationID=%d AND dg.giftID>50
      GROUP BY dg.giftID", $d->donationID));

////////////////////////////////////////////////////////////////////////////////

//    if($gift_section!='' || $gc_section!='') {

      if (count($gifts)>0 || count($vargifts)>0) {

        if(count($gifts)>0)
        foreach ($gifts as $gift) {
          $tax_list .= '<tr>
            <td style="'.$css.'">'.$dDate.'</td>
            <td style="'.$css.'">'.ucwords(stripslashes($gift->displayName)).($gift->itemCount>1?' (x'.$gift->itemCount.')':'').'</td>
            <td align="right" style="'.$css.'padding-right:10px;">'.as_money($gift->amount*$gift->itemCount).'</td>
            <td style="'.$css.'">'.$pProvider.'</td>
            </tr>';
          $total_donation += $gift->amount * $gift->itemCount;
        }

        if(count($vargifts)>0)
        foreach ($vargifts as $gift) {
          $tax_list .= '<tr>
            <td style="'.$css.'">'.$dDate.'</td>
            <td style="'.$css.'">'.ucwords(stripslashes($this->vargift_meta->displayName)).'</td>
            <td align="right" style="'.$css.'padding-right:10px;">'.as_money($gift->price).'</td>
            <td style="'.$css.'">'.$pProvider.'</td>
            </tr>';
          $total_donation += $gift->price;
        }

      } else {
        $tax_list .= '<tr>
          <td style="'.$css.'">'.$dDate.'</td>
          <td style="'.$css.'">???</td>
          <td align="right" style="'.$css.'padding-right:10px;">'.as_money($d->donationAmount_Total).'</td>
          <td style="'.$css.'">'.$pProvider.'</td>
          </tr>';
      }

      if($d->transAmt < 0) {
          $tax_list .= '<tr>
            <td style="'.$css.'">'.$dDate.'</td>
            <td style="'.$css.'">Excluding discount -- not deductible</td>
            <td align="right" style="'.$css.'padding-right:10px;">'.as_money($d->transAmt).'</td>
            <td style="'.$css.'"></td>
            </tr>';
          $total_discount += $d->transAmt;
      }
////////////////////////////////////////////////////////////////////////////////

      if (count($gcs)>0) {
        foreach ($gcs as $gc) {
          $tax_list .= '<tr>
            <td style="'.$css.'">'.$dDate.'</td>
            <td style="'.$css.'">SeeYourImpact Impact Card</td>
            <td align="right" style="'.$css.'padding-right:10px;">'.as_money($gc->price).'</td>
            <td style="'.$css.'">'.$pProvider.'</td>
            </tr>';
          $total_donation += $gc->price * $gc->quantity;
        }
      }

      if (floatval($d->itemTip)>0) {
          $tax_list .= '<tr>
            <td style="'.$css.'">'.$dDate.'</td>
            <td style="'.$css.'">Contribution to SeeYourImpact</td>
            <td align="right" style="'.$css.'padding-right:10px;">'.as_money($d->itemTip).'</td>
            <td style="'.$css.'"></td>
            </tr>';
          $total_donation += $d->itemTip;
      }

/*
      if (count($discounts)>0) {
        foreach ($discounts as $discount) {
          $tax_list .= '<tr>
            <td style="'.$css.'">'.$dDate.'</td>
            <td style="'.$css.'">Excluding discount -- not deductible</td>
            <td align="right" style="'.$css.'padding-right:10px;">'.as_money($discount->price).'</td>
            <td style="'.$css.'">'.$pProvider.'</td>
            </tr>';
          $total_discount += $discount->price;
        }
      }
*/
//    }

////////////////////////////////////////////////////////////////////////////////
    }

    $tax_list .= '<tr><td colspan="4"><br/></td></tr>';
    $tax_list .= '<tr><td></td><td style="'.$css.'"><strong>Total Donation:</strong></td>
      <td align="right" style="'.$css.'padding-right:10px;">'.
      as_money($total_donation).'</td><td></td></tr>';
    $tax_list .= '<tr><td></td><td style="'.$css.'">Discounts/gift cards:</td>
      <td align="right" style="'.$css.'padding-right:10px;"><strong>'.
      as_money($total_donation).'</strong></td><td></td></tr>';
    $tax_list .= '<tr><td colspan="4"><br/></td></tr>';
    $tax_list .= '<tr><td></td><td style="'.$css.'">Total Tax-Deductible Amount:</td>
      <td align="right" style="'.$css.'padding-right:10px;">'.
      as_money($total_donation+$total_discount).'</td><td></td></tr>';
    $tax_list .= '<tr><td colspan="4"><br/></td></tr>';
    $tax_list .= '</table>';
    $tax_section = str_replace(array('#DONOR_NAME#','#DATE_RANGE#','#TAX_LIST#'),
      array($this->recipient_name,

      (($date_start!=''||$date_stop!='')?'Donation period: '.
      ($date_start==''?'':'from '.date('F j, Y',strtotime($date_start)).' ').
      ($date_stop==''?'':'to '.date('F j, Y',strtotime($date_stop)))
      :''),

      $tax_list),
      stripslashes(get_blog_option(1, 'notify_taxinfo_main')));

//    if(!empty($tax_section)) { $tax_section = NOTIFY_SECT_DIV.$tax_section; }

    $this->content .= $tax_section;
    if($echo) echo $this->content;
    return $this->content;
  }

  public function build_gift_row($gift,$css,$type='gift') {

    global $wpdb;

    if (($type=='gift' && $gift->amount==0) || 
      (($type=='gc' || $type=='var') && $gift->price==0)) return;

//    if($type=='var') 
//      pre_dump($gift);
//return;

    $gift_id = $gift->id;
    $gift_link = str_replace('#GIFT_ID#',($type=='var'?'':$gift->giftID),NOTIFY_GIFT_LINK_TPL);
    $agg_link = ($gift->agg_id>0?str_replace('#GIFT_ID#',$gift->agg_id,NOTIFY_GIFT_LINK_TPL):'');
    $gift_desc = (isset($gift->excerpt)&&$type!='gc'&&$type!='var'?stripslashes($gift->excerpt):'');
    $gift_name = stripslashes($gift->displayName);
    $gift_img = gift_image_src($gift_id, 90, 60);

    $gift_event = intval($gift->event_id);
    $gift_blog = intval($gift->blog_id);
    
    if ($gift_event>0) {
      $campaign_note = stripslashes(get_blog_option(1,$this->nt_prefix.'campaign_note'));
      $campaign_link = get_permalink($gift_event);
      $campaign_title = get_the_title($gift_event);
      $campaign_desc = str_replace($this->campaign_tags, array($campaign_link,$campaign_title), $campaign_note);
    }
    
    if ($type=='var') {

      if ($gift_event>0) {
        $gift_name = get_campaign_owner_name($gift_event);
        if (empty($gift_name))
          $gift_name = 'fundraiser support';
        else
          $gift_name = "$gift_name's fundraiser";
        $gift_img = fundraiser_image_src($gift_event, 90,90);
      } else if ($gift_blog>1) {
        $blog_details = get_blog_details($gift_blog);
        $gift_name = $blog_details->blogname;
        $gift_img = get_charity_thumb($gift_blog, NULL, array(90,90));
      } else if ($gift_blog == 1) {
        // Give any to site
        $gift_name = "SeeYourImpact to distribute on your behalf";
        $gift_id = CART_GIVE_ANY;
        $gift_img = gift_image_src($gift_id, 90,90);
        $gift_desc = "Give any amount and we'll take care of the rest. All you need to do is sit back and enjoy the stories you've made possible!";
      } else { 
        $gift_name = ucwords(stripslashes($this->vargift_meta->displayName));
      }


    } else if ($type=='gc') {
      $gift_name = GIFTCERT_ITEM_NAME;
    } else {
      $avg_tgi = get_avg_tgi($gift_id); 
      if ($avg_tgi > 0) {
        $tg = $wpdb->get_row($wpdb->prepare("SELECT * FROM gift WHERE id = %d", $avg_tgi));        

        $gift_id = $tg->id;
        $gift_link = str_replace('#GIFT_ID#',($type=='var'?'':$gift_id),NOTIFY_GIFT_LINK_TPL);
        $agg_link = ($gift->agg_id>0?str_replace('#GIFT_ID#',$gift->agg_id,NOTIFY_GIFT_LINK_TPL):'');
        $gift_desc = (isset($tg->excerpt)?stripslashes($tg->excerpt):'');
        $gift_name = stripslashes(AVG_NAME_PREFIX.$tg->displayName);
        
        $gift->agg_left = $tg->unitAmount; // AVG doesnt track progress, use the full amount

      } else {
      }
    }

    if ($gift_event>0) {
      if ($type != 'var')
        $gift_desc .= '<br><br>';
      $gift_desc .= "<i>$campaign_desc</i>";      
    }
 
    //$matching_trans = $gift->matchingDonationAcctTrans;
    $matching_trans = $wpdb->get_var($wpdb->prepare("SELECT matchingDonationAcctTrans 
      FROM donationGifts WHERE matchingDonationAcctTrans > 0 AND event_id=%d AND giftID=%d AND donationID=%d",
    $gift->event_id, ($type=='var'?$gift->gift_id:$gift->giftID), $gift->donationID));
    $matching_user = get_acct_user_by_trans($matching_trans);

    if (!empty($matching_trans)) {
      $matching_note = stripslashes(get_blog_option(1,$this->nt_prefix.'matching_note'));
      $gift_desc .= ' '.str_replace($this->matching_tags,
        array(bp_core_get_userlink($matching_user->ID,false,true,false),$matching_user->user_nicename), $matching_note);
    }

    $qty = "";
    if ($type == 'gc') {
      $price = as_money($gift->price);
      $gift_img = __C('images/syigc.jpg');
      if ($gift->itemCount > 1) {
        $gift_name = $gift->itemCount . " $price Impact Cards";
      } else {
        $gift_name = "$price Impact Card";
      }

      $gift_name = "<b>$gift_name</b>";
      if (!empty($gift->recipient->first_name))
        $gift_name .= " for {$gift->recipient->first_name} {$gift->recipient->last_name}";
      else if (!empty($gift->recipient->name))
        $gift_name .= " for {$gift->recipient->name}";

      if (is_array($gift->details->codes)) {
        foreach($gift->details->codes as $code) {
          $gift_name .= '<br><a style="color:#444;" href="' . SITE_URL . '/card/' . $code . '">view or print card #' . $code . '</a>';
        }
      }
    } if ($gift->itemCount > 1) {
      $qty = " (qty: $gift->itemCount)";
    }
    $gift_tpl_reps = array($gift_id, $gift_name,
      trim($gift_desc), ($gift_event==0?$gift_link:get_permalink($gift_event)),
      get_blog_option($gift->blog_id,'blogname'),
      get_blog_option($gift->blog_id,'siteurl'),
      $gift->recipient->name, $gift->recipient->email, stripslashes($gift->details->message),
      ($type=='var'||$type=='gc'?as_money($gift->price):$gift->amount),
      $qty,
      stripslashes($gift->agg_title), $agg_link, as_money($gift->agg_left), $gift_img);

    $ret = '';
    $ret .= str_replace($this->gift_tpl_tags,
      $gift_tpl_reps,
      ($type=='var'?
        $this->vargift_tpl:
        ($type=='gc'?
          $this->gc_tpl:
          ($type=='agg'?
            $this->agggift_tpl:
            $this->gift_tpl
          )
        )
      )
    );

    $ret .= '<br/></td></tr><tr><td valign="top" style="'.$css.'">';
    return $ret;

  }

  public function build_gift_section($items, $title_opt, $css, $type='gift') {
    $content = '';
    if (count($items)>0) {
      $content .= '<table width="600" border="0" cellspacing="0" cellpadding="0">';
      $content .= '<tr><td valign="top" style="'.$css.'">';
      foreach ($items as $item) $content .= $this->build_gift_row($item,$css,$type);
      $content .= '</td></tr></table>';

      $gift_section_tpl = stripslashes(get_blog_option(1,$this->nt_prefix.$title_opt));

      if(strpos($gift_section_tpl,'#GIFT_LIST#')!==FALSE) {
        $content = str_replace('#GIFT_LIST#',$content,$gift_section_tpl);      
      } else {
        $content = $gift_section_tpl.$content;
      }
    }
    return $content;
  }

  public function build_thankyou_content ($extras=null) {
    global $wpdb;
    $this->nt_prefix = 'notify_thankyou_';
    $this->tpl_file = 'thankyou_tpl.html';

    $this->gift_tpl = stripslashes(get_blog_option(1, $this->nt_prefix.'gift_tpl'));
    $this->agggift_tpl = stripslashes(get_blog_option(1, $this->nt_prefix.'agggift_tpl'));
    $this->vargift_tpl = stripslashes(get_blog_option(1, $this->nt_prefix.'vargift_tpl'));
    $this->gc_tpl = stripslashes(get_blog_option(1, $this->nt_prefix.'gc_tpl'));

    $this->subject = (intval($this->donation->test)==1?'(TEST) ':'').stripslashes(get_blog_option(1, $this->nt_prefix.'subject'));

    $this->recipient_name = ucwords(stripslashes(trim($this->donation->firstName.' '.$this->donation->lastName)));
    $this->recipient_name = (empty($this->recipient_name)?'Donor':$this->recipient_name);
    $this->recipient_email = get_user_email($this->donation->donorID,'thanks');

    $css = stripslashes(get_blog_option(1, $this->nt_prefix.'style'));

    $this->content = '';

    if(is_array($extras) && count($extras)>0) {
      $gcs = $extras['gcs'];
      $discounts = $extras['discounts'];
      $vargifts = $extras['vargifts'];
    }

    $gifts = $wpdb->get_results($wpdb->prepare(
    "SELECT *, count(dg.giftID) as itemCount,
    0 as agg_id, '' as agg_title, 0 as agg_left FROM donationGifts dg
    JOIN gift g ON(dg.giftID=g.id) WHERE dg.donationID=%d AND dg.giftID>50
    GROUP BY dg.giftID ORDER BY matchingDonationAcctTrans ASC", $this->donationID));

    // using gift per item to separate matching
    $gifts_per_item = $wpdb->get_results($wpdb->prepare(
    "SELECT *, count(dg.giftID) as itemCount,
    0 as agg_id, '' as agg_title, 0 as agg_left FROM donationGifts dg
    JOIN gift g ON(dg.giftID=g.id) WHERE dg.donationID=%d AND dg.giftID>50
    GROUP BY dg.giftID, matchingDonationAcctTrans", $this->donationID));

    $gift_count = count($gifts);

    $this->vargift_meta = $wpdb->get_row("SELECT * FROM gift g WHERE g.id=50");

    //gift section//
    $gift_section = '';
    if (count($gifts)>0 || count($vargifts)>0) {

      $single_gifts = array();
      $agg_gifts = array();

      if (count($gifts)>0)
      foreach ($gifts as $gift) {
        if (intval($gift->towards_gift_id)>0) {
          $agg = $wpdb->get_row($wpdb->prepare(
            "SELECT id, displayName as title, IF(unitAmount>current_amount AND current_amount>0, 
            unitAmount-current_amount,0) as `left` FROM gift WHERE id=%d",$gift->towards_gift_id));

         if ($agg->left > 0) {
            $gift->agg_id = $agg->id;
            $gift->agg_title = $agg->title;
            $gift->agg_left = $agg->left;
            $agg_gifts[] = $gift;
          } else {
            $single_gifts[] = $gift;
          }
        } else { $single_gifts[] = $gift; }
      }

      if (count($gifts)>0)
        $gift_section = stripslashes(get_blog_option(1, $this->nt_prefix.'giftinfo'));

      //single gift section//
      $gift_section .= $this->build_gift_section($single_gifts, 'sglgiftinfo', $css);

      //agg gift section//
      $gift_section .= $this->build_gift_section($agg_gifts, 'agggiftinfo', $css, 'agg');

      //var gift section//
      $gift_section .= $this->build_gift_section($vargifts, 'vargiftinfo', $css, 'var');
    }

    //Impactg Card section//    
    $gc_section = $this->build_gift_section($gcs, 'gcinfo', $css, 'gc');

    //tip recognition section//
    $tip_section = (floatval($this->donation->tip)>0?
      str_replace($this->tip_tags,array(as_money($this->donation->tip)),
        stripslashes(get_blog_option(1,$this->nt_prefix.'tipinfo'))):'');

    $this->content .= str_replace($this->section_tags,
      array($gift_section,$gc_section,$tip_section),
      stripslashes(get_blog_option(1, $this->nt_prefix.'main')));

    //profile section//
    $profile_section = stripslashes(get_blog_option(1, $this->nt_prefix.'profile'));;
    if(!empty($profile_section)) {$profile_section=NOTIFY_SECT_DIV.$profile_section;}

    //tax section//
    $total_donation=0;
    $total_discount=0;
    if($gift_section!='' || $gc_section!='') {
      $tax_list .= '<table width="300" border="0" cellspacing="0" cellpadding="0">';
      if (count($gifts)>0 || count($vargifts)>0) {
        if(count($gifts)>0)
        foreach ($gifts_per_item as $gift) { // using gift per item to separate matching
          if (intval($gift->matchingDonationAcctTrans) == 0)
            $total_donation += $gift->amount * $gift->itemCount;
        }

        if(count($vargifts)>0)
        foreach ($vargifts as $gift) {
            $total_donation += $gift->price;
        }

      }

      if (count($gcs)>0) { foreach ($gcs as $gc) { $total_donation += $gc->price * $gc->quantity; } }
      if (floatval($this->donation->tip)>0) { $total_donation += $this->donation->tip; }
      if (count($discounts)>0) {

        $tax_list .= '<tr><td style="'.$css.'">Total donation:</td><td align="right" style="'.$css.'">'.
          as_money($total_donation).'</td></tr>';
        foreach ($discounts as $discount) {
          $msg = $discount->message;
          if (empty($msg))
            $msg = "Gift code applied";

          $tax_list .= '<tr><td style="'.$css.'">' . $msg . ':</td><td align="right" style="'.$css.'">'.
            as_money($discount->price).'</td></tr>';
          $total_discount += $discount->price;
        }

        $tax_list .= '<tr><td colspan="2"><br/></td></tr>';
        $tax_list .= '<tr><td style="'.$css.'"><b>Your tax-deductible amount:</b></td><td align="right" style="'.$css.'"><b>'.
          as_money($total_donation+$total_discount).'</b></td></tr>';
      } else {
        $tax_list .= '<tr><td style="'.$css.'"><b>Total tax-deductible amount:</b></td><td align="right" style="'.$css.'"><b>'.
          as_money($total_donation).'</b></td></tr>';
      }

      $tax_list .= '<tr><td colspan="2"><br/></td></tr>';
      $tax_list .= '</table>';

      $tax_section = str_replace($this->tax_tags,
        array($this->recipient_name,date('F j, Y',strtotime($this->donation->dateTime)),$tax_list),
        stripslashes(get_blog_option(1, $this->nt_prefix.'taxinfo')));
      if(!empty($tax_section)) { $tax_section = NOTIFY_SECT_DIV.$tax_section; }
    }

    //contact section//
    $contact_section = stripslashes(get_blog_option(1, $this->nt_prefix.'contact'));
    if(!empty($contact_section)) { $contact_section = NOTIFY_SECT_DIV.$contact_section; }

    $this->content .= $profile_section;
    $this->content .= $tax_section;
    $this->content .= $contact_section;

    if($echo) echo $this->content;
    return $this->content;
  }

  public function build_admin_content($context, $inviter, $message, $invite_id=0, $inviter_name='') {
    global $site_url;

    {
//      $this->content = str_replace($this->invite_tpl_tags, $invite_tpl_reps, stripslashes($tpl->post_content));
//      $this->subject = ucfirst(stripslashes(str_replace($this->invite_tpl_tags, $invite_tpl_reps, $tpl_subject)));

      return true;
    }
    return false;

      
  }

  public function build_invite_content ($context, $inviter=1, $message='', $invite_id=0, $inviter_name='') {


    global $site_url;

    $this->tpl_file = 'thankyou_tpl.html';

    $context = explode("/",$context);
    $tpl_slug = '';

    if (!is_array($context)||count($context)==0) return false;
    if (intval($inviter)) $inviter = get_userdata($inviter);
    $invite_type = $context[0];

    di('INVITE TYPE IS : '.$context[0]);
    di('INVITE OBJECT ID IS : '.$context[1]);
    di('INVITER OBJECT: '.print_r($inviter,true));

////////////////////////////////////////////////////////////////////////////////

    switch ($invite_type) {
      case 'update' :
        if (intval($context[1])>0) $invite = get_post($context[1]);
        else $invite = get_post_by_name($context[1]);
        di('INVITE OBJECT: '.print_r($invite,true));
        if ($invite->post_type!='event') return false;
        $invite_url = $site_url.'/support/'.$invite->post_name;
        $tpl_slug = 'campaign-update-email';

        if($inviter_name == '') 
          $inviter_name = $invite_name = get_donor_fullname_by_uid($inviter->ID);
        else         
          $invite_name = $inviter_name;
          
        break;

      case 'campaign':
        if (intval($context[1])>0) $invite = get_post($context[1]);
        else $invite = get_post_by_name($context[1]);        
        di('INVITE OBJECT: '.print_r($invite,true));
        if ($invite->post_type!='event') return false;
        $invite_url = $site_url.'/support/'.$invite->post_name;

        if ($invite->ID == get_campaign_for_user($inviter->ID))
          $tpl_slug = 'my-campaign-invite-email';
        else
          $tpl_slug = 'any-campaign-invite-email';

        if($inviter_name == '') 
          $inviter_name = $invite_name = get_donor_fullname_by_uid($inviter->ID);
        else         
          $invite_name = $inviter_name;

        break;

      case 'profile':
        $invite = get_userdata($context[1]);
        di('INVITE OBJECT: '.print_r($invite,true));
        if (empty($invite)) return;
        $invite_url = get_member_link($invite->ID);
        if($invite->ID == $inviter->ID) $tpl_slug = 'my-profile-invite-email';
        else $tpl_slug = 'any-profile-invite-email';

        if($inviter_name == '') 
          $inviter_name = $invite_name = get_donor_fullname_by_uid($inviter->ID);
        else         
          $invite_name = $inviter_name;

        break;

      case 'thankyou': 
        $cart_id = intval($context[1]);
        $user = get_cart_user($cart_id,true);
        if (empty($user)) return;

        if($inviter_name == '') 
          $inviter_name = $invite_name = get_donor_fullname_by_uid($user->ID);
        else         
          $invite_name = $inviter_name;


        $invite_url = $user->user_url;
        $cart_event_ids = get_cart_event_ids($cart_id);        
        if($cart_event_ids!=NULL && strpos($cart_event_ids,",")===FALSE) {
          $invite = get_post(intval($cart_event_ids));
          if ($invite->ID == get_campaign_for_user($inviter->ID))
            $tpl_slug = 'my-campaign-invite-email';
          else
            $tpl_slug = 'any-campaign-invite-email';
          $invite_type = 'campaign';
          $invite_url = $site_url.'/support/'.$invite->post_name;
        } else {
          $tpl_slug = 'thankyou-invite-email';
        }

        break;      
    }

////////////////////////////////////////////////////////////////////////////////

    if (!empty($tpl_slug)) {
      $tpl = get_post_by_name($tpl_slug);
      if ($tpl == null) { di('TEMPLATE #'.$tpl_slug.' IS NOT FOUND'); return false; }      

      di('USING TEMPLATE #'.$tpl->ID.' '.$tpl_slug);

      $invite_meta_keys = array('template_subject','template_css','template_note',
        'template_featured_post','template_featured_gifts','template_gifts_count');
      $invite_meta_values = get_post_metas($invite_meta_keys, $tpl->ID);
   
      //if ($invite_meta_values == NULL) { di('TEMPLATE METAS NOT FOUND'); }

      $tpl_subject = $invite_meta_values->template_subject;      
      $tpl_css = $invite_meta_values->template_css;      
      $tpl_note = $invite_meta_values->template_note;
      $tpl_featured_post = $invite_meta_values->template_featured_post;            
      $tpl_featured_gifts = $invite_meta_values->template_featured_gifts;
      $tpl_gifts_count = $invite_meta_values->template_gifts_count;
            
      if ($message=='') {
        $message = $tpl_note;  //use default message if blank    
        $message = nl2br(html_to_text($message));
      } else {
        $message = html_to_text($message);          
      }

      if (!empty($inviter->ID)) {
        $invite_url = add_query_arg('referrer', intval($invite_id), $invite_url);  
      }

//pre_dump($message);
//pre_dump($invite_meta_values);

////////////////////////////////////////////////////////////////////////////////////////////////////
// GET CAMPAIGN ATTRIBUTE

      switch_to_blog(1); // force switch to blog #1 to get campaign attributes
      $campaign_name = '';
      $campaign_desc = '';
      $campaign_img = '';
      $campaign_url = '';
      
      if ($invite_type=='campaign' || $invite_type=='update') {
        $campaign_name = $invite->post_title;
        $campaign_img = '<a href="'.$invite->guid.'" style="float:left;margin-right:20px;">'.
          get_the_post_thumbnail($invite->ID,array(100,100), 
          array('style'=>'width:100px;min-height:80px;padding:4px;border:2px solid #dddddd;display:block;margin-bottom:4px;')).'</a>';
         restore_current_blog();
        $campaign_desc = nl2br(html_to_text($invite->post_content));
        $campaign_url = $invite_url;
      }

      if ($invite_type=='campaign' || $invite_type=='update' || $invite_type == 'profile') {
        $fr_tag = get_fr_tags($invite->ID);
        if (!empty($fr_tag)) {
          if (strpos($fr_tag,',')!==FALSE) {
            $fr_tag = explode(',', $fr_tag);
            $fr_tag = $fr_tag[0];
          }        
          di("FOUND CAMPAIGN TAG: ".$fr_tag);
        }
      }

      $update_stats = '';
      $update_ask = '';  

      if ($invite_type=='update' || $invite_type=='campaign') {
        $update_stats = draw_campaign_stats_email($invite->ID);
        $update_ask;          
      }

////////////////////////////////////////////////////////////////////////////////////////////////////
// GET CAMPAIGN SAMPLE STORY

      if (!empty($fr_tag)) {
        di("STORY TRY CAMPAIGN TAG ".$fr_tag."");
        $featured_post = get_stories_by_tag($fr_tag,1);        
      } else {
      
/* Steve: DON'T SHOW RANDOM STORIES 
        if (empty($featured_post)) {
          if(!empty($context[2])) {
            di("STORY TRY PASSED BLOG-POST IDS: ".$context[2]);
            $featured = explode("-",$context[2]);
            $fblog_id = $featured[0];
            $fpost_id = $featured[1];
          }
        }
        
        if (empty($featured_post)) {
          if($tpl_featured_post == 'random' && empty($fr_tag)) {
            di("STORY TRY RANDOM");
            $featured_post = get_stories_where(1,1);
          } else {
            $featured = explode("-",$tpl_featured_post);
            if (!empty($featured)) {
              di("STORY TRY TEMPLATE BLOG-POST IDS: ".$tpl_featured_post);
              $fblog_id = $featured[0];
              $fpost_id = $featured[1];                        
            }
          }
        }
*/
      }

      if (!empty($featured_post)) {
        
        if (is_array($featured_post)) {
          $featured_post = $featured_post[0];
        }

        $fblog_id = $featured_post->blog_id;
        $fpost_id = $featured_post->post_id;
        di("USING STORY: BLOG#".$fblog_id." POST#".$fpost_id);
        $featured_post = get_blog_post($fblog_id, $fpost_id);

        switch_to_blog($fblog_id);
        $featured_post_link = get_blog_permalink($fblog_id, $featured_post->ID);
        $featured_post_content = nl2br(html_to_text($featured_post->post_content)) .
          ' <br/><br/><b>Read more stories <a href="'. get_site_url($fblog_id, '/stories') . '" style="color:#666666; ">here</a>.</b>';
        
        $max_w = 188;
        $fpic_atts = wp_get_attachment_image_src(get_post_thumbnail_id( $fpost_id ),'full');
        di("ATTACHED STORY IMAGE: ".print_r($fpic_atts,true));
        if(!empty($fpic_atts) && intval($fpic_atts[1])>0) {
          $fpic_w = $max_w; 
          $fpic_h = ($fpic_atts[2] * $max_w)/$fpic_atts[1];
        } else {
          $fpic_w = $max_w;
          $fpic_h = $max_w;  
        }

        //pre_dump(array($fpic_atts,$fpic_w,$fpic_h));
        $featured_post_thumb = get_the_post_thumbnail($fpost_id,
            array(intval($fpic_w),intval($fpic_h<$fpic_w?$fpic_w:$fpic_h)), 
            array('style'=>'padding: 4px; border:2px solid #dddddd; display:block; margin-bottom: 4px;',
            'width'=>intval($fpic_w), 'height'=>intval($fpic_h)));
        di($fpic_w." ".$fpic_h." ".print_r($featured_post_thumb,true));
        $featured_post_picture = 
          '<br><span style="font-size:16px;color:#2a4f62;font-family:Arial;">Join me and make stories like this one:</span><br><br>'.
          '<a href="'.$featured_post_link.'">'.$featured_post_thumb.'</a>';
        switch_to_blog(1);
      }
//

////////////////////////////////////////////////////////////////////////////////////////////////////

      $invite_tpl_reps = array(nl2br(stripslashes($message)), $inviter_name, $inviter->user_email, 
        '#INVITEE_NAME#', '#INVITEE_EMAIL#', $invite_name, $invite_url, $tpl_css,
        $featured_post_picture, $featured_post_content, $featured_post_link, $featured_gifts_content,
        $campaign_img, $campaign_desc, $campaign_url, $campaign_name, $update_stats, $update_ask,
        '<a href="'.$invite_url.'">'.$this->give_now_img.'</a>');

//pre_dump($invite_tpl_reps);

      $this->content = str_replace($this->invite_tpl_tags, $invite_tpl_reps, stripslashes($tpl->post_content));
      $this->subject = ucfirst(stripslashes(str_replace($this->invite_tpl_tags, $invite_tpl_reps, $tpl_subject)));

      return true;
    }
    return false;
  }

////////////////////////////////////////////////////////////////////////////////

  public function build_impactcard_content ($args) {
    global $wpdb;

    if(empty($args)) return '';

    $this->nt_prefix = 'ic_'; 
    $this->tpl_file = 'impactcard_tpl.html';    
    
    $this->subject = stripslashes(get_blog_option(1, 'ic_subject'));
    $this->content = stripslashes(get_blog_option(1, 'ic_main_content'));

    $ic_instructions = stripslashes(get_blog_option(1, 'ic_instructions'));
    $ic_fineprints = stripslashes(get_blog_option(1, 'ic_fineprints'));
    $ic_top_content = stripslashes(get_blog_option(1, 'ic_top_content'));
    $ic_card_content = stripslashes(get_blog_option(1, 'ic_card_content'));
    $ic_code_link = stripslashes(get_blog_option(1, 'ic_code_link'));

    $ic_share_title = stripslashes(get_blog_option(1, 'ic_share_title'));
    $ic_share_link = stripslashes(get_blog_option(1, 'ic_share_link'));
    
    $ic_tpl_reps = array($ic_instructions,$ic_fineprints,$ic_top_content,$ic_card_content,$ic_share_title,$ic_share_link);
    $this->content = str_replace($this->ic_tpl_tags,$ic_tpl_reps,$this->content);        

    if(is_array($args[2])) {
      $ic_code_list = '';
      foreach($args[2] as $ic_code)  {
          $ic_code_list .= str_replace('#IC_CODE#',$ic_code,$ic_code_link);  
      }      
      $args[2] = $ic_code_list;
    }

    $this->content = str_replace($this->ic_card_tpl_tags,$args,$this->content);        
    $this->subject = str_replace($this->ic_card_tpl_tags,$args,$this->subject);    

  }

////////////////////////////////////////////////////////////////////////////////

  public function add_content ($content, $div=true) {
    if($div) $this->content .= NOTIFY_SECT_DIV;
    $this->content .= $content;
  }

  public function replace_body_tpl_tags($html = NULL) {
    if (!$html) {
      $html = $this->content;
    }

    return str_replace(
      $this->body_tpl_tags,
      array(
        $this->content,
        '<a href="#PATH_HOME#give/">'.$this->give_now_img.'</a>',
        NOTIFY_URL_HOME,
        NOTIFY_URL_TPL,
        $this->recipient_name,
        $this->recipient_email
      ),
      $html);
  }

  public function get_finished_content($cache_callback=false, $args=NULL) {
    $html = file_get_contents(NOTIFY_URL_TPL.$this->tpl_file);
 
    $html = $this->replace_body_tpl_tags($html);
    if($cache_callback)
      $html = apply_filters( 'wp_cache_ob_callback_filter', $html );      
   
    return $html;
  }

  public function send ($extras=null, $debug=false, $bypass_filters=false, $content='') {
    global $wpdb, $phpmailer;
    syi_init_phpmailer();
    $mail = $phpmailer;

    if ($this->typeID == 11 || $this->typeID == 13) {
      $mail->AddBCC(get_email_address("mails-sent"));
    }

    try {
      $mail->Subject = $this->subject;
      $mail->AddAddress($this->recipient_email,$this->recipient_name);

      if(!$bypass_filters) {
        if(empty($this->content)) $this->build_thankyou_content($extras);
        $content = $this->get_finished_content();
        $mail->MsgHTML(utf8_encode(xml_entities($content)));
      } else {
        $mail->MsgHTML($content);  
      }
      $h2t = &new html2text($content); //Convert HTML to text for ALT tag

      $mail->ContentType = "text/html";
      $mail->AltBody = $h2t->get_text();

      if ($this->typeID>10 && $this->typeID<30)
        $this->insert_history();

      //ignore blank and test email
      if (empty($this->recipient_email) || strpos($this->recipient_email,'.seeyourimpact.com')!==FALSE) {
        $sent = TRUE;
      } else if (!can_send_to_email($this->recipient_email)) {
        $sent = TRUE;
        debug("EMAIL: ".$this->recipient_email, true,"WARNING LIVE EMAIL ON DEV");
      } else {
        $sent = $mail->Send(); //sending email
      }

      if(!$sent){
        debug($mail->ErrorInfo."\n=====".print_r($mail,true), true, "Mail Send Failure");
        if ($this->typeID>10 && $this->typeID<20)
          $wpdb->query($wpdb->prepare("UPDATE notificationHistory SET success=0, error=%s
          WHERE notificationID=%d", $mail->ErrorInfo, $this->notificationID));
      } else {
          $wpdb->query($wpdb->prepare("UPDATE notificationHistory SET success=1
          WHERE notificationID=%d", $this->notificationID));
      }
      
      return $sent;
    } catch (Exception $e) {
      debug("ERROR: ".$e->getMessage(),true,"CATCH EMAIL ERROR (notification.php)");
    }
  }

  private function insert_history() {
    global $wpdb;

    $sql = $wpdb->prepare("SELECT COUNT(1) FROM notificationHistory
    WHERE donationID=%d AND postID=%d AND success=1 AND (mailType=%d OR emailSubject='%s')",
    $this->donationID, $this->postID, $this->typeID, '');

    $sent_count = intval($wpdb->get_var($sql));

    $wpdb->query($wpdb->prepare("INSERT INTO notificationHistory
    (mailType,donorID,donationID,postID,blogID,sentDate,success,emailTo,emailSubject,emailText)
    VALUES(%d,%d,%d,%d,%d,NOW(),0,'%s','%s','%s')",
    $this->typeID, $this->donation->donorID, $this->donationID, $this->postID, $this->blogID,
    $this->recipient_email, $this->subject, $this->get_finished_content()));

    $this->notificationID = $wpdb->insert_id;
  }
}

function can_send_to_email($email) {
  if (is_live_site())
    return TRUE;

  if (strpos($email,'seeyourimpact.org')!==FALSE)
    return TRUE;

  // TODO: add whitelist

  return FALSE;
}

function syi_init_phpmailer($mail=NULL) {
  global $phpmailer;
  $passed = false;

  if ($mail!=NULL) { $passed = true; }

  if ($mail==NULL || !is_object($mail) || !($mail instanceof PHPMailer)) {
      $mail = new SyiPHPMailer();
  }
  
  $mail->IsSMTP();
  $mail->From = 'impact@seeyourimpact.org';
  $mail->FromName = 'SeeYourImpact.org';
  do_action_ref_array('phpmailer_init',array(&$mail));

  if ($passed) { return $mail; } else { $phpmailer = $mail; }
}

////////////////////////////////////////////////////////////////////////////////

add_action('wp', 'invite_process_activation');
add_action('invite_process_event', 'do_invite_process');

function invite_process_activation() {
  if(!wp_next_scheduled('invite_process_event')) {
    wp_schedule_event(current_time('timestamp'), 'asap', 'invite_process_event');
  }
}

add_filter( 'cron_schedules', 'cron_add_asap' );
 
function cron_add_asap( $schedules ) {
  // Adds per-minute to the existing schedules.
  $schedules['asap'] = array(
    'interval' => 30,
    'display' => __( 'ASAP (30 sec)' )
  );
  return $schedules;
}

function di($val, $var = '') {
  global $invite_debug;
  if (!empty($var)) $invite_debug .= "$var=";
  $invite_debug .= htmlentities("$val\n");      
}

function di_end($msg='', $email=true, $subject='Debug Invite') {
  global $invite_debug;  
  di($msg);
  
  if(strpos($invite_debug,'FAILED')!==FALSE) {
    $subject = 'ERROR: '.$subject;  
  }
  
  debug($invite_debug, $email, "$subject");
}

function queue_mail($p, $email, $blogid, $mailType, $aggMailType,
  $items, $postID, $ccList, $mailSubject='', $mailContent='', $moreMsg='', $resend = false) {
  global $mq_debug;
  global $mq;
  if($mq == NULL && !is_array($mq)) { $mq = array(); $mqc=0; $mq_debug=''; }
  $m = func_get_args();
  $mq[] = $m;      
//  $mq_debug.="INSERT MAIL Q: ".print_r($m,true)."\n";  
  SyiLog::log('mq', "insert: ".json_pretty($m));
}

function queue_mail_simple($p, $name, $email, $subject='', $vars='', $template='', 
  $debug=false, $readyContent=false) {
  global $mq_debug;
  global $mq;
  if($mq == NULL && !is_array($mq)) { $mq = array(); $mqc=0; $mq_debug=''; }  
  $m = func_get_args();
  $m['simple'] = 1;
  $mq[] = $m;
//  $mq_debug.="INSERT MAIL Q: ".print_r($m,true)."\n";  
  SyiLog::log('mq', "insert: ".json_pretty($m));
}

function process_mail_queue() {
  global $mq_debug;
  global $mq;
  global $emailEngine;

  if($mq == NULL && !is_array($mq)) return false;
  $mq_debug.="START PROCESSING MAIL Q: ".strval(time())."\n";  
  SyiLog::log('mq', 'start mail queue processing');
  $mq = subval_sort($mq,0);
  foreach($mq as $m) {
    $mq_debug.="SENDING MAIL: ".print_r($m,true)."\n";  
    if(isset($m['simple'])) {
      SyiLog::log('mq', 'sendMailSimple: '.json_pretty($m));
      $emailEngine->sendMailSimple($m[1],$m[2],$m[3],$m[4],$m[5],$m[6],false,"from_queue");
    }
    else {
      SyiLog::log('mq', 'sendMail: '.json_pretty($m));
      $emailEngine->sendMail($m[1],$m[2],$m[3],$m[4],$m[5],$m[6],$m[7],$m[8],$m[9],$m[10],$m[11],"from_queue");
    }
  }
  $mq_debug.="FINISHED PROCESSING MAIL Q: ".strval(time())."\n";  
  SyiLog::log('mq', 'finished processing queue');
//  debug($mq_debug,true);
}

function subval_sort($a,$subkey) {
  foreach($a as $k=>$v) {
    $b[$k] = strtolower($v[$subkey]);
  }
  asort($b);
  foreach($b as $key=>$val) {
    $c[] = $a[$key];
  }
  return $c;
}

function do_invite_process() {
  global $wpdb;
  $total_start = intval(microtime(true) * 1000);
  switch_to_blog(1);

  $donotsend = $wpdb->get_col("SELECT email FROM unsubscribed");
  di('INVITE PROCESS START AT '.$total_start);

  $sql = "SELECT *, ivn.id AS ivnID, iv.id AS ivID
    FROM invitation ivn JOIN invite iv ON iv.invitation_id = ivn.id
    WHERE (iv.status = 'pending')
    AND ivn.date_added >= DATE_SUB(NOW(), INTERVAL 30 DAY) LIMIT 500";
    // OR iv.status = 'failed'

  $results = $wpdb->get_results($sql);
  unset($sql);
  $prev_ivn = 0;
  $content = '';
  $sent_count = 0;
  $n = new Notification(0,0,20,0);
  // $must_send = false; 

  if (is_array($results) && count($results)>0) {
    di('FOUND RECORDS: '.count($results));
    $built=false;

    foreach ($results as $result) {
      try {
        $time_start = intval(microtime(true) * 1000);
        $wpdb->query($wpdb->prepare("UPDATE invite SET status='sending' WHERE id=%d",$result->ivID));
        
        $n->recipient_name = $result->name;
        $n->recipient_email = $result->email;
        
        if ($n->recipient_name=='') $n->recipient_name = 'Friend';

        if($prev_ivn != $result->ivnID) {
          $built=false;
          // $invite_message = utf8_encode(xml_entities($result->message));
          di('BUILD INVITE CONTENT PARAMS: '.print_r($result,true));

          $built = $n->build_invite_content($result->context, $result->user_id, $result->message,
            $result->ivID, $result->inviter_name);
          if (!$built) { di('CONTENT BUILD FAILED ON ivn#'.$result->ivnID); } 
          else { di('CONTENT BUILT FOR ivn#'.$result->ivnID); }
          $content = utf8_encode(xml_entities($n->get_finished_content()));
            $prev_ivn = $result->ivnID;
        }

        if ($built) { //ensure only built content sent

          if(strpos($n->recipient_email,'seeyourimpact.com') !== FALSE) {
            di("IGNORING TEST EMAIL ".$n->recipient_email);
            $sent = true;
          } else if(in_array($n->recipient_email,$donotsend)) {
            di("EMAIL ".$n->recipient_email." IS REGISTERED IN UNSUBSCRIBED LIST -- NOT SENT");  
            $sent = true;
          } else {
            $c = str_replace($n->excl_invite_tpl_tags,array($n->recipient_name, $n->recipient_email),$content);      
            $sent = $n->send(null,false,true,$c);              
          }
  
        } else {
          $sent = false;    
        }

        $time_stop = (microtime(true)*1000);
        $wpdb->query($wpdb->prepare("UPDATE invite SET date_sent=NOW(), process_time=%d, status=%s 
          WHERE id=%d",intval($time_stop-$time_start),($sent?'sent':'failed'),$result->ivID));
        if(!$sent) { 
          di("SEND FAILED: #".$result->ivnID.'-'.$result->ivID." IN ".intval($time_stop-$time_start)."ms ");        
          //debug(print_r($result,true)."\n".print_r($n,true),true); 

        } else {
          $sent_count++;    

        }

        $wpdb->flush();

      } catch (Exception $e) {
        //debug(print_r($result,true)."\n".$e->getMessage(),true);
        di_end("INVITE PROCESS FAILED ".$e->getMessage()." FINISHED IN: ".(intval(microtime(true)*1000)-$total_start)."ms ");
        //die();
      }
    }

    di_end("INVITE PROCESS FINISHED IN: ".(intval(microtime(true)*1000)-$total_start)." SENT:".$sent_count);
  } else {
    //no records found
  }

  restore_current_blog();
}

////////////////////////////////////////////////////////////////////////////////

function draw_invite_import ($atts, $content) {
  global $invite_context;


  $context = explode("/",$invite_context,2);
  $invite_type = $context[0];

  return '
  <div>' . $content . '</div>
  <div id="importer-container" style="display:none;">
    <div id="importer"></div>
    <div id="importer-buttons">
      <input type="button" id="importer-addlist" class="button green-button small-button ev" value="Add selected"/>
      <input type="button" id="importer-selectall" class="button gray-button small-button ev" value="Select all"/>
      <input type="button" id="importer-unselectall" class="button gray-button small-button ev" value="Unselect all"/>
      <input type="button" id="importer-cancel" class="button gray-button small-button ev" value="Cancel"/>
    </div>
  </div>
  <div id="invite-import-container">
    <div style="float:left; width: 250px; position: relative;">
      <div><b>Invite your friends</b> from:</div>
      <div id="inline-login">
        <h3 style="margin:0; padding:0;">Please log in to AOL:</h3>
        <div class="field"><label for="inline-login-username">Username:</label><input type="text" id="inline-login-username" size="10" /></div>
        <div class="field"><label for="inline-login-password">Password:</label><input type="password" id="inline-login-password" size="10" /></div>
        <input type="button" onclick="return open_inline_login_popup(input_service);" name="submit" value="Login" class="button small-button green-button" style="margin:10px 0 0;"/>
        <input type="button" onclick="reset_importer();" name="cancel" value="Cancel" class="button small-button gray-button" style="margin:10px 0 0;"/>
      </div>
      <div style="padding:10px 0;">
        <a id="import-gmail" class="service ev" href="#service=gmail" onclick="return open_popup(\'gmail\')"><img src="' . __C('themes/syi/images/SocialMediaBookmarkIcon/32/google.png') . '" alt="Open Gmail address book" />Gmail</a>
        <a id="import-live" class="service ev" href="#service=windowslive" onclick="return open_popup(\'windowslive\')"><img src="' . __C('themes/syi/images/SocialMediaBookmarkIcon/32/microsoft.png') . '" alt="Open Microsoft Live address book" />Windows Live</a>
        <a id="import-yahoo" class="service ev" href="#service=yahoo" onclick="return open_popup(\'yahoo\');"><img src="' . __C('themes/syi/images/SocialMediaBookmarkIcon/32/yahoo.png') . '" alt="Open Yahoo address book" />Yahoo Mail</a>
        <a id="import-aol" class="service ev" href="#service=aol" onclick="input_service=\'aol\'; $(\'#inline-login\').show(); return false;"><img src="' . __C('themes/syi/images/SocialMediaBookmarkIcon/32/aol.png') . '" alt="Open AOL address book" />AOL</a>
        <!--<a id="import-outlook" class="service ev" href="#service=outlook" onclick="return open_popup(\'outlook\')"><img src="' . __C('themes/syi/images/SocialMediaBookmarkIcon/32/outlook.png') . '" alt="Open Outlook address book" />Outlook</a>-->
        <div style="display:block; padding: 15px 0 0; width: 250px; clear:both;">to choose from your contacts...</div>
      </div>
    </div>
    <div style="float:left; width: 380px;">
      <div>or enter e-mail addresses, one per line:</div>
      <textarea name="invites" id="invites" wrap="off"></textarea>
    </div>
  </div>
  ';
}

add_shortcode('invite_import','draw_invite_import');

function draw_invite_message ($atts, $content) {
  global $invite_context;
  global $bp;
  $inviter_id = 0;
  if(is_user_logged_in()) {
    $inviter_id = $bp->loggedin_user->id;
    $user = get_userdata($inviter_id,true);
    if (empty($user)) return;
    $inviter_name = $user->display_name;      
  } else {
    $inviter_id = 0;
    $inviter_name = '';  
  }
  
  //pre_dump (get_campaign_for_user($inviter_id));

  $context = explode("/",$invite_context,2);
  $invite_type = $context[0];
  $message = '';

  switch ($invite_type) {

    case 'update':
      if (intval($context[1])) $invite = get_post($context[1]);
      else $invite = get_post_by_name($context[1]);
      $tpl_slug = 'campaign-update-email';
      break;
    case 'campaign':
      if (intval($context[1])) $invite = get_post($context[1]);
      else $invite = get_post_by_name($context[1]);
      if($invite->ID == get_campaign_for_user($inviter_id)) { 
        $tpl_slug = 'my-campaign-invite-email'; 
      } else {
        $tpl_slug = 'any-campaign-invite-email'; 
      }
      $message = get_default_message($context[1]);
      break;
    case 'profile':
      if (intval($context[1])) $invite = get_userdata($context[1]);

      if($invite->ID == $inviter_id) $tpl_slug = 'my-profile-invite-email';
      else $tpl_slug = 'any-profile-invite-email';
      break;

    case 'thankyou':
      $tpl_slug = 'thankyou-invite-email';
      $cart_id = intval($context[1]);      
      $cart_event_ids = get_cart_event_ids($cart_id);
      if($cart_event_ids!=NULL && strpos($cart_event_ids,",")===FALSE) {
        $invite = get_post(intval($cart_event_ids));         

        if($invite->ID == get_campaign_for_user($inviter_id)) { 
          $tpl_slug = 'my-campaign-invite-email';
          $message = $invite->post_content;
        }  else $tpl_slug = 'any-campaign-invite-email';

      } else {
        $tpl_slug = 'thankyou-invite-email';
      }

      break;      
  }

  if (!empty($tpl_slug)) {
    $tpl = get_post_by_name($tpl_slug);

    if(empty($message))
      $message = str_replace(array('#INVITER_NAME#'),
        array(($inviter_name==''?'[YOUR NAME]':$inviter_name)),get_post_meta($tpl->ID, 'template_note', 1));
  }

  $message = html_to_text($message);

  if ($message == '-') {
    $message = '';
  }

//pre_dump($tpl);
//<div style="width: 240px; float:left; padding: 8px 5px;"><span id="add-message" class="ev"><u>Add a personal message</u>?</span></div>

  return '<br/><div id="invite-message-container">
  '.(isset($atts['noname'])?'':'<div style="margin-bottom: 5px;">'.
  (isset($atts['name_ask'])?$atts['name_ask']:'Write your name for the email greeting:').
  ' <input name="sender" id="invite-sender" value="'.$inviter_name.'" required="required"/></div>').'  
  <div style="margin-bottom: 5px;">'.stripslashes($content).'</div>
  <textarea id="invite-message" name="message" required="required">'.$message.'</textarea></div>
  <div id="invite-actions"><div id="msg" style="float:right; margin-top:5px;"></div><div style="float:left;">
  <input type="submit" name="invite" id="invite" class="button green-button large-button ev" value="Send Invitations"/></div>
  <br/><br/><br/>
  </div>';
}

add_shortcode('invite_message','draw_invite_message');

function draw_invite_thanks ($atts, $content) {
  return '<div id="invite-thanks" class="thankyou-widget" style="display:none;">'.$content.'</div>';
}

add_shortcode('invite_thanks','draw_invite_thanks');

function draw_invite_link ($context='', $template='', $return=false, $btype="invite") {
  global $bp;
  if (empty($context))
    $context = 'profile/'.encrypt($bp->loggedin_user->id);

  // need to handle cross-site requests
  $url = main_site_request('/invite/?context='.urlencode($context));
  $classes = "green-button button ev ${btype}-button inv-button";
  if (array_key_exists('show_invite', $_REQUEST)) {
    $classes .= " click-on-page-load";
  }

  $ret = '<a href="' . $url . '" class="' . $classes . '">'. eor($template, 'Invite your friends') . '</a>';

  if($return)
    return $ret;
  else
    echo $ret;
}

function draw_send_progress () {
  global $event_id;
  ?>
  <div style="clear:both; padding:20px 20px; width:950px; border-top:1px solid #999;;">
  <form id="progress_form" >
  <strong>Send updates to your campaign followers:</strong><br/>
  <? wp_nonce_field('invite-nonce'); ?>
  <textarea name="message" id="message" cols="85" rows="1" style="padding:2px; width:640px;" required="required"></textarea><br/>
  <div style="margin:10px 0; text-align:right;">
  <input type="radio" name="invite_group" value="1" checked="checked" /> donors only
  <input type="radio" name="invite_group" value="2" /> all involved
  <input type="radio" name="invite_group" value="3" /> invitees only
  <input type="submit" name="send" value="send update" class="" style="margin-left:20px;padding:0 10px;" />
  </div>
  <input type="hidden" name="context" value="<?='update/'.encrypt($event_id)?>" />
  <div id="progress_alert"></div>
  </form>
  </div>
  <script>
  $(function() {
    $('#progress_form').submit(function () {
      $.ajax({
        type: "POST",
        url: "/invite?ajax",        
        data: $('#progress_form').serialize(),
        beforeSend: function() {
          $('#progress_form').hide();
        },
        success: function (data) {
          if(data.toString().indexOf('Error') >= 0) {
            alert(data);
          } else {
            alert('Update sent!');  
            $('#message').val('');
          }
          $('#progress_form').show();
        },
        error: function (a,b,c) {
          alert(c);
          $('#progress_form').show();
        }
      });
      return false;
    });
  });
  </script>  
  <!--
  <a href="" class="button gray-button small-button right ev invite-button" style="text-decoration:none; line-height:1.45em;">send progress</a>
  -->
  <?
}

function html_to_text($html) {
  // Turn BRs into newlines
  $html = strip_shortcodes(strip_tags($html, "<br>"));
  $text = preg_replace('#<br\s*/?>#i', "\n", $html);
  return $text;

  $h2t = &new html2text($html);
  return stripslashes(str_replace(
  array("<br />"),array(" &nbsp;\r"),
    trim($h2t->get_text())));    
}

function get_email_address($type) {
  if (is_live_site()) {
    $addr = get_site_option("${type}_email");
    if (empty($addr))
      $addr = "$type@seeyourimpact.org";
  }
 
  // payments_email is always set (typically a debug address) 
  if (empty($addr))
    $addr = get_site_option("payments_email");

  return $addr;
}

function phpmailer_init_smtp_new($phpmailer) {
  switch_to_blog(1);
  phpmailer_init_smtp($phpmailer);
  restore_current_blog();
}
remove_action('phpmailer_init','phpmailer_init_smtp');
add_action('phpmailer_init','phpmailer_init_smtp_new');


?>
