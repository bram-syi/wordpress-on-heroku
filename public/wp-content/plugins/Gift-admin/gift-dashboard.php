<?php
/*
Plugin Name: Gift Dashboard
Plugin URI: http://www.seeyourimpact.org/
Description: Gift Dashboard
Version: 1.0
*/

function statsSince($date, $adjust = false, $first_last = 0)
{
  global $wpdb;
  global $blog_id;

  $from = "FROM donation AS d 
    LEFT OUTER JOIN donationGifts AS dg ON (dg.donationID = d.donationID) 
    LEFT OUTER JOIN donationGiver AS dd ON (d.donorID = dd.ID) 
    LEFT OUTER JOIN donorInfo AS info ON (info.donorID = d.donorID) 
    LEFT OUTER JOIN gift AS g ON (g.id = dg.giftID) 
    LEFT OUTER JOIN gift AS ag ON (ag.id = dg.towards_gift_id) 
    LEFT OUTER JOIN payment AS p ON (d.paymentID = p.id)
    LEFT OUTER JOIN donationAcctTrans AS dat ON (dg.matchingDonationAcctTrans = dat.id) 
    LEFT OUTER JOIN donationAcct AS da ON (da.id = dat.donationAcctID) 
    LEFT OUTER JOIN donationGiver AS ddd ON (da.donorId = ddd.ID) 
    LEFT OUTER JOIN wp_blogs AS wb ON (wb.blog_id = g.blog_id) 
	LEFT OUTER JOIN gift AS tg ON 
	  (g.towards_gift_id = tg.id AND g.varAmount=1 AND g.unitAmount=".AVG_UNIT_AMOUNT.")";

  $cond = "WHERE "
    ."d.test=0 "

    ."AND donationDATE >= DATE('" . date('Y-m-d', $date) . "') ";  

  if(isset($_POST['maximum'])){
    $maximum = floatval($_POST['maximum']);
    $cond .= "AND dg.amount<= ".$maximum." ";
  }

  if(isset($_POST['match_opt'])){
    $match_opt = intval($_POST['match_opt']);
    if($match_opt == 1){
      $cond .= "AND dg.matchingDonationAcctTrans=0 ";
    }else if($match_opt == 2){
      $cond .= "AND dg.matchingDonationAcctTrans>0 ";
    }
  } else {
    $match_opt = 0;
  }

  if(isset($_POST['giftcert_opt'])){
    $giftcert_opt = intval($_POST['giftcert_opt']);
    if($giftcert_opt == 1){
      $cond .= "AND p.raw NOT LIKE '%%GiftCertificate%%' ";
    }else if($giftcert_opt == 2){
      $cond .= "AND p.raw LIKE '%%GiftCertificate%%' ";
    }
  } else {
    $giftcert_opt = 0;
  }

  $_blog_id = 1;
  if(isset($_REQUEST['blog_id']) && $_REQUEST['blog_id']!='' && $_REQUEST['blog_id']!=1){
    $_blog_id = intval($_REQUEST['blog_id']);
    
    $cond.='AND dg.blog_id='.intval($_REQUEST['blog_id']);
  }
  
  if($first_last>0){
    return $wpdb->get_var(
      "SELECT ".($first_last>1?"MAX":"MIN")."(donationDate) $from $cond"
    );
  }
  
  $_blog_url = $wpdb->get_var($wpdb->prepare(
    "SELECT domain FROM wp_blogs WHERE blog_id = %d",$_blog_id));

  $r_queries = array(
    "total_donation_amount"=>"SELECT SUM(dg.amount) $from $cond",
    "total_tip_amount"=>"SELECT SUM(dg.tip) $from $cond",
    "donation_amount_tipped_on"=>"SELECT SUM(dg.amount) $from $cond AND dg.tip > 0",
    "donation_amount_no_tip"=>"SELECT SUM(dg.amount) $from $cond AND dg.tip = 0",
    "donation_amount_matched"=>"SELECT SUM(dg.amount) $from $cond AND dg.matchingDonationAcctTrans > 0",
    "tip_amount_matched"=>"SELECT SUM(dg.tip) $from $cond AND dg.matchingDonationAcctTrans > 0",
    "count_donation_gifts"=>"SELECT COUNT(DISTINCT dg.id) $from $cond",
    "count_donation_gifts_with_tip"=>"SELECT COUNT(DISTINCT dg.id) $from $cond AND dg.tip > 0",
    "count_donation_gifts_matched"=>"SELECT COUNT(DISTINCT dg.id) $from $cond AND matchingDonationAcctTrans > 0",
    "count_donations"=>"SELECT COUNT(DISTINCT dg.donationID) $from $cond"
  );
  
  
  foreach($r_queries as $k=>$v){
    $r_results[$k] = $wpdb->get_var($v);
  }

  $ret = '';
  $ret .= '<div style="font-size:12px; width:300px;">';
  foreach($r_queries as $k=>$v){
    if(strpos($k,'count')===0){break;}
    $ret .= '<div style="padding:2px;">'.ucwords(str_replace("_"," ",$k))
    .'<div style="float:right"><a href="/reports.php?report=dashboard&title='
    .urlencode(ucwords(str_replace("_"," ",$k)).' on '.$_blog_url
    .' since '.date('Y-m-d', $date))
    .'&encrypted_sql='.urlencode(encrypt($v))
    .'" target="_blank">'
    .as_money($r_results[$k])
    .'</a></div></div>';  
  }
  
  $ret .= '<br/>';
  $ret .= '<div style="padding:2px;">Total of '
    .'<a href="/reports.php?report=dashboard&title='
    .urlencode(ucwords(str_replace("_"," ",'total_donation_amount'))
    .' on '.$_blog_url
    .' since '.date('Y-m-d', $date))
    .'&encrypted_sql='.urlencode(encrypt($r_queries['total_donation_amount']))
    .'" target="_blank">'    
    .$r_results['count_donations'].'</a>'
    .' donations with '
    

    .'<a href="/reports.php?report=dashboard&title='
    .urlencode(ucwords(str_replace("_"," ",'total_donation_amount'))
    .' on '.$_blog_url
    .' since '.date('Y-m-d', $date))
    .'&encrypted_sql='.urlencode(encrypt($r_queries['total_donation_amount']))
    .'" target="_blank">'
    .$r_results['count_donation_gifts'].'</a>'
    
    .' gift items</div>';
  
  //donation_amount_tipped_on
  $ret .= '<div style="padding:2px;">'
    
    .'<a href="/reports.php?report=dashboard&title='
    .urlencode(ucwords(str_replace("_"," ",'donation_amount_tipped_on'))
    .' on '.$_blog_url
    .' since '.date('Y-m-d', $date))
    .'&encrypted_sql='.urlencode(encrypt($r_queries['donation_amount_tipped_on']))
    .'" target="_blank">'
    .$r_results['count_donation_gifts_with_tip']
    .'</a>'
    .' of '.$r_results['count_donation_gifts']    
    .' gift items is with tip ('
    .round($r_results['count_donation_gifts']>0?
      $r_results['count_donation_gifts_with_tip']*100/
      $r_results['count_donation_gifts']:0,2).' %)</div>';
  
  
  $ret .= '<div style="padding:2px;">Average tip % on tipped donation is '
    .round($r_results['donation_amount_tipped_on']>0?
    $r_results['total_tip_amount']*100/
    $r_results['donation_amount_tipped_on']:0,2).' %</div>';
  $ret .= '</div>';
  return $ret;
}

