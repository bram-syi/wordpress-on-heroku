<?

include_once('wp-load.php');

$code = $_REQUEST['code'];
if (empty($code)) {
  wp_redirect('/'); die;
}

if ($code == "sample") {
  $acct = new stdClass;
  $acct->balance = 100;
  $acct->code = "ABCD1234";

  $params = new stdClass;
  $params->recipient = (object)array(
    'first_name' => 'Jane Q.',
    'last_name' => 'Donor'
  );
  $params->message = "Dear Jane,<br><br>Please use this gift to change lives at SeeYourImpact.org. Pick your favorite project and in about two weeks you'll meet the life that you changed. Believe me, you will be impressed!<br><br>-- John";

} else {
  $id = get_acct_id_by_code($code);
  if (empty($id)) {
    sleep(4);
    ?><html><body><div style="color:#a00; font:18px Arial; padding: 100px;">
    We're sorry, that is not a valid Impact Card code.<br>
    Please confirm that you copied the code correctly,<br>
    or contact us at <a style="color:#800;" href="mailto:contact@seeyourimpact.org">contact@seeyourimpact.org</a> if<br>
    you are having trouble!
    </div></body></html><?
    die;
  }
  $acct = get_donation_account($id);
  $params = json_decode($acct->params);
}

$message = "";
$recip = $params->recipient;
if (!empty($recip->first_name)) {
  $name = trim("$recip->first_name $recip->last_name");
  $message = "For: $name<br>";
}

$balance = as_money($acct->balance);

$message .= "Balance: <b>$balance</b><br><br>" . nl2br(stripslashes($params->message));

$i = SITE_URL . "/wp-content/images/";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!--[if lt IE 7]> <html class="no-js ie6 ieX" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 ieX" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 ieX" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head><title>Your SeeYourImpact.org Impact Card</title>
<style>
#card { position: relative; }
#instructions {
  width: 280px;
  position: absolute;
  right: -320px;
  top: 20px;
}

body {
  margin:0;
  padding:30px; 
  font: 16px Arial,Helvetica,sans-serif;
}

.ieX .bkgd { display:none !important; }
.ieX .text { border-left: 5px solid #B7E3F0; border-right: 5px solid #B7E3F0; width: 521px; }
.ieX #t1 { margin-left: -5px; }
.ieX .print-preview { 
  display: block !important;
  padding: 0 15px;
  font-size: 80%;
  }

@media screen {
  a { text-decoration: underline; }
  #print { 
    display: block;
    -webkit-background-clip: padding-box;
    color: white;
    border: 1px solid #555;
    background: #666;
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#888), to(#575757));
    font-size: 14px;
    padding: 0.4em 1em 0.42em;
    zoom: 1;
    vertical-align: baseline;
    margin: 0px 2px;
    outline: none;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    font: normal normal bold 14px/100% Arial, Helvetica, sans-serif;
    padding: 0.5em 1em 0.55em;
    text-shadow: rgba(0, 0, 0, 0.296875) 0px 1px 1px;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(0, 0, 0, 0.199219) 0px 1px 2px;
    box-shadow: rgba(0, 0, 0, 0.199219) 0px 1px 2px;
    line-height: 1.1em;
    margin: 20px;
  }
}
@media print {
  a { text-decoration: none; color: black; }
  #print, .ieX .print-preview { display: none !important; }
  #bkgd { width: 531px; height: 480px; position: absolute; z-index: 0; }
  #bkgd2 { width: 531px; position: absolute; height: 51px; }
  #bkgd3 { width: 120px; position: absolute; height: 28px; }
  .text { width: 531px; position: relative; z-index: 1; }
  .text2 { position: relative; z-index: 1; }
}
</style>
</head>
<body>

