<?php
/*
Plugin Name: Gift Admin
Plugin URI: http://www.aditi.com
Description: Gift Admin
Version: 1.0
Author: Ageesh
Author URI: http://localhost/wordpress/
*/


//Main function to display response/process request
function show_manage_gifts_page(){
  $updated = "Refresh stats";

  if (!empty($_REQUEST['update'])) {
    update_capacity_stats();
    $updated = "Updated";
  }

?>
<style>
.refresh { cursor: pointer; }
.capacity-list {
}
.capacity {
  padding: 5px;
  margin: 5px;
  position: relative;
}
.capacity .progress {
  margin-left: 90px;
  position: absolute;
  z-index: 1;
  height: 60px;
  background: #ddf;
}
.capacity .contents {
  position: relative;
  z-index: 2;
}
.capacity .display-name { margin: 0; }
.capacity .pic {
  width: 80px;
  height: 60px;
  float: left;
  margin-right: 10px;
  border: 1px solid #888;
}
.capacity .title { margin-bottom: 5px; }
.capacity .title a { font-size: 8pt; }
.capacity .tags {
  font-size: 8pt;
  font-style: italic;
  margin-left: 10px;
  font-weight: normal;
}
.capacity .rate { color: #008; }
.capacity .need { color: #080; }
.capacity .sold-out { color: #800; }
.no-results {
  padding: 10px;
}

.conversion .sold { cursor: hand; cursor: pointer; }
.conversion .viewed { cursor: hand; cursor: pointer; }

#top-list { position: absolute; right: 5px; z-index: 10; display: none;
  margin-right: 20px; }
#top-list table { background: white; border: 1px solid black; padding: 5px;}
#top-list td { padding: 2px 5px; }
#gifts-list { height: 530px; overflow-y: scroll; overflow-x: hidden; }

</style>
<div style="margin:15px 10px 0 0; padding:10px; border: 1px solid #ccc;">
<form id="gifts-filter" method="post" action="">
 <div style="float:right; position: relative;">
   <button name="update" value="true"><?= $updated ?></button>
   <div id="top-list"></div>
 </div>
 <span class="refresh">Find:</span> <input id="gifts-search" name="search" />
 by
 <? add_capacity_order("price") ?>
 <? add_capacity_order("need") ?>
 <? add_capacity_order("progress") ?>
 <? add_capacity_order("views") ?>
 <? add_capacity_order("sales") ?>
 <? add_capacity_order("conversion") ?>
<div style="margin-left: 195px;">
 <? add_capacity_option("part","partial") ?>
 <? add_capacity_option("whole","whole") ?>
 <? add_capacity_option("soldout","sold out") ?>
 <? add_capacity_option("instock","in stock") ?>
</div>
</form>
<div id="gifts-list" class="capacity-list">
</div>
<script type="text/javascript">
jQuery(function($) {
  var last = '';

  function resubmit() {
    var data = $("#gifts-filter").serialize();
    data = data + "&r=" + Math.random();
    $("#gifts-list").html('<div class="no-results">loading</div>').load("/ajax-gifts.php?cmd=manage", data);
    $("#top-list").hide();
  }
  var fnChange = $.debounce(3000, function onChange(ev) {
    var now = $(this).val();
    if (now ==  last)
      return true;
    last = now;
    resubmit();
  });
 
  $("#gifts-search").bind("change keyup", fnChange);
  $("input:radio, input:checkbox").bind("click change", $.debounce(1000, resubmit));
  $(".refresh").click(resubmit);
  $("form input").bind("keypress", function(ev) {
    if (ev.keyCode != 13) return true;
    resubmit();
    return false;
  });

  $(".conversion .sold").live("click", function() {
    var data = { id: $(this).closest('.capacity').attr('id') };
    $("#top-list").show().load("/ajax-gifts.php?cmd=top-sold",data);
  });
  $(".conversion .viewed").live("click", function() {
    var data = { id: $(this).closest('.capacity').attr('id') };
    $("#top-list").show().load("/ajax-gifts.php?cmd=top-viewed",data);
  });
});
</script>
<?
}

function add_capacity_order($order, $label = "") {
  add_capacity_box('radio', 'order', $order, $label);
}
function add_capacity_option($option, $label = "") {
  add_capacity_box('checkbox', 'options[]', $option, $label);
}
function add_capacity_box($type, $name, $value, $label = "") {
  if (empty($label)) $label = $value;
  ?><input id="x-<?=$value?>" type="<?=$type?>" name="<?=$name?>" value="<?=$value?>" /> <label for="x-<?=$value?>"><?=$label?></label><?

}

function add_manage_gifts_scripts() {
  $script =path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) ) . "/debounce.js");
  wp_enqueue_script( "debounce", $script, array( 'jquery' ) ); 
}

function update_capacity_stats() {
  global $wpdb;

  $stats = array();

  $sales = $wpdb->get_results(
     "SELECT g.id,sum(dg.unitsDonated) as sales 
      FROM gift g 
      JOIN donationGifts dg on dg.giftID=g.id 
      JOIN donation d on dg.donationID=d.donationID
      WHERE d.donationDate >= '2010-11-16'
      GROUP BY g.id 
      ORDER BY g.id", ARRAY_A);
  foreach ($sales as $sale) {
    $stats[$sale['id']] = $sale;
  }

  $views = $wpdb->get_results(
     "SELECT id, sum(count) as views FROM views GROUP BY id", ARRAY_A);
  foreach ($views as $view) {
    $s = $stats[$view['id']];
    if ($s != null)
      $s['views'] = $view['views'];
    else
      $s = $view;
    $stats[$view['id']] = $s;
  }

  foreach ($stats as $id=>$stat) {
    $wpdb->query($sql = $wpdb->prepare(
      "INSERT INTO gift_stats (id, lastUpdate, sales, views)
       VALUES (%d,NOW(),%d,%d)
       ON DUPLICATE KEY UPDATE sales=%s, views=%d, lastUpdate=NOW()", 
       $id,$stat['sales'],$stat['views'],$stat['sales'],$stat['views']));
  }
}

?>
