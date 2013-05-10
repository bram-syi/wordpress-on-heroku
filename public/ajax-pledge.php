<?

define('XMLRPC_REQUEST', FALSE); // this turns off WP-Minify HTML minifcation
include_once('wp-load.php');

$eid = $event_id = $_REQUEST['eid'];
global $post;
global $edit;

global $event_id;
$post = get_post($event_id);
$edit = false;
//pre_dump($_REQUEST);

if (isset($_REQUEST['list']) && current_user_can('edit_post', $event_id)) {

  $edit = true;
  if ($_POST) {
    if (wp_verify_nonce($_POST['pledge'],'update_pledge')) {
      //pre_dump($_POST);  

      if($_POST['closed'] == "on")
        update_post_meta($eid, CLOSED_PLEDGE_META, 1);
      else
        update_post_meta($eid, CLOSED_PLEDGE_META, 0);
 
      $num_books = intval($_POST['num_books']);
      if ($num_books > 0)
        update_post_meta($eid, 'readathon_books', $num_books);
      else
        update_post_meta($eid, 'readathon_books', '');

      $dues = array();
      foreach($_POST as $k=>$v) {
        if (strpos($k,'due_') === 0) {
          $dues[substr($k, 4)] = doubleval($v);
        }
      }
  
      if (update_pledges($eid, $dues)) {
        if ($_SERVER['HTTP_AJAX_METHOD']) { 
          echo "OK-LIST";
        } else {
//          echo "";
//          wp_redirect(get_permalink($eid));
        }
        die;
      }
    }
  }

  $closed = get_post_meta($event_id, CLOSED_PLEDGE_META, 1);


  ?>
  <form id="pledge-form" action="/ajax-pledge.php?list" method="POST" class="standard-form pledge-form based pledge-list">
  <div id="pledge-info" class="pledge-info" style="display:none; margin-bottom:0 -5px 20px;">Changes saved.</div>
  <input type="hidden" name="eid" id="eid" value="<?= $event_id ?>"/>
  <? wp_nonce_field('update_pledge', 'pledge'); ?>
  <div>
    <h2 style="float:left; margin-bottom: 10px;">My Pledges</h2>
    <label style="display:block; padding: 9px 0 0 140px;" for="pledge-closed"><input type="checkbox" name="closed" <?=($closed?'checked="checked"':'')?> id="pledge-closed" /> pledging is finished.
     I read <input type="text" style="width:30px; padding: 2px;" name="num_books" id="num_books" value="<?= xml_entities(get_post_meta($eid, 'readathon_books', TRUE)) ?>"> books.
    </label>
  </div>
  <?=draw_pledge_list(array('raw' => TRUE));?>
  <input type="submit" name="submit" class="button orange-button medium-button w100" value="<?=UPDATE_PLEDGE_TXT?>" style="display:block; margin: 20px auto -20px;" />
  </form><?    
    
} 