<!-- For the curious: this huge mess is what it takes for one snippet of HTML to support both HTML emails and printing from all major browsers without losing background images.... -->
<div id="card" style="width: 531px;"><div style="width:531px; text-align: center; background:#B7E3F0;">
<img style="display:block;" src="<?=$i?>impact-card-top.jpg?" width="531" height="132">
<img id="bkgd" class="bkgd" style="display:block;" width="531" height="1" src="<?=$i?>impact-card-bkgd.jpg">
<div class="text"><div style="color:#045568; font-size: 17px; padding: 15px 30px 0px;">Choose a cause you're passionate about.  In about 2 weeks, you'll receive the photo and story of the person you helped!</div><div style="color:#045568; font: bold 24px Arial; padding: 30px 100px 0;"><?= $acct->code ?></div><div style="color:black; padding: 10px 50px 30px;"><?= $message ?></div>
<table id="t1" width="531" cellspacing="0" cellpadding="0" border="0" style="display:block;"><tr>
  <td valign="top" width="229" valign="top"><table width="229" cellspacing="0" cellpadding="0" border="0">
  <tr><td valign="top" colspan="2" style="width:229;height:163;"><img style="display:block;" width="229" height="163" src="<?=$i?>impact-card-left-top.jpg"></td></tr>
  <tr><td valign="top" width="109"><img style="display:block;" src="<?=$i?>impact-card-left-left.jpg" width="109" height="28"></td>
    <td valign="top" class="code" width="120" style="background:#F26925; text-align:center; color: black; font: bold 13px Arial;"><img id="bkgd3" class="bkgd" style="display:block;" src="<?=$i?>impact-card-code-bkgd.jpg" width="120" height="1"/><div class="text2" style="padding-top:7px;"><?= $acct->code ?></div></td></tr>
  <tr><td valign="top" colspan="2"><img style="display:block;" src="<?=$i?>impact-card-left-bottom.jpg" width="229" height="40"></td></tr>
  </table></td><td valign="top" width="302" valign="top"><img style="display:block;" src="<?=$i?>impact-card-right.jpg" width="302" height="231"></td>
  </tr></table>
</div><img id="bkgd2" class="bkgd" style="display:block;" src="<?=$i?>impact-card-bkgd.jpg" width="531" height="1"><div class="text" style="height:50px;"><div style="color:black; font-size:10px; padding: 0 30px;">Unless otherwise specified, Impact Card purchases not fully used within 12 months from the date of purchase will convert to a charitable donation to SeeYourImpact.  Please review the SeeYourImpact terms and conditions for further information.</div></div>
<img style="display:block;" src="<?=$i?>impact-card-bottom.jpg" width="531" height="18"></div>
<div style="font: 16px Arial; color: #666;" id="instructions">
<h2 style="color:#222;">Congratulations!</h2>
You've been given an Impact Card from <a style="color:#222;" href="http://seeyourimpact.org">SeeYourImpact.org</a>.
You can apply this card towards over 200 donation choices in 18 countries worldwide, including the U.S.
Better yet, <b>100% of your donation</b> goes to your chosen charity.
<h3 style="color:#222;">How does it work?</h3>
To use your Impact Card, simply select a life-changing gift on <a style="color:#222;" href="http://seeyourimpact.org">SeeYourImpact.org</a>, and click the donate button. Apply the code "<?=$acct->code?>" during checkout. <a style="font-size:80%; color:#222;" target="_new" href="http://seeyourimpact.org/gifts/redeem/">more info</a>
<h3 style="color:#222;">What happens next?</h3>
In about 2 weeks, you will receive an individual story of the life you changed.
<h3 style="color:#222;">Questions?</h3>
We're never more than an email away. Send your questions and ideas to: <a style="color:#222;" href="mailto:contact@seeyourimpact.org">contact@SeeYourImpact.org</a>
<input id="print" TYPE="button" onClick="window.print();" value="Print this page">
<div style="display: none;" class="print-preview">(NOTE: you will need to turn on "Print background colors and images" in your browser options. <a href="http://support.microsoft.com/kb/296326" target="_new">show me how</a>)
</div>
</div>

<body>
</html>
