<?php

// To add new reports: add a function called "report_SOMETHING()", where
// SOMETHING is what your report shows. Then add a line to the existing 
// "get_reports()" function.
//
// Use "require_param()" to get and set parameters.

include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include_once(ABSPATH . '/a/api/api.php');
include_once(ABSPATH . '/wp-admin/includes/user.php');

define('ROW_LIMIT', 5000);

if (!is_user_logged_in()) {
  $url = site_url($_SERVER["REQUEST_URI"]);
  wp_redirect( wp_login_url( $url ));
  die();
}

nocache_headers();

if ( !current_user_can('level_1') )
  wp_die('No access');

$notTest = "d.test=0";

$params = array();
$missing_params = FALSE;

function report_dashboard(){
  global $wpdb;
  $ret = ''; $script = '';
  
  $sql = decrypt($_GET['encrypted_sql']);

  $script .= '<div style="width:960px;font-size:12px;"><strong>Stats SQL: </strong>'.$sql.'</div>';  

  $replaced_fields = 
//  array(
  array("SUM(dg.amount)","SUM(dg.tip)","COUNT(DISTINCT dg.id)")
//  ,
//  "COUNT(DISTINCT dg.donationID)"
//  )
  ;

  if(strpos($sql,"COUNT(DISTINCT dg.donationID)")!==FALSE){
    //$sql .= ' GROUP BY dg.donationID ';
  }


  $sql_count_donation_gift = str_replace($replaced_fields, "COUNT(DISTINCT dg.id)", $sql);  
  $sql_count_donation = str_replace($replaced_fields, "COUNT(DISTINCT dg.donationID)", $sql);  
  $sql_total_amount = str_replace($replaced_fields, "SUM(dg.amount)", $sql);
  $sql_total_tip = str_replace($replaced_fields, "SUM(dg.tip)", $sql);  

  if(strpos($sql,"COUNT(DISTINCT dg.donationID)")!==FALSE){
    $sql .= 'GROUP BY dg.donationID';  
  }

  $main_blogname = get_blog_option(1,'blogname');  
  $sql = str_replace($replaced_fields,  
//    array(
    "" 
    ."DATE_FORMAT(d.donationDate,'%m/%d/%y %h:%i') AS `date`,"
    ."dg.ID AS `dgid`,"

    ."CONCAT('$ ',FORMAT(dg.amount,2)) AS `amt`,"
    ."CONCAT('$ ',FORMAT(dg.tip,2)) AS `tip`,"
    ."CONCAT(FORMAT(IF(dg.amount>0,100*dg.tip/dg.amount,0),2),'%') AS `%tip`,"
    ."CONCAT('[',wb.blog_id,'] ',REPLACE(wb.domain,'.$main_blogname','')) as site,"
    ."dg.unitsDonated AS `qty`,"
    ."CONCAT('[',dg.giftID,'] ',
      IF(tg.id IS NOT NULL, CONCAT('".AVG_NAME_PREFIX."',tg.displayName), g.displayName)) AS `gift`,"
    ."CONCAT('[',dg.towards_gift_id,'] ',ag.displayName) AS `agg gift`,"
    //."CONCAT(dd.firstName,' ',dd.lastName,' [',d.donorID,']') AS `Donor`,"

    ."dd.firstName as first,dd.lastName as last,d.donorID as `donor`, dd.email, info.donorType as `type`,"
    ."d.paymentID AS `payment`,"
    ."IF(dg.matchingDonationAcctTrans>0,'Matched',"
    ."IF(p.raw LIKE '%%Google Checkout%%','Google',"
    ."IF(p.raw LIKE '%%ACK=Success%%','CreditCard',"
    ."IF(p.raw LIKE '%%GiftCertificate%%', 'GiftCert',"
    ."IF(p.raw LIKE '%%cart%%','PayPal',"
    ."'??'))))) AS `method`,"
//    ."provider AS m,"
    ."d.donationID AS `donation`,"
    ."CONCAT('$',FORMAT(d.donationAmount_Total,2)) AS `d amt`,"
    ."CONCAT('$',FORMAT(d.tip,2)) AS `d tip`,"
    ."dg.story AS `post`,"
    ."dg.matchingDonationAcctTrans AS `match`,"
    ."CONCAT('[',ddd.ID,'] ',ddd.firstName,' ',ddd.lastName) AS `matcher` "
/*
    ,
    ""
    ."DATE_FORMAT(d.donationDate,'%m/%d/%y %h:%i') AS `Date`,"
    ."d.donationID AS `Donation`,"
    ."CONCAT('$',FORMAT(d.donationAmount_Total,2)) AS `D Amt`,"
    ."CONCAT('$',FORMAT(d.tip,2)) AS `D Tip`,"
    ."COUNT(dg.ID) as `Gift Items`,"
    ."CONCAT(dd.firstName,' ',dd.lastName,' (',d.donorID,')') AS `Donor`,"
    ."d.paymentID AS `Payment`"
*/    
//    )
    ,
    $sql);

  $sql .= " ORDER BY d.donationDate DESC ";

  $script .= '<br/><div style="width:960px;font-size:12px;"><strong>Query SQL: </strong>'.$sql.'</div>';
  //echo $sql; 
  //exit();

  $count_donation_gift = $wpdb->get_var($sql_count_donation_gift);
  $count_donation = $wpdb->get_var($sql_count_donation);
  $total_amount = $wpdb->get_var($sql_total_amount);
  $total_tip = $wpdb->get_var($sql_total_tip);

  $ret .= '<div id="report_floating_info" >'
  .'<div style="text-align:left">'.$_GET['title'].'</div><br style="clear:both"/>'  
  .'<label>Gift Count: </label>' . $count_donation_gift . '<br/>'
  .'<label>Donation Count: </label>' . $count_donation . '<br/>'
  .'<label>Total Amount: </label>' . as_money($total_amount) . '<br/>'
  .'<label>Total Tip: </label>' . as_money($total_tip) . '<br/>'
  .'</div>';
    
  return generate($sql,$_GET['title'],$ret,$script);
}

function generate($sql, $title = '', $desc='', $script='')
{
  if (is_array($sql))
    extract($sql);

  global $wpdb, $missing_params;

  $meta = array(
    'title' => $title,
    'desc' => $desc,
    'script' => $script,
    'columns' => $columns
  );

  $count = "COUNT(*) as count,";

  if (count($cols) > 0) {
    get_filters($cols, $wheres);

    $col = draw_groupings($cols);
    if (!empty($col)) {
      $filter = (object)$cols[$col];
      $filter->label = eor($filter->label, $col);
      $filter->group_func = eor($filter->group_func, $filter->column);
      $filter->group_func = str_replace('THIS', $filter->column, $filter->group_func);
      $group_select = trim(eor($filter->group_select, $select, '*'));
      $group_by = eor($filter->group_by, 'group_by');
      $order_by = eor($filter->order_by, $group_by);
      if ($filter->no_count)
        $count = "";

      $select = "$filter->group_func as group_by, $select";
      $sql = <<< EOF
SELECT 
  group_by as `$col`, $count
    $group_select
FROM ($sql) as data
GROUP BY $group_by
ORDER BY $order_by
EOF;
      $title .= " by $filter->label";
      $meta['group_by'] = $col;
    }
  }

  if ($_REQUEST['submit']) {
  } else if ($expensive && empty($col) && count($wheres) == 0) {
    echo "<div><b>Please select some options</b> - too slow to run without filters!<br><br></div>";
    return;
  }

  $sql = str_replace('{{SELECT}}', "SELECT $select", $sql);

  if (is_array($wheres)) {
    $wheres = array_filter($wheres);
  } else $wheres = array();
  if (count($wheres) > 0) {
    $wheres = 'WHERE ' . as_and($wheres);
  } else
    $wheres = "";
  $sql = str_replace('{{WHERE}}', $wheres, $sql);

  if (!preg_match('/LIMIT\s*\d*\s*$/', $sql))
    $sql .= "\r\nLIMIT " . ROW_LIMIT;

  if ($missing_params) {
    error_log('missing params');
    return;
  }

  if ($_REQUEST['sql'] == 'yes')
    pre_dump($sql);
  
  $wpdb->show_errors();
  $results = $wpdb->get_results($sql);
  $wpdb->hide_errors();
  return array($meta, $results);
}


function get_filters($filters, &$wheres) {
  global $wpdb;
  global $params;

  foreach ($filters as $k=>$filter) {
    $filter = (object)$filter;
    $filter->label = eor($filter->label, $k);

    $par = $k;
    if ($filter->label != $k)
      $par .= ":$filter->label";

    if (!empty($filter->type) && empty($filter->filter_as))
      require_param($par, $filter->type, $filter->default, $filter->required == TRUE);

    if (!isset($_REQUEST[$k]))
      continue;
    $val = trim($_REQUEST[$k]);
    if (empty($val))
      continue;

    if (!empty($filter->filter_as)) {
      $matches = array();
      if (preg_match('/(\w+)(\{.*\}$)/', $filter->filter_as, $matches)) {
        $filter->filter_as = $matches[1];
        $map = (array)json_decode($matches[2]);
        if (isset($map[$val]))
          $val = $map[$val];
      }

      wp_redirect(add_query_arg(array(
        $filter->filter_as => $val,
        $k => NULL)));die;
    }

    switch ($filter->type) {
      case 'expr':
        $wheres[] = build_expr($filter->column, $val);
        break;
      case 'daterange':
        $where = build_date_expr($filter->column, $val);
if (!empty($val) && $where == NULL) {
  pre_dump("ERROR IN DATE");
}
        $wheres[] = $where;
        break;
      case NULL:
        break;
      default:
        $filter->group_func = eor($filter->group_func, $filter->column);
        $filter->group_func = str_replace('THIS', $filter->column, $filter->group_func);
        $wheres[] = $filter->group_func . $wpdb->prepare("=%s", $val);
        break;
    }
  }
}

function draw_groupings($filters, $attr="group_by") { 
  global $params;

  $p = array();
  foreach ($filters as $col=>$filter) {
    $filter = (object)$filter;
    $filter->label = eor($filter->label, $col);

    if (empty($filter->group) && empty($filter->group_func) && empty($filter->group_select))
      continue;

    $id++;

    $selected = FALSE;
    if ($_REQUEST[$attr] == $col) {
      $choice = $col;
      $selected = TRUE;
    }
    
    $field = "<input id='group$id' type='radio' name='$attr' value='" . esc_attr($col) . "'";
    if ($selected)
      $field .= " checked='checked'";
    $field .= "><label for='group$id'>$filter->label</label>";
    $p[] = $field;
  }

  if (count($p) == 0)
    return;

  $params[] = "<br>";
  $params[] = "Group by:";
  $params = array_merge($params, $p);

  $id++;
  $field = "<input id='group$id' type='radio' name='$attr' value=''";
  if (empty($choice))
    $field .= " checked='checked'";
  $field .= "><label for='group$id'>none</label>";
  $params[] = $field;

  return $choice;
}

function require_param($p, $type = 'text', $def = NULL, $required = TRUE)
{
  global $params,$missing_params, $wpdb;

  if (is_array($p)) {
    extract($p);
  }
  else {
    if (empty($type))
      $type = 'text';

    $p = array_map('trim', explode(':', $p));
    if (count($p) == 0)
      return;
    $var = $p[0];
    $label = count($p) > 1 ? $p[1] : $var;
  }

  $rval = $val = $_REQUEST[$var];
  if (empty($val) && $val !== '0' && $required)
    $missing_params = TRUE;

  $field = '';
  switch ($type) {
    case 'blog_dropdown':
      $blogs = $wpdb->get_results("SELECT * FROM wp_blogs ORDER BY blog_id ASC");      
      $field .= '<select name="'.$var.'">';
      foreach($blogs as $b) {
        $field .= '<option '.($val==$b->blog_id?'selected="selected"':'')
          .' value="'.$b->blog_id.'">'.str_replace('.'.$blogs[0]->domain,'',$b->domain).'</option>';  
      }
      $field .= '</select>';
      break;
    case 'checkbox':
      $checked = (isset($_REQUEST['submit']) && ($val == TRUE)) || (!isset($_REQUEST['submit']) && ($def == TRUE));
      $field = esc_html($label) . " <input type='checkbox' name='$var' value='1' ". ($checked ? "checked='checked'" : '') ."/>";
      break;
    case 'dropdown':
      $field .= esc_html($label) . "<select name=\"$var\">";
      foreach ($p['opts'] as $value => $html) {
        if (isset($_REQUEST[$var]) and $_REQUEST[$var] == $value) { 
          $selected = 'selected="selected"';
        }
        else {
          $selected = '';
        }

        $field .= "<option value=\"$value\" $selected >$html</option>";
      }
      $field .= '</select>';
      break;
    case 'tags':
      $rval = as_array($val);
      // fallthrough
    default:
      $field = esc_html($label) . " <input type='text' class='fieldtype-$type' name='$var' size='10' value='" . esc_attr(eor($val, $def)) . "'/>";
      if ($type == 'date')
        $field .= "<span class='instructions'>(YYYY-MM-DD)</span>";
      else if (!empty($val))
        $field .= "<a href=\"" . remove_query_arg($var) . "\" class='remove-filter'>X</a>";
      else if ($type == 'expr')
        $field .= "<span class='expr' title='expression'>*</span>";
      break;
  }

  $params[] = $field;
  return $rval;
}

function editable_field($table, $field, $value, $vals) {
  $s = "<form class='$table editable' action='/ajax.php?edit_field=$table'>";
  foreach ($vals as $k=>$v) {
    $s .= "<input type='hidden' name='$k' value='" . esc_attr($v) . "'>";
  }
  $s .= "<input class='$field' type='text' name='$field' value='" . esc_attr($value) . "'>";
  return $s . "</form>";
}

