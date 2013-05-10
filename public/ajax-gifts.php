<?  

define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation

include_once('wp-load.php');


$tags1 = as_array($_REQUEST['tags']);
$tags2 = as_array($_REQUEST['tags2']);
$tags3 = as_array($_REQUEST['tags3']);
$min_amt = absint($_REQUEST['min_amt']);
$max_amt = absint($_REQUEST['max_amt']);
$gpg = $_REQUEST['gpg'];
$GIFTS_V2 = true;

switch ($_REQUEST['cmd']) {
  case 'browse':
    header('Expires: Thu, 29 Dec 2011 00:00:00 GMT');
    header('Cache-Control: public, max-age=60, must-revalidate');
    draw_gift_browser_pages($tags1, $tags2, $tags3, $min_amt, $max_amt, $gpg);
    die();

  case 'v':
    nocache_headers();
    record_views($_REQUEST['ids']);
    die();
}

nocache_headers();

// ADMIN-ONLY
if (!current_user_can('level_10'))
  die('invalid');
switch ($_REQUEST['cmd']) {
  case 'manage':
    draw_gift_capacity($_REQUEST);
    die();

  case 'top-sold':
    $id = intval(str_replace('cap-','', $_REQUEST['id'])) or die();
    die();

  case 'top-viewed':
    $id = intval(str_replace('cap-','', $_REQUEST['id'])) or die();
    $top = $wpdb->get_results($wpdb->prepare(
      "SELECT page, sum(count) as value FROM views
       WHERE id=%d
       GROUP BY page ORDER BY value DESC",
      $id));
    ?><table border="1" cellpadding="2"><?
    foreach ($top as $row) {
      ?><tr><td nowrap="" align="right"><?=htmlspecialchars($row->page)?></td><td><b><?=$row->value?></b></td></tr><?
    }
    ?></table><?
    die();
}

function record_views($ids) {
  global $wpdb;

  $ids = as_array($ids);

  foreach ($ids as $id) {
    $wpdb->query($sql = $wpdb->prepare(
      "INSERT INTO views (date,page,id,count)
       VALUES (NOW(),%s,%d,1)
       ON DUPLICATE KEY UPDATE count=count+1 ",
       dirname($id), intval(basename($id)))); 
  }
}

function draw_gift_capacity($args) {
  global $wpdb;

  extract($args);

  $search = trim($search);
  if (!empty($search)) {
    $search = "%$search%";
    $search = $wpdb->prepare(
      " AND (g.title LIKE %s OR g.tags LIKE %s) ", 
      $search, $search);
  }

  switch ($order) {
    case 'price':
      $order = "unitAmount ASC";
      break;
    case 'need':
      $order = "unitsWanted ASC";
      break;
    case 'views':
      $order = "views DESC";
      break;
    case 'sales':
      $order = "sales DESC";
      break;
    case 'conversion':
      $order = "(sales/(views+1)) DESC";
      break;
    case 'progress':
      $order = "progress DESC";
      break;
    default:
      $order = "unitAmount ASC";
      break;
  }

  if ($options) foreach ($options as $opt) {
    switch ($opt) {
      case 'whole':
        $search .= " AND (g.towards_gift_id = 0) ";
        break;
      case 'part':
        $search .= " AND (g.towards_gift_id > 0) ";
        break;
      case 'soldout':
        $search .= " AND (g.unitsWanted <= 0) ";
        break;
      case 'instock':
        $search .= " AND (g.unitsWanted > 0) ";
        break;
    }
  }

  $sql = //$wpdb->prepare(
    "SELECT g.id as ID,g.*,b.*,stat.*,g2.displayName as WHOLE,
      (g.current_amount / g.unitAmount) as progress
     FROM gift g
     JOIN wp_blogs b ON b.blog_id=g.blog_id
     LEFT JOIN gift_stats stat ON g.id=stat.id
     LEFT JOIN gift g2 ON g.towards_gift_id=g2.id
     WHERE g.active = 1
      $search
      AND b.public = 1
     ORDER BY $order
     LIMIT 100"; //, $limit);

  $gifts = $wpdb->get_results($sql, ARRAY_A);

  if (count($gifts) == 0)
    die('<div class="no-results">No matching results</div>');

  foreach ($gifts as $gift) {
    draw_gift_item_capacity($gift);
  }
}

function draw_gift_item_capacity($gift) {
  $price =$gift['unitAmount']; 
  $amount = '$'.$price;
  if ($gift['varAmount']) $amount .= "+";
  $c = intval($gift['current_amount']);
  if ($c > 0) $amount = "\$$c of $amount";

  $need = intval($gift['unitsWanted']);
  if ($need > 0)
    $need = "<span class=\"need\">need <b>$need</b></span>";
  else
    $need = "<b class=\"sold-out\">Sold out</b>";

  if ($gift['towards_gift_id'] > 0) {
    $toward = "[part of " . $gift['WHOLE'] . "]";
  }

  $sales = $gift['sales']; if (empty($sales)) $sales = 0;
  $views = $gift['views']; if (empty($views)) $views = 0;
  $conv = $sales / ($views ? $views : 1);

  if ($c == 0 || $price == 0)
    $progress = 0;
  else 
    $progress = $gift['progress'];
  if ($progress > 1)
    $progress = 1;
  
  $progress = intval($progress * 100);
  $name = htmlspecialchars(stripslashes($gift['displayName']));
  $title = htmlspecialchars(stripslashes($gift['title']));
  
?>
<div class="capacity clearfix" id="cap-<?=$gift['ID']?>">
  <div class="progress" style="width:<?=$progress?>%"></div>
  <div class="contents">
    <img class="pic" src="<?= $gift['image'] ?>" />
    <h4 class="display-name"><?= $name ?> <?=$toward?>
      <span class="tags"><?= $gift['tags'] ?> - <?= get_blog_option($gift['blog_id'], 'blogname') ?></span>
    </h4>
    <div class="title"><?= $title ?> <a target="_new" href="http://<?= $gift['domain'] . '/wp-admin/post.php?post=' . $gift['post_id'] . '&action=edit' ?>">edit</a></div>
    <div class="inventory"><?= $need ?> at <?= $amount ?>
      <span class="conversion"><span class="sold">sold <?=$sales?> </span>/<span class="viewed"> <?=$views?> </span>=<span class="rate"> <?= number_format($sales/($views?$views:1) * 100, 4) ?>% </span> <a target="_new" href="http://<?= $gift['domain'] . '/wp-admin/admin.php?page=Gift-admin/admin-gift.php-active&gift_id=' . $gift['ID'] ?>">edit</a></span>
    </div>
  </div>
</div><?
}

?>