function gift_dashboard_widget() {
  $today = date('Y-m-d');
  
  if(isset($_POST['start_date'])){
    $start_date = $_POST['start_date'];
  } else {
    $start_date = '2009-10-11';
  }  

  if(isset($_POST['maximum'])){
    $maximum = floatval($_POST['maximum']);
  } else {
    $maximum = 1000;
  }

  $match_opt = intval($_POST['match_opt']);
  $match_opts = array(
    'include matching',
    'exclude matching',
    'matching only'
  );
  $match_opt_select .= '<select name="match_opt">';
  foreach($match_opts as $k=>$v){
    $match_opt_select .= '<option value="'.$k.'" '
      .($k==$match_opt?'selected="selected"':'').'>'.$v.'</option>';
  }
  $match_opt_select .= '</select>';

  $giftcert_opt = intval($_POST['giftcert_opt']);
  $giftcert_opts = array(
    'include giftcert',
    'exclude giftcert',
    'giftcert only'
  );
  $giftcert_opt_select .= '<select name="giftcert_opt">';
  foreach($giftcert_opts as $k=>$v){
    $giftcert_opt_select .= '<option value="'.$k.'" '
      .($k==$giftcert_opt?'selected="selected"':'').'>'.$v.'</option>';
  }
  $giftcert_opt_select .= '</select>';

  
  echo '
  <form method="post">
  <div>'.build_charity_options('blog_id',$_POST['blog_id'],'dropdown',true).'  
  <br/>'
  .$match_opt_select.'<br/>'
  .$giftcert_opt_select.'<br/>'
  .'Start Date: <input type="text" name="start_date" '
  .'value="'.$start_date.'"/><br/>'
  .'Maximum Gift Amt: <input type="text" name="maximum" '
  .'value="'.$maximum.'"/>
  <br/><br/>
  <input type="submit" name="submit" value="Submit"/></div>
  </form>  
  <br/>
  '
  ;
  //strtotime('-10 year', strtotime($today))
  
  echo '<div>Today is: ' . date('l, M j, Y') . '</div>';
  echo '<div>First record: '
    . date('l, M j, Y', 
      strtotime(
        statsSince(
          strtotime($start_date),false,1))) . '</div>';
  echo '<div>Last record: '
    . date('l, M j, Y', 
      strtotime(
        statsSince(
          strtotime($start_date),false,2))) . '</div>';

  echo '<p><strong>All donations</strong>';
  echo statsSince(strtotime($start_date));
  echo '</p>';
  echo '<p><strong>Donations YTD</strong>';
  echo statsSince(strtotime(strval(date("Y")).'-01-01'));
  echo '</p>';  
  echo '<p><strong>Donations month-to-date</strong>';
  echo statsSince(strtotime(strval(date("Y-m")).'-01'));
  echo '</p>';
  echo '<p><strong>Donations since last week</strong>';
  echo statsSince(strtotime('-1 week', strtotime($today)));
  echo '</p>';
}


function add_gift_dashboard_widget() {
  if (current_user_can('publish_post')) {
	wp_add_dashboard_widget('gift_dashboard_widget', 'Donation Dashboard', 'gift_dashboard_widget');
  }
}  

add_action('wp_dashboard_setup', 'add_gift_dashboard_widget' );
