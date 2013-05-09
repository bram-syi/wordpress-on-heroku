<?
/*
Plugin Name: Featured Gift Set
Plugin URI: http://www.seeyourimpact.com
Description: controls the featured gift set on gift browser
Version: 1
Author: Yosia Urip
Author URI: http://www.seeyourimpact.com
*/

add_action('admin_menu', 'featured_gift_set_add_menu', SW_HOME_PRIORITY);

function featured_gift_set_add_menu(){
  add_submenu_page(SW_HOME, __('Featured Gift Set', 'featured-gift-set'),
    __('Featured Gift Set', 'featured-gift-set'), 'manage_network', 'featured-gift-set', 
    'featured_gift_set_page');
}

function featured_gift_set_page(){
  global $wpdb;

  $ret = '';
  if(isset($_POST['submit'])){


    if($_POST['submit']=='Update Featured Gift Set'){
      $sql = 'SELECT ID FROM featuredContent';    
      $ids = $wpdb->get_col($wpdb->prepare($sql));
    
	    $sql = $wpdb->prepare(
        "UPDATE featuredContent SET "
          . "imageUrl=%s, title=%s, content=%s, "
          . "css=%s, parent=%d, status=%s "
	    . "WHERE ID='%d'",
	    $_POST['imageUrl_'.$id],
		  $_POST['title_'.$id],
		  $_POST['content_'.$id],
		  $_POST['css_'.$id],
		  $_POST['parent_'.$id],
		  $_POST['status_'.$id],
		  $id);
        
    } else if($_POST['submit']=='Insert Featured Content') {
	    	    
      $sql = $wpdb->prepare("INSERT INTO featuredContent "
        . "(imageUrl, title, content, css, parent, status) "
	      . "VALUES(%s,%s,%s,%s,%d,%s)",
	    $_POST['imageUrl_'.$id],
		  $_POST['title_'.$id],
		  $_POST['content_'.$id],
		  $_POST['css_'.$id],
		  $_POST['parent_'.$id],
		  $_POST['status_'.$id]
		  );		  	    
    }
	  // $ret.=$id.'--'.$sql;
	  $wpdb->query($sql);
	}

  $sql = "SELECT * FROM featuredGiftSet ";
  $rows = $wpdb->get_results($sql,ARRAY_A);
  $ret .= '<form method="post">';
  $ret .= '<div class="wrap">';
  $ret .= '<h2>Featured Gift Set</h2>'; 
  $ret .= '<div style="width:600px;">'; 
  $ret .= '<br style="clear:both"/>';    
  $ret .= '<style type="text/css">
  label{float:left;width:110px;}
  .row_cell{float:left;}
  .row{font-size:11px;clear:both;}
  
  </style>';

  //print_r($fcs);
  if(is_array($rows)) {
    $tbl = "";
    foreach($rows as $row) {
      $tbl .= '<div class="row" style="width:840px;">';
      $tbl .= '<div class="row_cell" style="width:40px;"><a href="#'.$row['ID'].'">'.$row['ID'].'</a>&nbsp;</div>';
      $tbl .= '<div class="row_cell" style="width:260px;">'.$row['title'].'&nbsp;</div>';
      $tbl .= '<div class="row_cell" style="width:500px;">'.$row['request'].'&nbsp;</div>';
      $tbl .= '<div class="row_cell" style="width:40px;">'.$row['status'].'&nbsp;</div>';
      $tbl .= '</div>';
    }
    $ret.=$tbl;
  }

  $ret .= '</div>';
  $ret .= '</div>';
  $ret .= '</form>';
    
  echo $ret;  
}

?>