function format_unpublished($row) {
  if (intval($row->story) > 0) {
    if (!isset($row->recipient))
      $row->recipient = 'edit story';
    $row->story = '<a target="_new" href="http://' . $row->domain . '/publish/?ID=' . $row->story . '"><b>' . xml_entities($row->recipient) . '</b></a>';
  }
  $row->name = '<a target="_new" href="' . SITE_URL . '/members/' . $row->user_login . '">' . xml_entities($row->name) . '</a> <a style="color:#aaa;text-decoration:none;" target="_new" href="/reports.php?report=people&expand=1&x_gc=1&x_deposit=1&x_allocate=1&x_pay=1&x_giveany=1&name=' . $row->donorID . '">#' . $row->donorID . '</a>';
  unset($row->donorID);
  unset($row->user_login);
  unset($row->recipient);

  if ($row->qty > 1)
    $row->gift .= " (x$row->qty)";
  unset($row->qty);

  $dom = explode('.', $row->domain);
  $dom = $dom[0];
  $row->domain = '<a target="_new" style="color:black;" href="http://' . $row->domain . '/publish/">' . $dom . '</a>';

  $date = strtotime($row->date);
  $row->date = draw_date($row->date, FALSE, 10);
  $row->date = '<span style="color:#aaa;">#' . $row->donationId . '</span>' . $row->date;
  unset($row->donationId);

  $row->amount = as_money($row->amount);
  // $row->tip = as_money($row->tip);
  unset($row->tip); // $row->tip = as_money($row->tip);

  $row->notes = editable_field('gift_notes', 'notes', stripslashes($row->notes), array('id'=>$row->gift_id));
  unset($row->gift_id);

  return $row;
}
function report_unpublished() {
  global $wpdb;

  if (require_param('africa', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%africa%'";
  if (require_param('asia', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%asia%'";
  if (require_param('latin:latin america', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%americas%'";
  if (require_param('US', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%states%'";
  if (count($wheres) > 0)
    $wheres = " AND (" . implode(" OR ", $wheres) . ")";
  else
    $wheres = "";

  if (require_param('nolongterm:No long-term gifts', 'checkbox', 0, FALSE))
    $wheres .= " AND not(b.domain in ('wvhoa.seeyourimpact.org','jen.seeyourimpact.org','seattlebsa.seeyourimpact.org','kenyagirls.seeyourimpact.org'))";

  $gift_id = require_param('id:gift#', 'text', '', FALSE);
  if ($gift_id > 0) {
    $wheres .= $wpdb->prepare(" AND (g.id=%d OR g.towards_gift_id=%d)", $gift_id, $gift_id);
    
    $blog_id = $wpdb->get_var($wpdb->prepare(
      "select blog_id from gift where id = %d",
      $gift_id));
    if ($blog_id > 0) {
      $fields = "pm.meta_value as recipient,";
      $tables = "left join wp_{$blog_id}_postmeta pm on pm.post_id=dg.story and pm.meta_key = 'r_Name'";
    }
  }

  $sql = <<<EOF
select
  b.domain, g.displayName as gift,
  donor.id as donorID, donor.firstName as name, u.user_login,
  sum(dg.unitsDonated) as qty, 
  sum(dg.amount) as amount, sum(dg.tip) as tip,
  dg.story, d.donationId, d.donationDate as date, 
  $fields
  dg.id as gift_id, dn.notes
from donationGifts dg
left join donation d on d.donationId=dg.donationId
left join notificationHistory h on h.donorID=d.donorID and h.postID=dg.story and h.blogID=dg.blog_id
left join gift g on dg.giftID=g.id
left join donationGiver donor on donor.ID=d.donorID
left join wp_blogs b on b.blog_id=dg.blog_id
left join wp_users u on u.id=donor.user_id
left join gift_notes dn on dn.ID=dg.ID
$tables
where
  h.postID is null and d.donationDate > '2011-06-01'
  and d.test != 1
  $wheres
group by dg.donationID,dg.story
order by d.donationDate asc
EOF;

  $results = generate($sql, "unpublished donations");
  add_filter('format_report_row', 'format_unpublished');
  return $results;
}

function draw_link($url, $label = NULL) {
  $label = eor($label, $url);

  if (strpos($label, "<span") === FALSE)
    $label = esc_html($label);

  return '<a href="' . esc_url($url) . '" target="_new">' . $label . '</a>';
}

function draw_date($d, $dow = FALSE, $warn_days = 500000) {
  $date = strtotime($d);
  if (empty($date))
    return $d;

  $days = days_since($date);
  if ($days > 50000)
    return '<span class="date" sort="' . $days . '"></span>';

  $label = short_date($date, -9999); // Always show year
  if ($dow)
    $label .= date(' (D)', $date);
  return '<span class="date" sort="' . $days . '">' . $label . ($days > $warn_days ? ' <span style="color:red; font-size:80%;">' . $days . ' days</span>' : '') . '</span>';
}

function draw_money($m, $zero = '-') {
  $t = ($m == 0 ? $zero : as_money($m));
  if ($t == "-$0.00")
    $t = $zero;
  return '<span sort="' . $m . '">' . $t . '</span>';
}

function draw_pct($m, $zero = '-') {
  return '<span sort="' . $m . '">' . round($m * 100.0, 1) . '%</span>';
}

function format_unpublished_stories($row) {
  if (intval($row->story) > 0) {
    if (!isset($row->recipient))
      $row->recipient = 'edit story';
    $row->story = '<a target="_new" href="http://' . $row->domain . '/publish/?ID=' . $row->story . '"><b>' . xml_entities($row->recipient) . '</b></a>';
  }
  unset($row->recipient);

  $dom = explode('.', $row->domain);
  $dom = $dom[0];
  $row->domain = '<a target="_new" style="color:black;" href="http://' . $row->domain . '/publish/">' . $dom . '</a>';

  $row->date = draw_date($row->date, FALSE, 10);
  return $row;
}
function report_unpublished_stories() {
  global $wpdb;

  $blog_id = 86;

  $gift_id = require_param('id:gift#', 'text', '', FALSE);
  $blog_id = $wpdb->get_var($wpdb->prepare(
    "select blog_id from gift where id = %d",
    $gift_id));

  $sql = <<<EOF
select 
  b.domain, p.ID as story, g.displayName as gift,
  post_date as date,
  pm.meta_value as recipient 
from wp_{$blog_id}_posts p
left join wp_{$blog_id}_postmeta pm on pm.post_id=p.id and pm.meta_key='r_Name'
left join wp_18_postmeta pm2 on pm2.post_id=p.id and pm2.meta_key='r_Gifts'
left join gift g on g.id=cast(pm2.meta_value as unsigned integer)
left join wp_blogs b on b.blog_id = {$blog_id}
where post_type='post' and post_status in ('pending', 'draft')
order by post_date desc
EOF;

  $results = generate($sql, "unpublished_stories");
  add_filter('format_report_row', 'format_unpublished_stories');
  return $results;
}

function format_accounts($row) {
  $row->name = "$row->name $row->lastName ($row->email)";
  unset($row->lastName);
  unset($row->email);

  $row->balance = as_money($row->balance);

  if ($row->dateUpdated == $row->dateCreated)
    $row->dateUpdated = "";
  if (!empty($row->dateCreated))
    $row->dateCreated = date("Y-m-d", strtotime($row->dateCreated));
  if (!empty($row->dateUpdated))
    $row->dateUpdated = date("Y-m-d", strtotime($row->dateUpdated));

  if (empty($row->fundraiser))
    $row->fundraiser = "";

  return $row;
}
function report_accounts() {
  global $wpdb;

  $sql = <<<EOF
select
  da.id, dt.name as type,
  dg.firstName as name, dg.lastName, dg.email,
  da.code, da.balance, da.dateCreated, da.dateUpdated, 
  da.event_id as fundraiser, da.params
from donationAcct da
left join donationAcctType dt on dt.id=da.donationAcctTypeId
left join donationGiver dg on dg.id=da.donorId
where not (dt.name = 'discount' and da.balance=0)
order by id desc 
EOF;

  add_filter('format_report_row', 'format_accounts');
  return generate($sql, 'donor account balances');
}


function format_pending($row) {
/*
  if (intval($row->story) > 0)
    $row->story = '<a target="_new" href="http://' . $row->domain . '/publish/?ID=' . $row->story . '"><b>edit story</b></a>';
  $row->name = '<a target="_new" href="' . SITE_URL . '/members/' . $row->user_login . '">' . xml_entities($row->name) . '</a> <a style="color:#aaa;text-decoration:none;" target="_new" href="/reports.php?report=people&expand=1&x_gc=1&x_deposit=1&x_allocate=1&x_pay=1&x_giveany=1&name=' . $row->donorID . '">#' . $row->donorID . '</a>';
  unset($row->donorID);
  unset($row->user_login);

  if ($row->qty > 1)
    $row->displayName .= " (x$row->qty)";
  unset($row->qty);
*/

  $dom = explode('.', $row->domain);
  $dom = $dom[0];
  $row->domain = '<a target="_new" style="color:black;" href="http://' . $row->domain . '/publish/">' . $dom . '</a>';

  if (isset($row->earliest)) { 
    $row->earliest = draw_date($row->earliest);
    $row->latest = draw_date($row->latest);
  }

  if (isset($row->date)) {
    $date = strtotime($row->date);
    $row->date = draw_date($row->date, FALSE, 10);
  }

  $full = $row->amount >= $row->price;
  $row->amount = as_money($row->amount);
  $row->price = as_money($row->price);
  if ($full)
    $row->amount = "<b>$row->amount</b>";

  $url = "/reports.php?left=unpublished&right=unpublished_stories&id=$row->gift_id";
  unset($row->gift_id);
  $row->gift = '<a class="drilldown" target="_new" href="' . $url . '">' . $row->gift . '</a>';

  if ($row->donors > 1 && $row->donors != $row->qty) {
    $p = plural($row->donors, 'donor');
    $row->qty .= " ($p)";
  }
  unset($row->donors);

  return $row;
}

function report_pending() {
  global $wpdb;

  if (require_param('africa', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%africa%'";
  if (require_param('asia', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%asia%'";
  if (require_param('latin:latin america', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%americas%'";
  if (require_param('US', 'checkbox', 0, FALSE))
    $wheres[] = "g.tags like '%states%'";
  if (count($wheres) > 0)
    $wheres = " AND (" . implode(" OR ", $wheres) . ")";
  else
    $wheres = "";

  if (require_param('nolongterm:No long-term gifts', 'checkbox', 0, FALSE))
    $wheres .= " AND not(b.domain in ('wvhoa.seeyourimpact.org','jen.seeyourimpact.org','seattlebsa.seeyourimpact.org','kenyagirls.seeyourimpact.org'))";

  $gift_id = require_param('id:gift#', 'text', '', FALSE);
  if ($gift_id > 0)
    $wheres .= $wpdb->prepare(" AND g.id=%d", $gift_id);

  if ($gift_id > 0) {
    $groups = "g2.id,dg.donationID";
    $fields = <<<EOF
  d.donationDate as date,
  d.donorID as donorID,
  dg.donationID as donationID,
  group_concat(dg.id) as dgID
EOF;
  } else {
    $groups = "g.id"; //,g2.id";
    $fields = <<<EOF
  min(d.donationDate) as earliest,
  max(d.donationDate) as latest
EOF;
  } 

  $sql = <<<EOF
select 
  b.domain,
  g.id as gift_id,
  g.displayName as gift,
  g.unitAmount as price,
  count(distinct d.donorID) as donors,
  count(distinct dg.id) as qty,
  sum(dg.amount) as amount,
  $fields
from gift g
left join gift g2 on g2.active=1 and (g2.id = g.id OR g2.towards_gift_id=g.id)
left join wp_blogs b on g.blog_id=b.blog_id
left join donationGifts dg on dg.giftID = g2.id
left join donation d on dg.donationID=d.donationID
left join notificationHistory nh on nh.donorID=d.donorID and nh.blogID=g.blog_id and nh.postID=dg.story
where g.active = 1 and g.towards_gift_id=0
  and dg.donationID > 3700
  and d.test != 1 
  and nh.notificationID IS null
  $wheres
group by b.domain,$groups
having qty > 0
EOF;

  $results = generate($sql, "pending donations");
  add_filter('format_report_row', 'format_pending');
  return $results;
}

function report_referrals(){
  global $wpdb;
  global $notTest;

  $from = require_param("from:referred by");

  $sql = "SELECT firstName,lastName,df.unitsDonated,displayName,amount,d.tip,referrer "
  ."FROM donation d join donationGiver dg on dg.ID = d.donorID "
  ."JOIN donationGifts df on df.donationID = d.donationID "
  ."JOIN gift g on g.ID = df.giftID";
  $sql = $wpdb->prepare("$sql where $notTest AND referrer=%s", $from);
  return generate($sql, 'donations referred by ' . $from);
}

function report_unallocated(){
  global $wpdb;
  global $notTest;

  $event_id = require_param("event:event #");

  if (!empty($event_id))
    $menu = require_param("menu:gift #s", 'text', '', false);

  if ($event_id == "*") {
    $where = "where event_id>0";
    $name = "all events";
  } else {
    $where = $wpdb->prepare("where event_id=%d", $event_id);
    $name = "event $event_id";
  }

  $sql = "
    select 
      CONCAT(donor.firstName, ' ', donor.lastName) as name, da.dateCreated,
      da.code, da.balance,
      CONCAT('<a target=\"new\" href=\"http://seeyourimpact.org/payments/allocate.php?code=', da.code, '&menu=$menu&go=go\">give</a>') as link,
      da.params,
      event_id as event
    from donationAcct da
    left join donationGiver donor on donor.id=da.owner
    $where
    order by da.balance desc,da.dateCreated desc";

  if ($event_id > 0) {
    $url = get_permalink($event_id);
    ?><a target="_new2" href="<?= add_query_arg('gifts', '', $url) ?>"><?= $url ?></a><?
  }

  $results = generate($sql, "unallocated funds for $name");
  if (!empty($results))
  foreach ($results[1] as $row) {
    $params = json_decode($row->params);
    unset($row->params);

    $tip_rate = ($params->tip_rate);
    $row->amount = as_money(($row->balance / (1 + $tip_rate)))
       . ' + ' . ($tip_rate * 100). '%';
    $row->balance = as_money($row->balance);
    $link = get_campaign_permalink($row->event);
    if ($event_id > 0)
      unset($row->event);
    else
      $row->event = "<a href=\"$link\">$row->event</a> (<a href=\"reports.php?report=unallocated&event=$row->event\">filter</a>)";
  }

  return $results;
}

function report_gifts() {
  $main_blogname = get_blog_option(1,'blogname');

  $sql = "SELECT CONCAT('[',b.blog_id,'] ',REPLACE(b.domain,'.$main_blogname','')) as blog,
    g.id, g.towards_gift_id as tgi,
    IF(tg.displayName IS NOT NULL, CONCAT('".AVG_NAME_PREFIX."',tg.displayName), g.displayName) as name, 
    g.title, g.unitAmount as amt, g.varAmount as var, g.tags
    FROM gift g
    LEFT JOIN wp_blogs b on g.blog_id=b.blog_id
    LEFT JOIN gift tg ON (g.towards_gift_id = tg.id AND g.varAmount=1 
      AND g.unitAmount=".AVG_UNIT_AMOUNT.")
    WHERE g.active = 1
    ORDER BY b.domain, g.id";
//pre_dump($sql);
  return generate($sql, "all gifts");
}

function format_donated_per_gift($row) {
  $row->total_tip = as_money($row->total_tip);
  $row->total_donated = as_money($row->total_donated);
  $row->price_per = "$$row->price_per";
  if (!empty($row->tip_rate))
    $row->tip_rate = number_format($row->tip_rate * 100.0, 1) . "%";
  return $row;
}
function report_donated_per_gift() {
  global $wpdb;

  $date_from = require_param('from', 'date', '2011-01-01');
  $date_to = require_param('to', 'date', '2012-01-01');
  $main_blogname = get_blog_option(1,'blogname');

  $noallocate = require_param('noallocate:remove allocated gifts', 'checkbox', 1, FALSE);
  $wheres[] = "d.donationDate >= '$date_from'";
  $wheres[] = "d.donationDate < '$date_to'";
  if (!empty($noallocate))
    $wheres[] = $wpdb->prepare("(da.id IS NULL OR (da.donationAcctTypeId!=7 AND da.donationAcctTypeId!=4))");

  $sql = "
    SELECT *,
      g.num_donated * g.price_per AS total_donated,
      g.total_tip / (g.num_donated * g.price_per) AS tip_rate
    FROM (
    SELECT 
     CONCAT('[',b.blog_id,'] ',REPLACE(b.domain,'.$main_blogname','')) as charity,
     IF(tg.id IS NOT NULL, CONCAT('".AVG_NAME_PREFIX."',tg.displayName,' [', g.id, ']'), 
       CONCAT('[', g.id, '] ',g.displayName)) as gift,
     CONCAT('[', g2.id, '] ',g2.displayName) as towards,
     COUNT(DISTINCT dg.id) as num_donated,
     CONCAT(g.unitAmount,IF(g.varAmount,'+','')) as price_per,
     g.unitsWanted as avail_now,
     SUM(dg.tip) as total_tip
    FROM gift g
    LEFT JOIN gift g2 ON g2.id=g.towards_gift_id
    LEFT JOIN gift tg ON (tg.id=g.towards_gift_id AND g.varAmount=1 
      AND g.unitAmount=".AVG_UNIT_AMOUNT.")
    LEFT JOIN wp_blogs b on g.blog_id=b.blog_id
    LEFT JOIN donationGifts dg on dg.giftID=g.id
    LEFT JOIN donation d on d.donationID=dg.donationID
    LEFT JOIN payment p on p.id=d.paymentID and p.provider=5
    LEFT JOIN donationAcctTrans dat on dat.paymentID=p.id
    LEFT JOIN donationAcct da on da.id=dat.donationAcctId 
    WHERE g.active = 1 AND b.domain != '' AND
     " . implode(' AND ', $wheres) . "
    GROUP BY g.id
    ORDER BY b.domain,g.id,g2.id)
    AS g
  ";
  add_filter('format_report_row', 'format_donated_per_gift');
  return generate($sql, "donations by gift $date_from to $date_to");
}

function report_gifts_per_tag() {
  global $wpdb;

  $date_from = require_param('from', 'date', '2011-01-01');
  $date_to = require_param('to', 'date', '2012-01-01');
  $main_blogname = get_blog_option(1,'blogname');

  $noallocate = require_param('noallocate:remove allocated gifts', 'checkbox', 1, FALSE);
  $wheres[] = "d.donationDate >= '$date_from'";
  $wheres[] = "d.donationDate < '$date_to'";
  if (!empty($noallocate))
    $wheres[] = $wpdb->prepare("(da.id IS NULL OR (da.donationAcctTypeId!=7 AND da.donationAcctTypeId!=4))");

  // this is the same query as report_donated_per_gift, but it includes the tags
  // of the gift in each row
  $sql = "
    SELECT *,
      g.num_donated * g.price_per AS money,
      g.num_donated AS count,
      g.total_tip / (g.num_donated * g.price_per) AS tip_rate,
      g.tags
    FROM (
    SELECT
     CONCAT('[',b.blog_id,'] ',REPLACE(b.domain,'.$main_blogname','')) as charity,
     IF(tg.id IS NOT NULL, CONCAT('".AVG_NAME_PREFIX."',tg.displayName,' [', g.id, ']'),
       CONCAT('[', g.id, '] ',g.displayName)) as gift,
     CONCAT('[', g2.id, '] ',g2.displayName) as towards,
     COUNT(DISTINCT dg.id) as num_donated,
     CONCAT(g.unitAmount,IF(g.varAmount,'+','')) as price_per,
     g.unitsWanted as avail_now,
     SUM(dg.tip) as total_tip,
     g.tags
    FROM gift g
    LEFT JOIN gift g2 ON g2.id=g.towards_gift_id
    LEFT JOIN gift tg ON (tg.id=g.towards_gift_id AND g.varAmount=1
      AND g.unitAmount=".AVG_UNIT_AMOUNT.")
    LEFT JOIN wp_blogs b on g.blog_id=b.blog_id
    LEFT JOIN donationGifts dg on dg.giftID=g.id
    LEFT JOIN donation d on d.donationID=dg.donationID
    LEFT JOIN payment p on p.id=d.paymentID and p.provider=5
    LEFT JOIN donationAcctTrans dat on dat.paymentID=p.id
    LEFT JOIN donationAcct da on da.id=dat.donationAcctId
    WHERE g.active = 1 AND b.domain != '' AND
     " . implode(' AND ', $wheres) . "
    GROUP BY g.id
    ORDER BY b.domain,g.id,g2.id)
    AS g
  ";

  $rows = $wpdb->get_results($sql);
  $by_tag = array();
  foreach ($rows as $row) {
    foreach (explode(',', $row->tags) as $tag) {
      $tag = trim($tag);
      if (!array_key_exists($tag, $by_tag)) {
        $by_tag[$tag] = array('tag' => $tag, 'money' => 0, 'count' => 0, 'tip_rate' => 0);
      }

      $by_tag[$tag]['money'] += $row->money;
      $by_tag[$tag]['count'] += $row->count;
      $by_tag[$tag]['tip_rate'] = moving_average(
        $by_tag[$tag]['tip_rate'],
        $by_tag[$tag]['count'],
        $row->tip_rate
      );
    }
  }

  usort($by_tag, 'by_tag_cmp');

  $meta = array(
    'title' => "donations by gift $date_from to $date_to (by tag)",
    'desc' => '',
    'script' => '',
    'columns' => null,
  );

  return array($meta, array_map('by_tag_format', array_values($by_tag)));
}

function by_tag_cmp($a, $b) {
  // reverse numeric sort
  if ($a['money'] == $b['money']) {
      return 0;
  }
  return ($a['money'] > $b['money']) ? -1 : 1;
}
function by_tag_format($row) {
  // format each value
  $row['money'] = '$'.commify($row['money']);
  $row['count'] = commify($row['count']);
  $row['tip_rate'] = draw_pct($row['tip_rate']);
  return $row;
}
function commify ($str) {
  $n = strlen($str);
  if ($n <= 3) {
    $return=$str;
  }
  else {
    $pre=substr($str,0,$n-3);
    $post=substr($str,$n-3,3);
    $pre=commify($pre);
    $return="$pre,$post";
  }
  return($return);
}
function moving_average($old_average, $new_count, $new_value) {
  return $old_average - ($old_average/$new_count) + ($new_value/$new_count);
}

function as_and($arr) {
  if (count($arr) == 0)
    return "";

  return "(" . implode(') AND (', $arr) . ")";
}

function hide_email($email) {
  return preg_replace("/(\w{0,3}).*@(\w{0,1}).*(\.\w)/i", "$1*@$2*$3", $email);
}

function common_rows(&$r) {
  if (isset($r->raised))
    $r->raised = draw_money($r->raised);
  if (isset($r->offline))
    $r->offline = draw_money($r->offline);
  if (isset($r->avg_raised))
    $r->avg_raised = draw_money($r->avg_raised);

  if (isset($r->tip))
    $r->tip = draw_money($r->tip);
  if (isset($r->avg_tip))
    $r->avg_tip = draw_money($r->avg_tip);
  if (isset($r->pct_tipping))
    $r->pct_tipping = draw_pct($r->pct_tipping);
  if (isset($r->tip_rate))
    $r->tip_rate = draw_pct($r->tip_rate);
  if (isset($r->avg_donors))
    $r->avg_donors = round($r->avg_donors, 1);
  if (isset($r->per_donor))
    $r->per_donor = draw_money($r->per_donor);
  if (isset($r->goal))
    $r->goal = draw_money($r->goal);
  if (isset($r->avg_goal))
    $r->avg_goal = draw_money($r->avg_goal);

  if (isset($r->date))
    $r->date = draw_date($r->date);
  if (isset($r->created))
    $r->created = draw_date($r->created);
  if (isset($r->started))
    $r->started = draw_date($r->started);
  if (isset($r->ended))
    $r->ended = draw_date($r->ended);
  if (isset($r->start_date))
    $r->start_date = draw_date($r->start_date);
  if (isset($r->end_date))
    $r->end_date = draw_date($r->end_date);
  if (isset($r->first_donated))
    $r->first_donated = draw_date($r->first_donated);
  if (isset($r->last_donated))
    $r->last_donated = draw_date($r->last_donated);
}

function format_campaign_stats($r) {
  if (isset($r->ID)) {
    $r->status = (has_tag('private',$r->ID) || !has_tag('public',$r->ID)?'<i>private</i>':'<b>public</b>');
    $link = get_permalink($r->ID);
    if (trim($r->title) == '')
      $r->title = '(no title)';
    $r->title = '<a href="' . $link . '" target="_new">' . esc_html($r->title) . '</a>';
  }

  if (isset($r->unallocated)) {
    $amt = $r->unallocated;
    $r->unallocated = draw_money($r->unallocated);
    if (isset($r->ID) && $amt > 0) {
      $r->unallocated = '<a target="_new" href="/reports.php?report=unallocated&event=' . $r->ID . '">' . $r->unallocated . '</a>';
    }
  }

  common_rows($r);
  return $r;
}

function report_campaign_stats() {
  global $wpdb;

  // Update campaigns that are missing
  $ids = $wpdb->get_col($sql = 
   "SELECT c.ID
    FROM wp_1_posts c
    LEFT JOIN campaigns cs ON c.ID = cs.post_id
    WHERE c.post_type='event'
    AND cs.post_id IS NULL");
  foreach($ids as $id) {
    update_campaign_stats($id);
  }

  $wheres = array("c.post_type = 'event'");

  $select = "
    c.ID, c.post_title as title,
    cs.donors_count as donors, cs.gifts_count as gifts,
    cs.goal, cs.raised, (cs.raised/cs.donors_count) as per_donor,
    cs.tip, (cs.tip / cs.raised) as tip_rate, 
    cs.offline,
    SUM(da.balance) AS unallocated,
    -- count(DISTINCT i.id) as invites,
    c.post_date as created, cs.start_date as started, -- cs.first_donated, 
    cs.last_donated, cs.end_date as ended,
    fn.meta_value as first, ln.meta_value as last,
    u.user_email as email,
    cs.theme, '' as status";

  $group_select = "
    SUM(donors) as donors, AVG(donors) as avg_donors,
    AVG(per_donor) as per_donor,
    AVG(goal) as avg_goal,
    SUM(raised) as raised, AVG(raised) as avg_raised,
    SUM(tip) as tip, AVG(tip) as avg_tip,
    SUM(offline) as offline,
    AVG(tip_rate) as tip_rate, SUM(invites) as invites";

  $sql = "
    {{SELECT}}
    FROM wp_1_posts c
    LEFT JOIN campaigns cs ON c.ID = cs.post_id
    LEFT JOIN wp_1_posts p ON p.id = c.ID
    LEFT JOIN wp_users u on u.id=p.post_author
    LEFT JOIN wp_usermeta fn on fn.user_id = u.id AND fn.meta_key='first_name'
    LEFT JOIN wp_usermeta ln on ln.user_id = u.id AND ln.meta_key='last_name'
    -- LEFT JOIN invite i on i.context=CONCAT('campaign/', cs.post_id)
    LEFT JOIN donationAcct da on (da.event_id = c.ID) AND (IFNULL(da.event_id,0) != 0)
    {{WHERE}}
    GROUP BY c.ID
    ORDER BY c.ID DESC
  ";

  $cols['theme'] = array(
    'column' => 'cs.theme',
    'type' => 'expr',
    'group_select' => $group_select
  );
  $cols['created'] = array(
    'label' => 'campaign created',
    'column' => 'c.post_date',
    'type' => 'daterange',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m')",
    'group_select' => $group_select
  );
  $cols['donor_count'] = array(
    'label' => '# of donors',
    'column' => 'cs.donors_count',
    'type' => 'expr',
    'group_func' => "IF(THIS=0, '0', IF(THIS<5, '1-4', IF(THIS<10, '5-9', '10+')))",
    'group_select' => $group_select
  );

  add_filter('format_report_row', 'format_campaign_stats');
  return generate(array(
    'sql' => $sql, 
    'title' => "campaign stats", 
    'wheres' => $wheres, 
    'cols' => $cols,
    'select' => $select,
    'columns' => array(
      'ID' => 'int',
      'first' => 'string',
      'last' => 'string',
      'email' => 'email',
      'goal' => 'money',
      'raised' => 'money',
      'tip' => 'money',
      'tip_rate' => 'percent',
      'offline' => 'money',
      'donors' => 'int',
      'gifts' => 'int',
      'per_donor' => 'money',
      'unallocated' => 'money',
      'created' => 'date',
      'started' => 'date',
      'last_donated' => 'date',
      'ended' => 'date',
      'theme' => TRUE,
      'title' => TRUE,
      'status' => TRUE
    )
  ));
}   

function report_campaign_activity() {
  global $wpdb;

  $group_select = "
    COUNT(DISTINCT user_id) as donors,
    AVG(raised) as avg_action,
    SUM(raised) as raised, 
    SUM(tip) as tip,
    COUNT(DISTINCT IF(tip > 0,user_id,NULL)) as tipped,
    COUNT(DISTINCT IF(tip > 0,user_id,NULL)) / COUNT(DISTINCT user_id) as pct_tipping,
    (SUM(tip) / SUM(raised)) as tip_rate";

  $select = "activity.eventID as ID, activity.title, activity.date, activity.donationID, activity.donor, activity.email, activity.user_id, activity.raised, activity.tip, (activity.tip / activity.raised) as tip_rate, activity.displayName, unallocated, code, c.theme";

  $cols['ID'] = array(
    'label' => 'campaign ID',
    'column' => 'IFNULL(activity.eventID,0)',
    'type' => 'expr',
    'group_select' => "title, $group_select, theme"
  );
  
  $cols['is_campaign'] = array(
    'label' => 'campaign/organic',
    'column' => "IF(activity.eventID > 0, 'campaign', 'organic')",
    'filter_as' => 'ID{"campaign":">0", "organic":"=0"}',
    'type' => 'int',
    'group_select' => $group_select
  );

  $cols['date'] = array(
    'column' => 'activity.date',
    'type' => 'daterange',
    'default' => '',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m/%d')",
    'group_select' => $group_select
  );

  $cols['month'] = array(
    'column' => 'activity.date',
    'filter_as' => 'date',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m')",
    'group_select' => $group_select
  );

  $cols['theme'] = array(
    'column' => 'c.theme',
    'type' => 'expr',
    'group_select' => $group_select
  );

  $cols['sum'] = array(
    'column' => "'total'",
    'group_select' => $group_select
  );

  if ($_REQUEST['debug']) {
    $cols['ID']['group_select'] .= ",cr,ct";
    $select .= ", c.raised as cr, c.tip as ct, c.offline as coff, activity.kind";
  }

  $sql = $wpdb->prepare("
    {{SELECT}}
    FROM ((SELECT 
        wp.ID as eventID,        
        wp.post_title as title,        
        d.donationID, 
        CONCAT(donor.firstName, ' ', donor.lastName) as donor,
        donor.email,
        donor.user_id,
        SUM(dg.amount) as raised,
        SUM(dg.tip) as tip,
        GROUP_CONCAT(DISTINCT IFNULL(tg.displayName,g.displayName) SEPARATOR ', ') as displayName,     
        d.donationDate AS date,
        'PURCHASE' as kind,
        0 as unallocated, '' as code
      FROM donationGifts dg
      JOIN donation d ON d.donationID = dg.donationID
      JOIN donationGiver donor ON donor.ID = d.donorID
      JOIN payment p ON d.paymentID = p.id
      LEFT JOIN wp_1_posts wp ON wp.ID = dg.event_id 
      LEFT JOIN gift g ON dg.giftID = g.ID 
      LEFT JOIN gift tg ON (g.towards_gift_id=tg.id AND g.varAmount=1 AND g.unitAmount=%d) 
      LEFT JOIN donationAcctTrans dat ON dat.paymentID = p.id AND p.provider = 5
      LEFT JOIN donationAcct da ON dat.donationAcctId = da.id   
      WHERE d.test=0
        -- AND dg.matchingDonationAcctTrans=0 
        AND (da.id IS NULL OR (da.donationAcctTypeId != 4 AND da.event_id != dg.event_id))
      GROUP BY d.donationID, dg.event_id
    ) UNION (
      SELECT 
        wp.ID as eventID,        
        wp.post_title as title,        
        d.donationId AS donationID, 
        CONCAT(donor.firstName, ' ', donor.lastName) as donor,
        donor.email,
        donor.user_id,
        IFNULL(dat.amount, 0) * (p1.amount/(p1.amount+p1.tip)) as raised,
        IFNULL(dat.amount, 0) * (p1.tip/(p1.amount+p1.tip)) as tip,
        '' as displayName,
        dat.dateInserted as date,
        dat.kind as kind,
        da.balance as unallocated, da.code as code
      FROM donationAcct da
      JOIN donationAcctTrans dat ON dat.donationAcctId = da.id AND dat.amount > 0
      LEFT JOIN payment p1 ON p1.id=dat.paymentID
      LEFT JOIN donation d on d.paymentID=p1.ID
      JOIN donationGiver donor ON da.owner = donor.ID
      LEFT JOIN wp_1_posts wp ON wp.ID = da.event_id 
      LEFT JOIN donationAcctTrans dat2 ON dat2.paymentID=p1.id AND dat2.donationAcctId != da.id AND dat2.amount < 0
      LEFT JOIN donationAcct da2 on dat2.donationAcctId = da2.id
      WHERE d.donationID > 0 AND NOT (IFNULL(da2.donationAcctTypeId,0) = 7 and da2.event_id = da.event_id)
        AND da.donationAcctTypeId = 7 -- Excludes purchase of GCs or contributions to funds
    )) as activity
    LEFT JOIN campaigns c ON activity.eventID=c.post_id
    {{WHERE}}
    ORDER BY `date` DESC
    ", AVG_UNIT_AMOUNT); 

  add_filter('format_report_row', 'format_campaign_activity');
  return generate(array(
    'sql' => $sql,
    'select' => $select,
    'title' => 'donations',
    'expensive' => TRUE,
    'wheres' => $wheres,
    'cols' => $cols
  ));
}
function format_campaign_activity($r) {
  if (isset($r->cr) && $r->cr != $r->raised)
    $r->cr = '<span style="color:red;">' . $r->cr . '</span>';
  if (isset($r->ct) && $r->ct != $r->tip)
    $r->ct = '<span style="color:red;">' . $r->ct . '</span>';

  if (isset($r->title)) {
    $link = get_permalink($r->ID);
    $r->title = '<a href="' . $link . '" target="_new">' . xml_entities($r->title) . '</a>';
  }

  if (isset($r->donor)) {
    if (isset($r->user_id))
      $r->donor = draw_link(get_member_link($r->user_id), $r->donor);
    unset($r->user_id);
  }

  if (isset($r->date))
    $r->date = draw_date($r->date, TRUE);

  if (isset($r->avg_action))
    $r->avg_action = draw_money($r->avg_action);

  if (isset($r->unallocated)) {
    if ($r->unallocated > 0 && !empty($r->code))
      $r->unallocated = draw_link("/payments/allocate.php?code=$r->code&event=$r->ID", draw_money($r->unallocated));
    else 
      $r->unallocated = draw_money($r->unallocated);
  }
  unset($r->code);

  common_rows($r);

  return $r;
}



function report_stories() {
  global $wpdb;

/*
  $group_select = "
    COUNT(DISTINCT user_id) as donors,
    SUM(raised) as raised, AVG(raised) as avg_action,
    SUM(tip) as tip,
    (SUM(tip) / SUM(raised)) as tip_rate,
    SUM(unallocated) as unallocated"; 
*/

  $select = "SUBSTRING_INDEX(b.domain,'.',1) as charity,
    story.post_id as ID, story.gift_id, 
    story.post_title as title, story.guid as url, 
    story.post_status as status, story.post_modified as date, story.featured";

  $group_select = "SUM(featured) as featured";

  $cols['date'] = array(
    'column' => 'story.post_modified',
    'type' => 'daterange',
    'default' => '',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m/%d')",
    'group_select' => $group_select
  );

  $cols['month'] = array(
    'column' => 'story.post_modified',
    'filter_as' => 'date',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m')",
    'group_select' => $group_select
  );

  $cols['charity'] = array(
    'column' => "SUBSTRING_INDEX(b.domain,'.',1)",
    'type' => 'expr',
    'group_select' => $group_select
  );

  $cols['sum'] = array(
    'column' => "'total'",
    'group_select' => $group_select
  );

  $sql = "
    {{SELECT}}
    FROM donationStory story
    LEFT JOIN wp_blogs b on b.blog_id=story.blog_id
    {{WHERE}}
    ORDER BY `date` DESC";

  add_filter('format_report_row', 'format_stories');
  return generate(array(
    'sql' => $sql,
    'select' => $select,
    'title' => 'stories',
    // 'expensive' => TRUE,
    'wheres' => $wheres,
    'cols' => $cols
  ));
}
function format_stories($r) {
  if (isset($r->url)) {
    $r->title = draw_link($r->url, $r->title);
    unset($r->url);
  }

  common_rows($r);
  return $r;
}








function report_invite_referrer() {
  global $wpdb;    

  $sql = "SELECT 
  c.id as Cart, c.lastUpdated as Updated, c.status as Status,
  u.ID as dID, u.user_login as dLogin, u.user_email as dEmail, 
  u2.ID as rID, u2.user_login as rLogin, u2.user_email as rEmail
  FROM cart c
  LEFT JOIN wp_users u ON (u.ID = c.userID)
  LEFT JOIN invite iv ON (iv.id = c.referrer)
  LEFT JOIN invitation ivn ON (ivn.id = iv.invitation_id)
  LEFT JOIN wp_users u2 ON (u2.ID = ivn.user_id)
  WHERE referrer > 0 ORDER BY c.id";
  return generate($sql, 'invite referrer');    
}

function report_story_mismatch() {
  global $wpdb;    

  $wheres = array();
  $date_since = require_param('since', 'date', '2011-01-01');
  $blog = require_param('blog', 'blog_dropdown', '');

  if (!empty($blog) && $blog>1)
    $wheres[] = $wpdb->prepare("dg.blog_id = %d", $blog);  

  $sql = "SELECT FROM donationStory  
  WHERE $wheres ORDER BY date_added DESC, IF(status='pending',0,IF(status='failed',1,2)) ASC";    
  return generate($sql, 'story mismatch');

}

function report_invite_queue() {
  global $wpdb;
  $wheres = array();

  $date_since = require_param('since', 'date', '2011-01-01');
  if (!empty($date_since))
    $wheres[] = $wpdb->prepare("date_added >= %s", $date_since);

  $wheres = "(" . implode(') and (', $wheres) . ")";
    
  $sql = "SELECT 
  ivn.id as ivnID,
  iv.id as ivID,
  context,
  date_added,
  iv.status,
  iv.date_sent,
  iv.email as recipient,
--  message,
  process_time
  FROM invite iv JOIN invitation ivn ON  iv.invitation_id = ivn.id 
  WHERE $wheres ORDER BY date_added DESC, IF(status='pending',0,IF(status='failed',1,2)) ASC";    

  return generate($sql, 'invite queue');
}

function report_story_delay() {
  global $wpdb;

  $wheres = array();

  $blog = require_param('blog', 'blog_dropdown', '');
  if (!empty($blog) && $blog>1)
    $wheres[] = $wpdb->prepare("dg.blog_id = %d", $blog);

  $date_since = require_param('since', 'date', '2011-01-01');
  if (!empty($date_since))
    $wheres[] = $wpdb->prepare("d.donationDate >= %s", $date_since);

  $date_upto = require_param('upto', 'date', date('Y-m-d'));
  if (!empty($date_upto))
    $wheres[] = $wpdb->prepare("d.donationDate <= %s", $date_upto);

  $tags = require_param('exclude:excl. tags', 'tags', '', FALSE);
  if (!empty($tags)) {
    foreach ($tags as $tag) {
      $wheres[] = $wpdb->prepare(" NOT (IF(tg.id IS NOT NULL, tg.tags, g.tags) LIKE %s) ", "%$tag%");
    }
  }

//  $nostory = require_param('nostory:without story only', 'checkbox', 1);
//  if (!empty($nostory))
//    $wheres[] = $wpdb->prepare("dg.story=0 OR dg.story IS NULL");

  $completed = require_param('completed', 'checkbox', FALSE, false);
  if (!empty($completed))
    $wheres[] = "g2.current_amount IS NULL OR g2.current_amount = 0";

  $unpublished = require_param('unpublished:unpublished', 'checkbox', 1, false);
  if (!empty($unpublished))
    $wheres[] = "ds.post_id IS NULL";
  
  $unsent = require_param('unsent:unsent', 'checkbox', 1, false);
  if (!empty($unsent))
    $wheres[] = $wpdb->prepare(" 
      (n.emailSubject IS NULL AND n2.emailSubject IS NULL)
        OR (n.success IS NOT NULL AND n.success=0 AND wum.meta_value!=1)
        OR (n2.success IS NOT NULL AND n2.success=0 AND wum.meta_value!=1)");
  $real = require_param('real:not tests', 'checkbox', 1, false);
  if (!empty($real))
    $wheres[] = $wpdb->prepare("dg.blog_id <> 17 AND d.testData = 0");
  $wheres = "(" . implode(') AND (', $wheres) . ")";
  $main_blogname = get_blog_option(1,'blogname');

  $sql = "SELECT dg.donationID AS `dID`, dg.ID as `dgID`, 
    CONCAT('[',don.ID,'] ',don.firstName,' ', don.lastName) AS donor, 
    di.donorType AS `type`, 
    DATE_FORMAT(d.donationDate,'%m/%d') AS `paid`, 
    IF(tg.displayName IS NOT NULL, 
      CONCAT('[',g.id,'] $',dg.amount,' towards...'), 
      CONCAT('[',g.id,'] ',g.displayName)
      ) AS gift,
    IF(g2.id IS NOT NULL, 
      CONCAT('[',g2.id,'] ',g2.displayName, 
        IF(g2.current_amount > 0, CONCAT(' (', ROUND(g2.current_amount/g2.unitAmount * 100) ,'%)'), '')),
      ''
      ) AS towards,
    g.towards_gift_id AS agg, 
    REPLACE(b.domain, '.$main_blogname','') AS chrty, 
    b.blog_id AS cID,
    IF(dg.story IS NULL,0,dg.story) AS pID,
    IF(n.notificationID IS NULL, CONCAT('*',n2.notificationID), n.notificationID) AS nID,
    DATE_FORMAT(IF(n.sentDate IS NULL, n2.sentDate, n.sentDate),'%m/%d') as sent,
    DATEDIFF(IF(n.sentDate IS NULL, 
      IF(n2.sentDate IS NULL, NOW(), n2.sentDate), n.sentDate), d.donationDate) AS days, 
    IF(tg.id IS NOT NULL, tg.tags, g.tags) AS tags

    FROM donationGifts dg 
    LEFT JOIN donation d ON dg.donationID=d.donationID
    LEFT JOIN donationGiver don ON don.ID = d.donorID
    LEFT JOIN donorInfo di ON di.donorID = d.donorID
    LEFT JOIN wp_usermeta wum ON (wum.user_id = don.user_id AND meta_key='no_story_email') 
    LEFT JOIN notificationHistory n ON (
      n.donationID = d.donationID 
      AND n.postID = dg.story 
      AND n.mailType IN (2,6) 
      AND (n.success < 2 OR (n.success=0 AND wum.meta_value=1))
    )
    LEFT JOIN notificationHistory n2 ON (      
      n.notificationID IS NULL
      AND n2.donationID <> d.donationID
      AND n2.donorID = don.ID
      AND n2.postID = dg.story 
      AND n2.blogID = dg.blog_id
      AND n2.sentDate >= d.donationDate
      AND n2.mailType IN (2,6) 
      AND (n2.success < 2 OR (n2.success=0 AND wum.meta_value=1))
    )
    LEFT JOIN gift g ON g.id = dg.giftID
    LEFT JOIN gift g2 ON g.towards_gift_id = g2.id
    LEFT JOIN gift tg ON (g.towards_gift_id = tg.id AND g.varAmount=1 AND g.unitAmount=".AVG_UNIT_AMOUNT.")
    LEFT JOIN wp_blogs b ON b.blog_id = g.blog_id
    LEFT JOIN donationStory ds ON (ds.blog_id = g.blog_id AND ds.post_id = dg.story)  
    WHERE  
      $wheres

    ORDER BY 
      -- CONCAT(cID, dg.story),
      ISNULL(nID) DESC, days DESC, d.donationDate ASC
    ";

//-- IF(ds.post_modified IS NULL, '', DATE_FORMAT(ds.post_modified,'%m/%d')) AS pub,
//-- IF(n.blogID IS NULL, CONCAT('*',n2.blogID), n.blogID) AS ncID,
//-- IF(n.postID IS NULL, CONCAT('*',n2.postID), n.postID) AS npID,
//-- CONCAT(SUBSTR(n.emailSubject ,1,40),'...') AS `subject`,      
//-- , IF(n.success IS NULL, IF(n2.success IS NULL,'x',CONCAT('*',n2.success)), n.success) AS ns
//-- IF(wum.meta_value IS NULL, 0, wum.meta_value) AS nse


  if ($_REQUEST['sql']=='yes')
    pre_dump($sql);

  return generate($sql, 'impact story delay');
}

function report_checkout_failures() {
  global $wpdb;

  $sql = "select 
      dg.firstName,dg.lastName, dg.email,
      p.dateTime,p.raw,
      CONCAT('http://seeyourimpact.org/members/', u.user_login) as url
    from payment p 
    left join donation d on d.paymentID=p.id
    left join donationGiver dg on d.donorID=dg.id
    left join donorInfo info on info.donorID=dg.id
    left join wp_users u on u.id=dg.user_id
    where p.provider=3 and p.amount=0 and not (dg.firstName is NULL)
    order by dateTime desc";

  $results = generate($sql, 'checkout failures');
  foreach ($results[1] as $row) {
    $amt = get_paid_amt(3, $row->raw, 0, '');
    $row->amount = as_money($amt);
    unset($row->raw);
  }
  return $results;
}

function report_donor_retention() {
  global $wpdb;

  $wpdb->query("
create temporary table t_donorDates
select DATE(p.dateTime) as payDate,d.donorID,sum(p.amount) as amount 
from payment p
left join donation d on d.paymentID=p.id
left join donationAcctTrans dat on dat.paymentID=p.id
where
  (p.provider != 5 and p.provider != 0)
  and not(d.donorID is NULL)
  and not (d.test = 1)
group by payDate,d.donorID
order by payDate
");

$wpdb->query("
create temporary table t_retention
select
   d.donorID,donor.firstName,donor.lastName,donor.email,info.donorType,
   count(*) as numDonations,sum(d.amount) as amount,
   group_concat(d.payDate) as dates,
   group_concat(d.amount) as amounts,
   datediff(max(d.payDate),min(d.payDate))+1 as spread,
   min(d.payDate) as firstDate
from t_donorDates d
left join donorInfo info on d.donorID=info.donorID
left join donationGiver donor on donor.ID=d.donorID
group by d.donorID
order by numDonations desc
");

$sql = " 
  select pp1.*,pp2.amount as firstAmount
  from t_retention pp1
  left join t_donorDates pp2 on pp2.donorID=pp1.donorID AND pp2.payDate=pp1.firstDate
";

  return generate($sql, "donor retention");
}

function build_date_expr($var, $expr) {
  global $wpdb;

  $expr = str_replace(' ','', $expr);

  $matches = array();
  if (preg_match('/^(\!|not)/', $expr, $matches)) {
    return "not (" . build_date_expr($var, str_replace($matches[1], '', $expr)) . ")";
  }

  if ($expr == "empty") {
    return "IFNULL($var,'') = ''";
  }

  // <=, =, >=, >, <
  if (preg_match('/(\<\=|\<|\=|\>|\>=)(.*)/', $expr, $matches)) {
    return $wpdb->prepare("$var {$matches[1]} %s", $matches[2]);
  }

  // A particular month
  if (preg_match('/^(?<year>\d\d\d\d)\/(?<month>\d?\d)(?<day>\/\d?\d)?$/', $expr, $matches) ||
      preg_match('/^(?<month>\d?\d)(?<day>\/\d?\d)?\/(?<year>\d\d\d\d)(-(?<month2>\d?\d)(?<day2>\/\d?\d)?(\/(?<year2>\d\d\d\d)?))?$/', $expr, $matches) ||
      preg_match('/^(?<year>\d\d\d\d)(-(?<year2>\d\d\d\d))?$/', $expr, $matches)) {
    $y = str_replace('/','', $matches['year']);
    $m = str_replace('/','', $matches['month']);
    $d = str_replace('/','', $matches['day']);
    $period = 'day';

    if (empty($d)) {
      $d = "01";
      $period = "month";
    } else if (strlen($d) == 1)
      $d = "0$d";

    if (empty($m)) {
      $m = "01";
      $period = "year";
    } else if (strlen($m) == 1)
      $m = "0$m";

    $from = "$y-$m-$d";

    if (!empty($matches['day2'])) {
      $y = eor(str_replace('/','', $matches['year2']), $y);
      $m = eor(str_replace('/','', $matches['month2']), $m);
      $d = str_replace('/','', $matches['day2']);

      $to = date("Y-m-d", strtotime("$y-$m-$d +1 day"));
    } else if (!empty($matches['month2'])) {
      $y = eor(str_replace('/','', $matches['year2']), $y);
      $m = str_replace('/','', $matches['month2']);

      $to = date("Y-m-d", strtotime("$y-$m-01 +1 month"));
    } else if (!empty($matches['year2'])) {
      $y = str_replace('/','', $matches['year2']);

      $to = date("Y-m-d", strtotime("$y-01-01 +1 year"));
    } else 
      $to = date("Y-m-d", strtotime("$from +1 $period"));
  }

  if ($from && $to)
    return $wpdb->prepare("($var >= %s) AND ($var < %s)", $from, $to);

  return NULL; 
}

function build_expr($var, $expr) {
  global $wpdb;

  $matches = array();
  if (preg_match('/^(\!|not )/', $expr, $matches)) {
    return "not (" . build_expr($var, str_replace($matches[1], '', $expr)) . ")";
  }

  $old_expr = $expr;
  $expr = str_replace(' ','', $expr);

  if ($expr == "empty" || $expr == "(none)") {
    return "IFNULL($var,'') = ''";
  }

  // X%
  while (preg_match('/([-+]?[0-9]*\.?[0-9]+)\%/', $expr, $matches)) {
    $expr = str_replace($matches[0], $matches[1] / 100.0, $expr);
  }

  // X-Y
  if (preg_match('/([-+]?[0-9]*\.?[0-9]+)(\-|to)([-+]?[0-9]*\.?[0-9]+)/', $expr, $matches)) {
    return $wpdb->prepare("$var >= %s and $var <= %s", $matches[1], $matches[3]);
  }

  // X+
  if (preg_match('/([-+]?[0-9]*\.?[0-9]+)\+/', $expr, $matches)) {
    return $wpdb->prepare("$var >= %s", $matches[1]);
  }

  // ~X
  if (preg_match('/\~(.*)/', $expr, $matches)) {
    $s = str_replace("'",'', $wpdb->prepare("%s", $matches[1]));
    return "$var LIKE '%$s%'";
  }

  // <=, =, >=, >, <
  if (preg_match('/(\<\=|\<|\=|\>|\>=)(.*)/', $expr, $matches)) {
    return $wpdb->prepare("$var {$matches[1]} %s", $matches[2]);
  }

  // X,Y,Z
  $words = array_map('trim', as_array($old_expr));
  if (count($words) > 1) {
    for ($i = 0; $i < count($words); $i++) {
      $words[$i] = $wpdb->prepare('%s', $words[$i]);
    }
    return "$var in (" . implode(',', $words) . ")";
  } else if (count($words) == 1) {
    return $wpdb->prepare("$var = %s", $words[0]);
  }

  return NULL;
}

function report_people() {
  global $wpdb, $params;

  $wheres = array();
  $having = array();

  $name = require_param('name:Name', 'text', '', false);
  $type = require_param('type:Donor Type', 'expr', '', false);
  $main = require_param('main:is main', 'checkbox', false, false);
  $params[] = '<br style="clear:both;"><span class="param">Interactions: </span>';
  $event_id = require_param('event_id:Campaign ID', 'expr', '', false);
  $x_pay = require_param('x_pay:pay', 'checkbox', true, false);
  $x_giveany = require_param('x_giveany:give-any', 'checkbox', true, false);
  $x_gc = require_param('x_gc:buy/spend gc', 'checkbox', false, false);
  $x_match = require_param('x_match:matched', 'checkbox', false, false);
  $x_deposit = require_param('x_deposit:deposit(recurly)', 'checkbox', false, false);
  $x_allocate = require_param('x_allocate:allocated', 'checkbox', false, false);
  $expand = require_param('expand', 'checkbox', false, false);
  $combine = require_param('combine', 'checkbox', false, false);
  $params[] = '<br style="clear:both;"><span class="param">Period: </span>';
  $from = require_param('from', 'date', '', false);
  $to = require_param('to', 'date', '', false);
  $num_days = require_param('num_days:# of active days', 'expr', '', false);
  $num_gifts = require_param('num_gifts:# of gifts', 'expr', '', false);
  $params[] = '<br style="clear:both;"><span class="param">Overall stats: </span>';
  $tiprate = require_param('tiprate:Tip', 'expr', '', false);
  $gifts2010 = require_param('gifts2010:Gifts in 2010', 'expr', '', false);
  $gifts2011 = require_param('gifts2011:Gifts in 2011', 'expr', '', false);
  $gifts = require_param('gifts:Gifts (Total)', 'expr', '', false);
  $total = require_param('total:$ (Total)', 'expr', '', false);
  $params[] = '<br style="clear:both;"><span class="param">Demographics: </span>';
  $tags = require_param('tags', 'expr', '', false);
  $set_tag = require_param('set_tag:set tag', 'text', '', false);

  if ($type !== '' && $type !== NULL)
    $wheres[] = build_expr("IFNULL(di.donorType, 0)", $type);
  if ($main)
    $wheres[] = "dg.main = 1";
  $c = count($wheres);

  $types = array();
  if (!$x_match)
    $types[] = "'match'";
  if (!$x_pay) 
    $types[] = "'pay'";
  if (!$x_giveany) 
    $types[] = "'giveany'";
  if (!$x_gc) {
    $types[] = "'buy_gc'";
    $types[] = "'spend_gc'";
  }
  if (!$x_deposit)
    $types[] = "'deposit'";
  if (!$x_allocate)
    $types[] = "'allocate'";
  if (count($types) > 0)
    $wheres[] = "not(t.type in (" . implode(',', $types) . "))";

  if ($combine)
    $combine = "";
  else
    $combine = ",t.type";

  $filters = "";
  if (!empty($from))
    $filters .= " and " . $wpdb->prepare("p.dateTime >= %s", $from);
  if (!empty($to))
    $filters .= " and " . $wpdb->prepare("p.dateTime < %s", $to);

  if (!empty($name)) {
    if (absint($name) > 0) {
      $wheres[] = $wpdb->prepare("dg.id = %d", $name);
    } else {
      $name .= "%";
      $wheres[] = $wpdb->prepare("dg.firstName like %s OR dg.lastName like %s OR u.user_login like %s", $name, $name, $name);
    }
  }
  if (!($event_id === '' || $event_id === NULL))
    $wheres[] = build_expr("t.eid", $event_id);
  if (!empty($tiprate))
    $wheres[] = build_expr("di.tip_rate", $tiprate);
  if (!empty($gifts2010))
    $wheres[] = build_expr("di.gifts2010", $gifts2010);
  if (!empty($gifts2011))
    $wheres[] = build_expr("di.gifts2011", $gifts2011);
  if (!empty($gifts))
    $wheres[] = build_expr("(di.gifts2011 + di.gifts2010)", $gifts);
  if (!empty($total))
    $wheres[] = build_expr("(di.total2011 + di.total2010)", $total);
  if (!empty($num_days)) 
    $having[] = build_expr('num_days', $num_days);
  if (!empty($num_gifts))
    $having[] = build_expr('num_gifts', $num_gifts);
  if (!empty($tags))
    $having[] = build_expr("IFNULL(demo_tag,'')", $tags);


/*
  if (($c >= count($wheres)) || (count($having) > 0))
    return null;
*/
  $wheres = '(' . implode(")\n  AND (", array_filter($wheres)) . ')';

  if (count($having) > 0) {
    $having = "\nHAVING (" . implode(")\n  AND (", $having) . ')';
  } else
    $having = "";

  // $expand = TRUE; // TRUE to split all ledger lines
  if ($expand) {
    $expand = $expand ? "RAND()," : "";
    $expand2 = $expand ? "DATE(t.date),t.eid," : "";
  }
  else 
    $expand = $expand2 = "";

  $sql = <<<EOF
create temporary table t_donorActivity
select
  p.id,p.provider,p.amount,dg.ID as donorID,dg.user_id as userID,dg.email,
  p.dateTime as date,
  IFNULL(IFNULL(gift.event_id, da.event_id),0) as eid,
  IF(IFNULL(da.donationAcctTypeId,0)=7,IF(p.provider=5,'allocate',IF(p.provider=9,'deposit','giveany')),
    IF(IFNULL(da.donationAcctTypeId,0)=4 and IFNULL(gift.matchingDonationAcctTrans,0)>0, 'match',
      IF(IFNULL(da.donationAcctTypeId,0) in (3,6), IF(dat.amount>0,'buy_gc','spend_gc'),
        'pay'))) as type,
  IFNULL(SUM(gift.amount),
    IF(da.donationAcctTypeId != 5 and IFNULL(dat.amount,0) < 0, IFNULL(-dat.amount-p.tip,0), p.amount)) as paid,
  IFNULL(SUM(gift.tip), IFNULL(p.tip,'')) as tip,
  IFNULL(group_concat(distinct gift.id),'') as gifts,
  count(distinct gift.id) as num_gifts
from donationGiver dg
left join donation d on d.donorID=dg.id
left join payment p on d.paymentID=p.id
left join donationAcctTrans dat on dat.paymentID=p.id
left join donationAcct da on dat.donationAcctId=da.id
left join donationGifts gift on gift.donationId=d.donationId and IFNULL(gift.matchingDonationAcctTrans,0)=IFNULL(dat.id,0)
where
  (d.test is NULL or d.test != 1)
  and not (IFNULL(p.provider,0) = 10 and p.amount = 0) -- XFER
  $filters
group by {$expand}donorID,p.id,eid,type
order by date,donorID
EOF;

  if ($_REQUEST['sql'] == 'yes') {
    pre_dump($sql . ";");
  }
  $wpdb->query($sql);
  $wpdb->query("alter table t_donorActivity ADD index (donorID)");
  $wpdb->query("alter table t_donorActivity ADD index (userID)");
  $wpdb->query("alter table t_donorActivity ADD index (eid)");

  $sql = <<<EOF
SELECT
  dg.id as donorID,di.donorType as dtype,dg.user_id,
  concat('<a target=\"_new\" href=\"/members/', u.user_login, '\">', dg.firstName,' ',dg.lastName,'</a>') as profile,
  dg.firstName, dg.lastName,
  dg.email,dg.demo_tag,
  group_concat(distinct t.type) as type,
  sum(t.paid) as paid,
  IF(sum(t.tip)=0,'',sum(t.tip)) as tip,
  sum(t.paid)+IF(sum(t.tip)=0,'',sum(t.tip)) as total,
  '' as tip_pct,
  group_concat(distinct DATE(t.date) ORDER BY t.date DESC SEPARATOR ', ' ) as dates,
  count(distinct DATE(t.date)) as num_days,
  GROUP_CONCAT(distinct IF(t.eid = 0,NULL,t.eid)) as event_id,
  GROUP_CONCAT(distinct IF(t.gifts = '', NULL,t.gifts)) as gifts,
  SUM(t.num_gifts) as num_gifts,
  dg.main,
  IFNULL(DATE(di.lastUpdate),'---') as updated,
  di.isFB,
  di.i_location,
  di.total2010,di.total2011,
  di.gifts2010,di.gifts2011,
  di.tip_rate
FROM donationGiver dg
LEFT JOIN donorInfo di on di.donorID=dg.id
LEFT JOIN t_donorActivity t on t.donorID=dg.id
LEFT JOIN wp_users u on u.id=dg.user_id
WHERE $wheres
GROUP BY {$expand2}dg.id{$combine} $having
ORDER BY user_id desc,dg.id desc,t.date desc
EOF;

  $data = generate($sql, 'people report');
  if (empty($data))
    return;

  if (!empty($set_tag)) { // If tagging:
    $sql2 = "SELECT distinct t.donorID FROM ($sql) as t LIMIT 10000";
    $ids = implode(',', $wpdb->get_col($sql2));
    $wpdb->query($wpdb->prepare("UPDATE donationGiver SET demo_tag = %s WHERE id in ($ids)", $set_tag));
  }

  foreach ($data[1] as $row) {
    if (!empty($set_tag))
      $row->demo_tag = $set_tag;
    $row->total2010 = as_money($row->total2010);
    $row->total2011 = as_money($row->total2011);
    if ($row->paid > 0)
      $row->tip_pct = ($row->tip / $row->paid)  * 100.0;
    if (!empty($row->tip_rate))
      $row->tip_rate = "$row->tip_rate%";
    if (!empty($row->paid))
      $row->paid = as_money($row->paid);
    if (!empty($row->total))
      $row->total = as_money($row->total);
    if (!empty($row->tip))
      $row->tip = as_money($row->tip);
    if (!empty($row->tip_pct))
      $row->tip_pct = round($row->tip_pct, 2) . "%";
    if (empty($row->dates))
      $row->type = '';
    if (empty($row->dtype))
      $row->dtype = '';
    if (empty($row->user_id))
      $row->user_id = '';
    if (empty($row->num_days))
      $row->num_days = '';
    $row->donorID = '<a target="_new" href="/database/donation_reports.php?donor=' . $row->donorID . '">' . $row->donorID . '</a>';
    $row->dtype = editable_field('donorInfo', 'donorType', $row->dtype, array('donorID'=>$row->donorID));
  }

  return $data;
}

function report_donorinfo() {
  global $notTest, $wpdb;

  $donors = as_ints($_REQUEST['donor']);
  if (count($donors) > 0)
    $where = "and dg.ID in (" . implode(',', $donors) . ")";

  $sql = $wpdb->prepare("
    select dg.ID,dg.email,dg.firstName,dg.lastName,
      IF(NOT(u.user_login IS NULL), CONCAT(%s, u.user_login), '') as url,
      info.donorType,info.deductible2010,info.gifts2010,info.errors2010,
      info.story_title_1,info.story_url_1,info.story_img_1,
      info.story_title_2,info.story_url_2,info.story_img_2,
      info.story_title_3,info.story_url_3,info.story_img_3
    from donationGiver dg
    join donorInfo info on info.donorID=dg.id
    left join wp_users u on u.id=dg.user_id
    where dg.main=1 $where", get_site_url(1, '/members/'));
  return generate($sql, 'donor info');
}

function report_campaign() {
  global $wpdb;
  
  $campaign = require_param("campaign");

  $sql = $wpdb->prepare(
    "SELECT d.donationDate as `Donation Date`,
      donor.firstName as `First Name`,donor.lastName as `Last Name`, donor.email as `Email`, 
      dg.amount as `Donation`,dg.tip as `Optional Tip`, dg.amount+dg.tip as `Total Donation`,
      g.title as `Gift`, b.domain as `Charity`, 
      g.id as gift_id,g.blog_id,dg.story,
      p.amount+p.tip as amountMatched,p.notes,dat.note
    FROM donationGifts dg
    LEFT JOIN donation d on d.donationID=dg.donationID
    LEFT JOIN donationGiver donor on donor.id=d.donorID
    LEFT JOIN donationAcctTrans dat on dat.ID=dg.matchingDonationAcctTrans
    LEFT JOIN payment p on dat.paymentID=p.ID
    LEFT JOIN gift g on g.id=dg.giftID
    LEFT JOIN wp_blogs b on b.blog_id=g.blog_id
    WHERE event_id=%d
    ORDER BY `Donation Date` ASC", $campaign);

  $data = generate($sql, 'donations to campaign ' . $campaign);
  if (empty($data))
    return;

  foreach ($data[1] as $row) {
    $row->Story = get_blog_permalink($row->blog_id, $row->story);
  }

  return $data;
}

function report_user_donations() {
  global $wpdb;

  $sql = "
    select dg.id as `Donor ID`,dg.email,dg.firstName,dg.lastName,dg.main,
     u.ID as user_id,u.user_login,u.user_email,u.display_name,
     m1.meta_value as first_name,m2.meta_value as last_name,
     d.donationDate,d.donationAmount_Total as total,d.tip,
     IF(dd.max_date=d.donationDate,'LATEST','') as latest
    from donationGiver dg
    left join wp_users u on dg.user_id=u.id
    left join wp_usermeta m1 on m1.user_id=u.id and m1.meta_key='first_name'
    left join wp_usermeta m2 on m2.user_id=u.id and m2.meta_key='last_name'
    left join donation d on d.donorID=dg.ID
    left join (
      select donorID,MAX(donationDate) as max_date
      from donation
      group by donorID
    ) as dd on dd.donorID=dg.ID
    where dg.sendUpdates=1 -- AND (d.donationDate=dd.max_date OR dd.max_date = NULL)
    order by user_id,dg.main desc
  ";

  return generate($sql, 'donor user table');
}

function report_summaries() {
  global $wpdb;

  $since = require_param("since");

  $sql = $wpdb->prepare("select d.donationID,d.donationDate,sum(dg.unitsDonated) as quantity,count(distinct dg.giftID) as kinds, sum(dg.amount) as amount, d.tip
    from donationGifts dg
    join donation d on d.donationID=dg.donationId
    left join payment p on d.paymentID=p.ID
    where d.donationDate > %s and p.provider <> 5 and d.test=0 and dg.giftID > 10
    group by d.donationID
    order by d.donationDate", $since);

  return generate($sql, 'donations (summary) since ' . $since);
}

function report_mails()
{

}

function report_certificates()
{
  $date_from = require_param('from', 'date', '', false);
  $date_to = require_param('to', 'date', '', false);
  $wheres[] = "da.donationAcctTypeId in (3,6)";
  if (!empty($date_from))
    $wheres[] = "da.dateCreated >= '$date_from'";
  if (!empty($date_to))
    $wheres[] = "da.dateCreated < '$date_to'";
  $wheres = implode(' AND ', $wheres);

  $sql = "
select
  da.id,da.code,da.balance,
  date_format(da.dateCreated,'%m-%d-%Y') as dateCreated,
  date_format(da.dateUpdated,'%m-%d-%Y') as lastUpdated,
  if (da.donationAcctTypeId=3,concat(dg2.firstName,' ',dg2.lastName,' <',dg2.email,'>'),concat(dg.firstName,' ',dg.lastName,' <',dg.email,'>')) as sender,
  if (da.donationAcctTypeId=6,'--open--',concat(dg.firstName,' ',dg.lastName,' <',dg.email,'>')) as recipient
from donationAcct da
join donationGiver dg on da.owner=dg.id
left join donationGiver dg2 on da.creator=dg2.id
where $wheres
order by da.dateCreated ASC
";

  return generate($sql, 'gift certificates');
}

function report_donations()
{
  global $wpdb;
  global $notTest;
  $extraCond = '';

/*
  $bid = require_param("blog", 'blog_dropdown', NULL, FALSE);
  if ($bid > 1)
    $extraCond .= $wpdb->prepare(" AND dg.blog_id = %d", $bid);
*/
  $start = require_param("start:Start Date", 'text', '2011-01-01', TRUE);
  $extraCond .= $wpdb->prepare(" AND donationDate > %s", $start);
  $stop = require_param("stop:End Date", 'text', '', FALSE);
  if (!empty($stop))
    $extraCond .= $wpdb->prepare(" AND donationDate < %s", $stop);

  $grouped = require_param('grouped:group by donation', 'checkbox', 1, false);
  if ($grouped)
    $grp = "d.donationID";
  else
    $grp = "dg.id";

  $sql = 
"SELECT donor.firstName, donor.lastName, 
  ".(!$grouped?"dg.onbehalf,":"GROUP_CONCAT(dg.onbehalf SEPARATOR ',') AS onbehalf,")."
  info.donorType,
  group_concat(g.displayName separator ', ') as gift, g.towards_gift_id as aggregatesTo,
  d.donationDate, 
  sum(dg.amount) as amount, sum(dg.tip) as tip, sum(dg.tip)/sum(dg.amount) as tipP, story, group_concat(distinct domain separator ', ') as domain, IF(donor.sendUpdates=1, donor.email, '') as email, 
  d.donationID,d.paymentID 
FROM donationGifts as dg
LEFT JOIN donation d ON d.donationID=dg.donationID
LEFT JOIN donationGiver donor ON donor.ID=d.donorID
LEFT JOIN gift g ON g.id=dg.giftID
LEFT JOIN wp_blogs wb ON wb.blog_id=dg.blog_id
LEFT JOIN donorInfo info ON info.donorID=d.donorID
WHERE 
  $notTest $extraCond
GROUP BY $grp
ORDER BY d.donationDate desc";

  add_filter('format_report_row', 'format_donation_status');
  return generate($sql, 'donation status');
}
function format_donation_status($row) {
  $row->tipP = round(1000 * $row->tipP) / 10.0 . '%';
  return $row;
}

function report_quantities()
{
  global $notTest;
  $main_blogname = get_blog_option(1,'blogname');
  $sql = "SELECT
    CONCAT('[',dg.id,'] ',SUBSTR(dg.email,1,50)) as `donor`, d.donationDate as donated, 
    CONCAT('[',b.blog_id,'] ',REPLACE(b.domain,'.$main_blogname','')) as charity, 
    SUM(g.unitsDonated) as qty, CONCAT('[',gift.ID,'] ',gift.displayName) as gift 
    FROM donation d join donationGiver dg on dg.ID = d.donorID 
    JOIN donationGifts g on g.donationId = d.donationId 
    LEFT JOIN gift gift on gift.id = g.giftID 
    LEFT JOIN wp_blogs b on gift.blog_id = b.blog_id 
    WHERE $notTest group by d.donationId order by qty desc, d.donationDate desc";

  return generate($sql, 'gift quantities');
}

function report_contacts()
{
  $sql = "select firstName,lastName,email,referrer from donationGiver where sendUpdates = 1";

  return generate($sql, 'donor contact e-mails');
}

function report_causes() {
  $sql = "select b.blog_id,b.domain,b.public,regions,causes from wp_blogs b left join charity info on b.blog_id=info.blog_id";
  
  return generate($sql, 'blog regions and causes');
}

function format_pratham($row) {
  if (isset($row->ended))
    $row->ended = $row->ended ? "YES" : "";

  if (isset($row->guid)) {
    $row->name = '<a href="' . $row->guid . '" target="_new">' . xml_entities($row->name) . '</a>';
    unset($row->guid);
  }

  common_rows($row);
  return $row;
}
function report_pratham() {
  $wheres = array("c.theme = 'readathon'", "!c.archived");

  $select = "c.post_id, c.guid, concat(um1.meta_value, ' ', um2.meta_value) as name, u.user_email as email,
    c.goal, books.meta_value as num_books,
    c.donors_count as donors, c.pledge_count as pledges, count(DISTINCT i.id) as invites,
    c.raised, c.tip, (c.tip / c.raised) as tip_rate, c.offline,
    (c.raised / c.donors_count) as per_donor,
    c.team as team, coordinator.meta_value as coordinator,
    p.post_date as start_date, c.end_date, closed.meta_value as ended";

  $group_select = "
    SUM(donors) as donors, AVG(donors) as avg_donors,
    AVG(per_donor) as per_donor,
    SUM(pledges) as pledges, AVG(pledges) as avg_pledge,
    AVG(goal) as avg_goal,
    SUM(raised) as raised, AVG(raised) as avg_raised,
    SUM(tip) as tip, AVG(tip) as avg_tip,
    SUM(offline) as offline,
    AVG(tip_rate) as tip_rate, SUM(invites) as invites";

  $cols['created'] = array(
    'label' => 'created',
    'column' => 'p.post_date',
    'type' => 'daterange',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m')",
    'group_select' => $group_select
  );
  $cols['donor_count'] = array(
    'label' => '# of donors',
    'column' => 'c.donors_count',
    'type' => 'expr',
    'group_func' => "IF(THIS=0, '0', IF(THIS<5, '1-4', IF(THIS<10, '5-9', '10+')))",
    'group_select' => $group_select
  );
  $cols['chapter'] = array(
    'column' => "IFNULL(c.team, '')",
    'type' => 'expr',
    'group_select' => $group_select
  );

  $sql = "
    {{SELECT}}
    from campaigns c
    left join wp_1_postmeta theme on theme.post_id=c.post_id and theme.meta_key = 'syi_theme'
    left join wp_1_postmeta books on books.post_id=c.post_id and books.meta_key = 'readathon_books'
    left join wp_1_postmeta coordinator on coordinator.post_id=c.post_id and coordinator.meta_key = 'readathon_coordinator'
    left join wp_1_postmeta closed on closed.post_id=c.post_id and closed.meta_key = 'pledge_closed'
    left join wp_1_posts p on p.id=c.post_id
    LEFT JOIN wp_users u on u.id=p.post_author
    left join wp_usermeta um1 on um1.user_id=p.post_author and um1.meta_key = 'first_name'
    left join wp_usermeta um2 on um2.user_id=p.post_author and um2.meta_key = 'last_name'
    left join invite i on i.context=CONCAT('campaign/', c.post_id)
    {{WHERE}}
    GROUP BY c.post_id
    ORDER BY c.post_id desc";

  add_filter('format_report_row', 'format_pratham');
  return generate(array(
    'sql' => $sql,
    'title' => "pratham fundraiser",
    'wheres' => $wheres,
    'cols' => $cols,
    'select' => $select
  ));
}

function format_readathon($row) {

  if (isset($row->name)) {
    $row->name = "<a href='$row->url'>$row->name</a> ($row->email)";
    unset($row->url);
    unset($row->email);

    $row->for_SYI = draw_money($row->for_SYI);
    $row->signup = draw_date($row->signup);
  }
  
  common_rows($row);
  return $row;
}
function report_readathon() {
  $wheres = array("c.theme = 'readathon'", "!c.archived", 'c.start_date >= "2012-06-01"');

  $select = "
    c.team as city, 
    c.guid as url, concat(um1.meta_value, ' ', um2.meta_value) as name,
    u.user_email as email,
    coordinator.meta_value as coordinator,
    p.post_date as signup,
    c.donors_count as donors, count(DISTINCT i.id) as invites,
    c.raised, c.tip as 'for_SYI'";

  $group_select = "
    SUM(donors) as donors,
    SUM(raised) as raised,
    SUM(invites) as invites";

  $cols['signup'] = array(
    'label' => 'signup',
    'column' => 'p.post_date',
    'type' => 'daterange',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m')",
    'group_select' => $group_select
  );
  $cols['donor_count'] = array(
    'label' => '# of donors',
    'column' => 'c.donors_count',
    'type' => 'expr',
    'group_func' => "IF(THIS=0, '0', IF(THIS<5, '1-4', IF(THIS<10, '5-9', '10+')))",
    'group_select' => $group_select
  );
  $cols['chapter'] = array(
    'column' => "IFNULL(c.team, '')",
    'type' => 'expr',
    'group_select' => $group_select
  );

  $sql = "
    {{SELECT}}
    from campaigns c
    left join wp_1_postmeta theme on theme.post_id=c.post_id and theme.meta_key = 'syi_theme'
    left join wp_1_postmeta books on books.post_id=c.post_id and books.meta_key = 'readathon_books'
    left join wp_1_postmeta coordinator on coordinator.post_id=c.post_id and coordinator.meta_key = 'readathon_coordinator'
    left join wp_1_postmeta closed on closed.post_id=c.post_id and closed.meta_key = 'pledge_closed'
    left join wp_1_posts p on p.id=c.post_id
    LEFT JOIN wp_users u on u.id=p.post_author
    left join wp_usermeta um1 on um1.user_id=p.post_author and um1.meta_key = 'first_name'
    left join wp_usermeta um2 on um2.user_id=p.post_author and um2.meta_key = 'last_name'
    left join invite i on i.context=CONCAT('campaign/', c.post_id)
    {{WHERE}}
    GROUP BY c.post_id
    ORDER BY c.team ASC";

  add_filter('format_report_row', 'format_readathon');
  return generate(array(
    'sql' => $sql,
    'title' => "pratham coordinator report",
    'wheres' => $wheres,
    'cols' => $cols,
    'select' => $select
  ));
}

function wpdb_make_string($s) {
  global $wpdb;
  return $wpdb->prepare('%s', $s);
}

function format_email_list($r) {
  common_rows($r);
  if (isset($r->most_recent))
    $r->most_recent = draw_date($r->most_recent);
  if (isset($r->allocated))
    $r->allocated = draw_money($r->allocated);
  if (isset($r->unallocated))
    $r->unallocated = draw_money($r->unallocated);
  if (isset($r->direct))
    $r->direct = draw_money($r->direct);
  if (isset($r->send_email))
    $r->send_email = $r->send_email ? "YES" : "no";
  if (isset($r->owner)) {
    $r->fundraiser = draw_link($r->fundraiser, $r->owner);
  }
  unset($r->owner);
  if (isset($r->address2)) {
    $r->address .= " $r->address2";
    unset($r->address2);
  }
/*
  if (isset($r->donor_name)) {
    $r->donor_name .= " <$r->donor_email>";
    unset($r->donor_email);
  }
*/
  return $r;
}
function report_email_list() {

  $wheres = array();
  // $wheres[] = "(ISNULL(account.id) OR NOT ISNULL(donIn.donationId))";

  $select = "
    GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(blog.domain, '.', 1)) AS 'partner',
    c.theme as campaign,
    CONCAT(donor.firstName, ' ', donor.lastName) AS 'donor_name',
    donor.email AS 'donor_email',
    MAX(donor.share_email) AS 'share_email',
    -- looking first by user_id, and then via donor ID, get the donor type
    -- if the donor type is NULL or 0, then they are normal
    -- otherwise they are special
    donation.donationID,
    MAX(IF(info1.donorType IS NULL, IFNULL(info2.donorType,0), info1.donorType)) AS 'donor_type',
    MAX(IF(payment.provider = 5 AND (account.donationAcctTypeId = 4 OR account.donationAcctTypeId = 7), payIn.dateTime, donation.donationDate)) as `date`,
    SUM(IF(IFNULL(account.donationAcctTypeId,0) = 7,donationGifts.amount,0)) AS 'allocated',
    SUM(IF(IFNULL(account.donationAcctTypeId,0) != 7,donationGifts.amount,0)) AS 'direct',
    0 as 'unallocated',
    0 as 'offline',
    c.post_id AS fundraiser_id, u.display_name as owner, GROUP_CONCAT(DISTINCT c.guid SEPARATOR ' ') as fundraiser, c.team,
    donor.address, donor.address2, donor.city, donor.state, donor.zip
 ";

  $sql = <<<EOS
    {{SELECT}}
    FROM donation
    LEFT JOIN donationGifts                 ON donation.donationID = donationGifts.donationID
    LEFT JOIN wp_blogs blog                 ON blog.blog_id = donationGifts.blog_id
    LEFT JOIN payment                       ON payment.id = donation.paymentID
    LEFT JOIN donationAcctTrans trans       ON trans.paymentID = payment.id
    LEFT JOIN donationAcct account          ON account.id = trans.donationAcctId
    LEFT JOIN donationGiver donor           ON donor.id = IF(IFNULL(account.donationAcctTypeId,0) = 7, account.donorID, donation.donorID)
    LEFT JOIN donorInfo info1               ON info1.user_id = donor.user_id AND donor.user_id > 0
    LEFT JOIN donorInfo info2               ON info2.donorID = donor.id AND donor.id > 0
    LEFT JOIN donationAcctTrans datIn       ON trans.donationAcctId > 100 AND datIn.donationAcctId=trans.donationAcctId AND datIn.amount > 0
    LEFT JOIN payment payIn                 ON payIn.id = datIn.paymentId AND payIn.provider != 5
    LEFT JOIN donation donIn                ON donIn.paymentId = payIn.id AND donIn.donorID=donor.id
    LEFT JOIN campaigns c                   ON c.post_id = IFNULL(donationGifts.event_id, account.event_id)
    LEFT JOIN wp_users u                    ON u.id = c.owner
    {{WHERE}}
    GROUP BY account.event_id,donor_email
    HAVING (IFNULL(allocated,0) + direct) > 0
    ORDER BY date DESC
EOS;

  $cols['email'] = array(
    'column' => 'donor.email',
    'type' => 'expr',
    'group_by' => 'donor_email, partner',
    'order_by' => 'partner, donor_email',
    'no_count' => TRUE,
    'group_select' => "
      donor_name, donor_type, share_email AS send_email,
      GROUP_CONCAT(DISTINCT partner SEPARATOR ', ') AS 'partner',
      MAX(date) AS 'most_recent',
      SUM(allocated) AS 'allocated',
      SUM(direct) AS 'direct',
      owner,
      GROUP_CONCAT(DISTINCT fundraiser SEPARATOR ' ') as fundraiser,
      team,
      address, address2, city, state, zip"
  );

  $cols['fundraiser'] = array(
    'column' => 'c.post_id',
    'type' => 'expr',
    'group_by' => 'fundraiser',
    'group_select' => ''
  );

  $cols['theme'] = array(
    'column' => 'c.theme',
    'type' => 'expr',
    'group_by' => 'theme',
    'group_select' => $cols['email']['group_select']
  );

  $cols['partner'] = array(
    'column' => "SUBSTRING_INDEX(blog.domain, '.', 1)",
    'type' => 'expr',
    'group_select' => "MAX(date) AS 'most_recent', SUM(allocated) AS 'allocated', SUM(direct) AS 'direct'"
  );

  $cols['date'] = array(
    'label' => 'donation month (yyyy/mm)',
    'column' => 'IF(payment.provider = 5 AND (account.donationAcctTypeId = 4 OR account.donationAcctTypeId = 7), payIn.dateTime, donation.donationDate)',
    'type' => 'daterange',
    'group_func' => "DATE_FORMAT(THIS, '%Y/%m')",
    'group_select' => "SUM(allocated) AS 'allocated', SUM(direct) AS 'direct'"
  );

  $cols['share_contact'] = array(
    'label' => 'only if contact shared',
    'column' => "donor.share_email",
    'type' => 'checkbox',
    'group_select' => "MAX(date) AS 'most_recent', SUM(allocated) AS 'allocated', SUM(direct) AS 'direct'"
  );

  add_filter('format_report_row', 'format_email_list');
  return generate(array(
    'sql' => $sql,
    'title' => "E-mails for partner distribution",
    'wheres' => $wheres,
    'select' => $select,
    'cols' => $cols,
    'expensive' => TRUE
  ));
}

function report_email_stats() {
  global $wpdb;

  $source = require_param(array(
    'var' => 'source',
    'label' => 'Type of email:&nbsp;',
    'type' => 'dropdown',
    'opts' => array(
      'invite' => "Fundraiser Invite",
      'update' => "Fundraiser Update",
    )
  ));

  $theme = require_param(array(
    'var' => 'theme',
    'label' => 'Fundraiser theme:&nbsp;',
    'type' => 'text',
    'required' => 0,
  ));

  $select = array(
    'e.recipient',
    'e.delivered',
    'e.theme', 
    'e.reason',
    'e.opened',
    'e.clicked',
    'e.soft_bounce', 
    'e.blocked', 
    'e.hard_bounce', 
    'e.spam_report', 
    'e.filtered', 
    'e.unsubscribed'
  );

  $sql = "FROM email_stats e ";
  $sql_params = array();

  if ($source == "invite") {
    array_unshift($select, 
      '"invite" as type',
      'invitation.id as post_id'
    );

    $sql .= <<<EOS
      INNER JOIN invitation 
        ON invitation.id = e.source_id
EOS;
    $sql_params[] = 'invite';
  }
  else if ($source == "update") {
    array_unshift($select,
      '"update" as type',
      'e.source_id as post_id'
    );

    $sql_params[] = 'update';
  }

  $sql .= ' WHERE (e.reason is null or (e.reason is not null and e.reason != "bcc")) AND e.source = %s';

  if ($theme) {
    $sql .= " AND e.theme = %s";
    $sql_params[] = $theme;
  }

  $sql .= " ORDER BY e.delivered";

  array_unshift($select, '1 as fundraiser');
  $sql = "SELECT " . implode(', ', $select) . " $sql";

  add_filter('format_report_row', 'format_email_stats');
  return generate(array(
    'sql' => $wpdb->prepare($sql, $sql_params),
    'title' => "Email Statistics",
    'expensive' => TRUE
   ));
}

function donor_name($post_id) {
  global $wpdb;
  $owner = get_campaign_owner($post_id);
  $donor = $wpdb->get_row($wpdb->prepare('select * from donationGiver where user_id = %d', $owner));
  return $donor->firstName . ' ' . $donor->lastName;
}

function format_email_stats($r) {
  global $wpdb;

  if (isset($r->post_id)) {
    if ($r->type == 'update') {
      $r->post_id = get_post_meta($r->post_id, 'fr', true);
    }
    else {
      $r->post_id = $wpdb->get_var($wpdb->prepare(
        'select SUBSTRING_INDEX(context, "/", -1) from invite where invitation_id = %d LIMIT 1',
        $r->post_id
      ));
    }
    $r->fundraiser = '<a href="' . get_permalink($r->post_id) . '" target="_blank">'. donor_name($r->post_id) . '</a>';
    unset($r->type);
    unset($r->post_id);
  }
  $r->delivered = draw_date($r->delivered);
  $r->opened = draw_date($r->opened);
  $r->clicked = draw_date($r->clicked);
  return $r;
}

function report_table($meta, $results = array())
{
  global $params, $missing_params;

  draw_styles();

  if (count($params) > 0) { 
    ?>
    <form class="params" method="GET" action="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
      <input type="hidden" name="report" value="<?= $_REQUEST['report'] ?>" />
      <span class="param">
      <? echo implode("</span>\n<span class='param'>", $params); ?>
      </span>
      <input type="submit" class="go" value="run report" name="submit" />
    </form>
    <?
    if ($missing_params)
      die();
  }

  if (count($results) <= 0) {
    echo '<br/>No results';
    return;
  }

  $cr = count($results);
  if ($cr == ROW_LIMIT)
    $cr = ROW_LIMIT . '+ results (truncated)';
  else if ($cr == 1)
    $cr = '1 result';
  else
    $cr = "$cr results";

  ?>
  <h1><?=as_html($meta['title'])?>: <?=$cr?> <a class="csv" href="<?= add_query_arg(array('format' => 'csv')) ?>">save as CSV</a></h1>
  <?

  draw_table($meta, $results);
}

function draw_styles() {
?><style type="text/css">
#report_floating_info{background-color: rgba(0,0,0,0.45);
font-size:14px;  text-align:right; color: #fff;
  position: fixed; right: 25px; top: 25px; width:240px; height:160px;
  border: 1px solid #000;padding:20px;}
#report_floating_info label{
text-align:left;
float:left; width:160px; clear: left;

}
body{font-family:Arial;margin:20px;background:white;}
h1{font: 18pt Arial; padding: 4px;}
table{border: 1px solid black;
  border-spacing:0;
  border-collapse:collapse;
}
table td, table th{font: 10pt Arial; border-left:1px solid #d0d0d0;
border-bottom:1px solid #d0d0d0; padding:2px 5px 2px 2px; vertical-align:top;}
table th{background: #000040; color: white; font: bold 11pt Arial; text-align:left;}
a.csv{font: 10pt Arial; margin-left: 15px;}
.hilite_yellow{background:#ffffC0;}
.hilite_red{background:#ffC0C0;}
.hilite_purple{background:#ffC0ff;}
.hilite_blue{background:#C0C0ff;}
.hilite_green{background:#C0ffC0;}
.tright{text-align:right;}
tr:hover { background: #f0f0f0; }

a.remove-filter, .add-filter {
  background: #ddd; color: white;
  text-decoration: none;
  margin-left: 2px;
  padding: 0 4px;
  font-size: 80%;
  border-radius: 8px;
}
td .add-filter { float: right; }

form.params {  }
.params .param { padding: 8px; margin-right: 10px; font-size:12px; }
.params .param br { clear: both; }
.params .go { padding: 2px 10px; margin: 8px; }
.params .instructions { font-size: 8pt; margin: 0 10px; }

.report thead { cursor: pointer; }
</style>
<?
}

function draw_table($meta, $results) {
?>
  <p><?=isset($meta['desc']) ? $meta['desc'] : ''?></p>
  <table cellpadding="0" cellspacing="4" border="0" class="report"><?

  $last_time = '';
  if (isset($meta['group_by']))
    $group_by = $meta['group_by'];
  else 
    $group_by = "";

  $i = 0;
  foreach ($results as $k=>$result) {
    if (!empty($group_by))
      $orig = $result->$group_by;
    $result = apply_filters('format_report_row', $result);

    if ($i++ == 0) {
      // Header row
      ?><thead><tr><?
      foreach ($result as $col=>$val) {
        ?><th style="text-align:left;" class="<?=($col=='Amt'||$col=='Tip'||$col=='%Tip'
          ||$col=='D Amt'||$col=='D Tip')?
            'tright':''?>"><?= as_html(stripslashes($col)) ?></th><?
      }
      ?></tr></thead><?
    } 

    if(isset($result->Matcher) && $result->Matcher!=NULL && $result->Matcher!=''){
      $row_class = 'hilite_green';
    } else {
      $row_class = '';
    }
    ?><tr class="<?=$row_class?>"><?
    foreach ($result as $col=>$val) {
      $class = '';
      if($col=='Amt'||$col=='Tip'||$col=='%Tip'){
        $class = 'tright hilite_yellow';
        if($col == 'Tip' && floatval(str_replace('$','',$val)) == '0'){
          $class = 'tright hilite_red';  
        }
      }else if($col=='D Amt'||$col=='D Tip'){
        $class = 'tright';            
      }else if($col=='Donation' &&
          ((isset($results[$k+1]) && ($val == intval($results[$k+1]->$col)))
          || (isset($results[$k-1]) && ($val == intval($results[$k-1]->$col))))){
          
        if($k>1 && $val != intval($results[$k-1]->$col)){
          if($last_time == 'hilite_blue'){
            $class = 'hilite_purple';
            $last_time = $class;
          } else {
            $class = 'hilite_blue';
            $last_time = $class;
          }
        }else{
          $class = $last_time;
        }
      }
      ?>
      <td class="<?=$class?>">
        <? 
        $disp = $val;
        if ($col == $group_by) { 
          if (empty($val)) {
            $orig = $disp = "(none)";
          }
          $url = add_query_arg(array('submit'=>NULL,'group_by'=>NULL, $col=>urlencode($orig)));
          $url = str_replace('#', '%23', $url);
          ?><a href="<?=$url?>"><?
        }
        echo $col == "Report Name" ? $disp : smart_url(stripslashes($disp));
        /* if ($col == $group_by) { ?></a><a href="<?=$url?>" class="add-filter">+</a><? } */
        ?>
      </td><?
    }
    ?></tr><?
  }
  ?></table>
  <p><?=isset($meta['script']) ? $meta['script'] : ''?></p>
  <?
}

function as_csv($s) {
  $s = str_replace('"', '""', stripslashes($s));
  $s = preg_replace('/\<i\>(.*)\<\/i\>/', '$1', $s);
  $s = preg_replace('/\<b\>(.*)\<\/b\>/', '$1', $s);
  $s = preg_replace('/\<span.*\>(.*)\<\/span\>/', '$1', $s);
  $s = preg_replace('/\<a .*\>(.*)\<\/a\>/', '$1', $s);
  return '"' . @iconv('UTF-8', 'windows-1256', $s) . '"';
}

function report_email($meta, $results = array()) {
  ob_start();
  $url = remove_query_arg('format');
  $csv_url = add_query_arg('format', 'csv', remove_query_arg('format'));
?>
<style>
  table { 
    font: 10pt Arial; 
    border-right: 1px solid #ddd; 
    border-bottom: 1px solid #ddd; 
    }
  td {
    border-top: 1px solid #ddd; 
    border-left: 1px solid #ddd; 
    padding: 2px;
   }
  th {
    text-align: left;
    background: black;
    color: white;
    padding: 4px;
   }
</style>
  <h1><?= $meta['title'] ?></h1>
  <p><a href="<?=SITE_URL . $url?>">view this report online</a> or 
  <a href="<?=SITE_URL . $csv_url?>">download a CSV</a></p>
<?
 

  draw_table($meta, $results);
  $body = ob_get_contents();
  ob_end_flush();

  $recip = '"Steve Eisner" <steve@seeyourimpact.org>';
  SyiMailer::send($recip, 'Report: ' . $meta['title'], 'raw', array(), array(
    'body_html' => $body
  ));
}

function report_csv($meta, $results = array())
{
  if (count($results) <= 0) {
    return;
  }

  header("Cache-Control: must-revalidate");
  header("Pragma: must-revalidate");
  header('Content-Disposition: attachment; filename="' . $meta['title'] . '.csv"');
  header('Content-type: text/csv');

  $i = 0;

  foreach ($results as $result) {
    $result = apply_filters('format_report_row', $result);

    if ($i++ == 0) {
      // Header row
      $s = '';
      foreach ($results[0] as $col=>$val) {
        echo $s;
        echo as_csv($col);
        $s = ',';
      }
      echo "\r\n";
    }

    $s = '';
    foreach ($result as $col=>$val) {
      echo $s;
      echo as_csv($val);
      $s = ',';
    }
    echo "\r\n";
  }
}

function replace_smart_url($matches) {
  return '<a target="_smart" href="' . esc_url($matches[1]) . '">' . $matches[1] . '</a>';
}
function smart_url($s) {
  if (substr($s, 0, 5) == 'http:') {
    return preg_replace_callback("/(http:\/\/[^ ]*)/", 'replace_smart_url', $s);
    // return '<a target="_smart" href="' . esc_url($s) . '">' . esc_html($s) . '</a>';
  }
  if (substr($s, 0, 1) == "<")
    return $s;
  return esc_html($s);
}

function split_report($left, $right) {
  $url = add_query_arg('format','framed', remove_query_arg(array('left','right')));
  $left = add_query_arg('report', $left, $url);
  $right = add_query_arg('report', $right, $url);

?>
<html><head>
<iframe style="width:60%; height:95%; border: 0; padding: 0; margin: 0;" src="<?=$left?>"></iframe>
<iframe style="width:39%; height:95%; border: 0; padding: 0; margin: 0;" src="<?=$right?>"></iframe>
</head></html>
<?
}

function get_report()
{
  global $notTest;

  $left = $_REQUEST['left'];
  if (!empty($left)) {
    split_report($left, $_REQUEST['right']);
    return;
  }

  $report = $_REQUEST['report'];
  $format = $_REQUEST['format'];
  if (empty($format))
    $format = 'table';

  $test = $_REQUEST['test'];
  if (!empty($test))
    $notTest = "1=1";

  $reports = array(
    'campaign_activity' => array('func' => 'campaign_activity', 
      'desc' => 'activity on all fundraisers, by date'),
    'campaign_stats' => array('func' => 'campaign_stats', 'desc' => 'fundraiser stats'),
    'stories' => array('func' => 'stories', 'desc' => 'published stories'),
    'email_list' => array('func' => 'email_list', 'desc' => 'Email lists for partners', 'owner' => 'Alex'),
    'email_stats' => array('func' => 'email_stats', 'desc' => 'Email statistics', 'owner' => 'Alex'),
    'refer' => array('func' => 'referrals from=<i>champion</i>'),
    'campaign' => array('func' => 'campaign campaign=<i>campaign_id</i>',
      'desc' => 'list all donors to a fundraiser'),
    'unallocated' => array('func' => 'unallocated event=<i>event_id</i>',
      'desc' => 'unallocated funds for fundraiser'),
    'unpublished' => array('func' => 'unpublished',
      'desc' => 'received donations that have not yet published a story'),
    'unpublished_stories' => array('func' => 'unpublished_stories',
      'desc' => 'stories that are awaiting publication'),
    'accounts' => array('func' => 'accounts',
      'desc' => 'GC/discount/to-allocate accounts'),
    'pending' => array('func' => 'pending',
      'desc' => 'received donations that have not yet published a story'),
    'donor_retention' => array('func' => 'donor_retention',
      'desc' => 'donors in order of number of repeat payments'),
    'donation' => array('func' => 'donations'),
    'summary' => array('func' => 'summaries'),
    'contact' => array('func' => 'contacts'),
    'cert' => array('func' => 'certificates',
      'desc' => 'Gift certificates by purchase date'),
    'pratham' => array('func' => 'pratham', 'desc' => 'Pratham Fundraisers'),
    'readathon' => array('func' => 'readathon', 'desc' => 'Readathon chapter status'),
    'quant' => array('func' => 'quantities'),
    'donors' => array('func' => 'donorinfo',
      'desc' => 'Segmentation, 2010 tax info, most recent published stories, etc for each donor'),
    'people' => array('func' => 'people',
      'desc' => 'Donor segmentation'),
    'checout_failures' => array('func' => 'checkout_failures'),
    'cause' => array('func' => 'causes'),
    'story_delay' => array('func' => 'story_delay'),
    'user_donations' => array('func' => 'user_donations'),
    'dashboard' => array('func' => 'dashboard'),    
    'gifts' => array('func' => 'gifts', 'desc' => 'All gifts'),    
    'gifts_per_tag' => array('func' => 'gifts_per_tag', 'desc' => 'Gifts per tag'),
    'donated_per_gift' => array('func' => 'donated_per_gift',
      'desc' => 'Donations grouped by gift/aggregates'),    
    'invite_queue' => array('func' => 'invite_queue'),
    'story_mismatch' => array('func' => 'story_mismatch'),          
    'invite_referrer' => array('func' => 'invite_referrer')
  );

  $meta = array('title' => "Available reports", 'link' => true);
  $data = array();

  foreach ($reports as $rpt) {
    $func = $rpt['func'];

    $f = explode(' ', $func);
    $f = $f[0];

    if (strpos($report, $f) === 0 && strlen($f) >= strlen($report)) {
      list($meta, $data) = call_user_func("report_$f");
      break;
    }

    $data[] = array(
      'Report Name' => "<a href='reports.php?report=$f'>$func</a>",
      'Description' => $rpt['desc'],
      'Updated' => '', #$rpt['updated'],
      'Owner' => array_key_exists('owner', $rpt) ? $rpt['owner'] : 'Steve',
    );
  }

  if ($format == 'csv') {
    report_csv($meta, $data);
    return;
  }
  if ($format == 'json') {
    Api::reply($data, null, $meta);
    return;
  }
  if ($format == 'email') {
    report_email($meta, $data);
    return;
  }

  ini_set('display_errors', 1);
  error_reporting(E_ALL & ~E_STRICT);

  ?><html><head><title><?= $meta['title'] ?></title>
<? core_scripts(); ?>
<script src="/wp-content/themes/syi/jquery.tools.min.js" type="text/javascript"></script>
<script src="/wp-content/themes/syi/jquery.lazyload.js" type="text/javascript"></script>
<script src="/wp-content/themes/syi/jquery.tablesorter.min.js" type="text/javascript"></script>

<script>
jQuery(function($) {
  $("img").lazyload({ effect: "fadeIn" });

  if ($.fn.tablesorter) {
    $("table.report").tablesorter({
      textExtraction: function(node) {
        var val = $(node).find('[sort]').attr('sort');
        return val || $(node).text();
      }
    });
  }

  $(".editable input").live("change keydown", function() {
    $(this).addClass('changed');
  });
  $(".editable").submit(function() {
    $(this).trigger("sync");
    return false;
  });
  $(".editable input.changed").live("blur", function() {
    $(this).closest(".editable").trigger("sync");
  });
  $(".editable").live("sync", function() {
    var f = $(this);
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: f.serialize(),
      headers: {"AJAX-Method":'AJAX'},
      success: function(data) {
        f.find("input").val(data).removeClass('changed');
      }
    });
  });
});
</script>
<style>
.editable { margin: 0; padding: 0; }
.editable input { border: 0px none; padding: 3px; margin: -2px; }
.gift_notes input.notes { width: 300px; }
.donorInfo input.donorType { width: 30px; }
.editable .changed { font-weight: bold; }
a.drilldown { color: #006; text-decoration: none; }
a.drilldown:hover { color: blue; text-decoration: underline; }
</style>
</head><body style="overflow:auto; background: #fff;">
<?
/*
<script type="text/javascript" src="/wp-includes/syi/js/ui.custom.js"></script>
<script type="text/javascript">
jQuery(function(){
jQuery("#start").datepicker();
jQuery("#stop").datepicker();    
});
</script>
<link href="/wp-includes/syi/ui.custom.css" type="text/css" rel="stylesheet" />
*/
  if (!empty($meta->frame)) {
    echo $meta->frame;
  }

  if ($format == 'framed') {
    draw_styles();
    draw_table($meta, $data);
  } else {
    report_table($meta, $data);
  }

  ?>
  </body></html>
  <?
}
get_report();
?>
