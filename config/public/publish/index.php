<?
define('WP_ADMIN', TRUE); // Yes, this is an admin page - this prevents some caching and minifying
define('APP_REQUEST', FALSE); // Turns off admin bar (!!)
define('STORY_LOGIN', TRUE);

require_once('storyTools.php');
require_once(ABSPATH . 'wp-admin/includes/admin.php');
require_once(ABSPATH . '/wp-includes/script-loader.php');

// Still looking for a way to replace this -- missing scripts
//require_once(ABSPATH . 'wp-includes/class-wp-editor.php');

////////////////////////////////////////////////////////////////////////////////

global $this_url;
$update = $_REQUEST['update'];

if ($blog_id == 1)
  wp_die('Please visit this page on a charity sub-site.');

force_login();

if (!user_can_edit()) {
  wp_die('Please log in as an editor.');
}

define('PAGE_SIZE', 7);
$this_id = intval($_REQUEST['ID']);
$this_page = intval($_REQUEST['page']);
$this_gift_id = intval($_REQUEST['gift']);
$this_status = strval($_REQUEST['status']);

if (!$_POST) {
  // Normalize the URL

  foreach ($_GET as $k=>$v) {
    if (!empty($v))
      continue;
    $removed[] = $k;
  }
  if (count($removed) > 0) {
    wp_redirect(remove_query_arg($removed));
    die();
  }
} else if ($update != 'photo' && !wp_verify_nonce($_REQUEST['story-nonce'], "story-$this_id")) 
  wp_die('failed - please retry'); 


global $errors;
$errors = array();

////////////////////////////////////////////////////////////////////////////////

// Perform delete, if selected.
$delete_nonce = $_REQUEST['delete'];
if ($delete_nonce != NULL && wp_verify_nonce($delete_nonce, "story-$this_id")) {
  $post_type_object = get_post_type_object('post');
  if (!current_user_can($post_type_object->cap->delete_post, $this_id))
    wp_die('sorry, no permission');

  wp_delete_post($this_id);
  wp_redirect(remove_query_arg(array('ID','delete'), $this_url));
}

////////////////////////////////////////////////////////////////////////////////

switch ($update) {
  case 'recipients':
    draw_thumbs($this_id, $this_page, $this_gift_id, $this_status); 
    die(); 
}

$gifts = get_all_gifts();

////////////////////////////////////////////////////////////////////////////////

if ($update == 'photo') {
  $result = handle_attachments($_POST['ID']);

  $result_html = str_replace('"','',$result['html']);

  if(isset($_REQUEST['html4'])) 
    $result['html'] = htmlentities($result_html);
  else {
    $result['html'] = $result_html;
  }

  header('Content-Type: text/html'); // MUST BE TEXT/HTML OR PLUPLOAD BREAKS
  echo json_encode($result);
  exit(); 
}

////////////////////////////////////////////////////////////////////////////////

$history = get_history($gifts, $this_id);
$story = load_story($this_id);
upgrade_story($story, $gifts, $history);

if ($_POST)
  modify_story($story, $_REQUEST);

$story['items'] = $items = get_story_donations($story); // get items in story (selected or not)
$story['needs'] = $needs = get_story_needs($story, $gifts, $items); // get parent gifts needed
$available = get_available_donations($items, $needs);  // get available items (if still needed)

//pre_dump($available);

switch ($update) {
  case 'donations':
    draw_available_donations($story, $gifts, $needs, $available);
    die();
}

$story['donors'] = $donors = get_story_donors($story, $items);

//pre_dump($donors);
//pre_dump($gifts);
//pre_dump($available);

$test = array();

if (!empty($_POST['select_donors'])) { // if auto donor

  $selected = array();
  $selected_amts = array();
  $any_amts = array();
  $agg_amts = array();
  
  foreach ($available as $donated) {
    // allocated already, continue
    if ($donated->story!=0 || in_array($donated->ID, $story['r_Items'])) continue; 

    $gid = $donated->giftID;
    $to = $donated->towards_gift_id ? $donated->towards_gift_id : $donated->giftID;

    // if parents not needed, continue
    if (empty($needs[$to])) continue; 

    // ignoring variable amount donation for now
    if ($donated->varAmount) { $any_amts[] = $donated; continue; }
	if ($donated->towards_gift_id) { $agg_amts[] = $donated; continue; }

    if (($selected_amts[$to] + $donated->amount) < $needs[$to]) {
      $selected[$to][] = $donated->ID; // assigning dg ID to selected
      $selected_amts[$to] += $donated->amount;

    } else if (($selected_amts[$to] + $donated->amount) == $needs[$to]) {
      $selected[$to][] = $donated->ID; // assigning dg ID to selected
      $selected_amts[$to] += $donated->amount;        
      unset($needs[$to]); // fulfilled already 

    } else {
      continue;    
    }

  }

  process_available_giveany($any_amts,$selected,$selected_amts,$needs,$story);
  process_available_giveany($agg_amts,$selected,$selected_amts,$needs,$story);

//pre_dump($selected);
//pre_dump($selected_amts);

  $n = array();
  foreach ($needs as $giftID => $qty) {
    unset($selected[$giftID]);
    $n[] = $gifts->all[$giftID]->displayName;
  }

  foreach ($selected as $id => $dgs)
    $story['r_Items'] = array_merge($story['r_Items'], $dgs);

  if (count($n) > 0)
    $errors[] = new WP_Error('auto', "There aren't enough donations of " . comma_list($n, ' or ') . 
      " available right now.<br>You can still create your story now, and assign donors later.");


  $story['items'] = $items = get_story_donations($story);
  $story['needs'] = $needs = get_story_needs($story, $gifts, $items);
  $available = get_available_donations($items, $needs);

//  pre_dump($available);

} // end if auto donor

////////////////////////////////////////////////////////////////////////////////

if (!empty($_POST['do_submit'])) {
  if (user_can_edit($story['ID'], 'publish_posts'))
    $story['new_status'] = 'publish';
  else
    $story['new_status'] = 'pending';
} else if ($story['status'] == 'draft' && user_can_edit($story['ID'], 'publish_posts')) {
  $story['new_status'] = 'pending';
}

////////////////////////////////////////////////////////////////////////////////

if ($_POST) {
  $new_id = save_story($story);
  if ($new_id > 0) {
    if ($_REQUEST['do_save'] == BUTTON_ADD_NEW) {
      wp_redirect(remove_query_arg('ID'));
      exit();
    }

    $redir = array('ID' => $new_id);
    if ($story['ID'] == 0)
      $redir['page'] = NULL;
    wp_redirect(add_query_arg($redir));
    exit();
  }
}

//pre_dump($story['items']);
//pre_dump($story['needs']);

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

// functions

function make_name($first, $last = '') {
  $last = substr(trim($last), 0,1);
  if ($last == '')
    return $first;

  return "$first $last";
}

function check_donation_dates($story) {
  if (is_array($story['items'])) {
    foreach ($story['items'] as $item) {
      $d1 = strtotime($item->donationDate);
      $d2 = time();    
      $diff = floor(($d2-$d1)/(60*60));
      //pre_dump ($diff);
      if($diff <= 24) {    
        return false;    
      }
    }
    return true;
  } else 
    return false;
}

////////////////////////////////////////////////////////////////////////////////

function clean_body($b) {
  // Remove caption
  $b = preg_replace('/\[caption(.*?)\](.*?)\[\/caption\]/ms', '', $b);

  // Remove comments
  $b = preg_replace('/\<!\s*--(.*?)(--\s*\>)/m', '', $b);

  // Remove Word spans with font-family
  $b = preg_replace('/\<span style="font-family: (.*?)"\>(.*?)\<\/span\>/i', '$2', $b);

  // Remove empty links
  $b = preg_replace('/<a[^>]*>(\s*?)<\/a>/i', '', $b);

  // Remove whitespace spans and divs, etc
  for ($i = 0; $i < 3; $i++)
    $b = preg_replace('/<span[^>]*>(\s*?)<\/span>/m', '$1', $b);
  for ($i = 0; $i < 3; $i++)
    $b = preg_replace('/<div[^>]*>(\s*?)<\/div>/m', '$1', $b);
  for ($i = 0; $i < 3; $i++)
    $b = preg_replace('/<p [^>]*>(\s*?)<\/p>/m', '$1', $b);
  $b = preg_replace('/<em>(\s*?)<\/em>/m', '$1', $b);

  // Remove salutation
  $b = preg_replace('/Dear\s.*[,:]/mi', '', $b);

  $b = str_replace("&#160;", " ", $b);

  return clean_text($b);
}

function draw_name($n) {
  if (empty($n))
    return;

  ?><span class="name"><?=esc_html($n)?></span><?
}
function map_status($s,$d='') {
  switch ($s) {
    case 'draft': $s = 'new'; break;
    case 'pending': $s = 'edit'; break;
    case 'future': $s = 'sending'; break;
    case 'publish': $s = "&#x2713;"; break;
  }
  if(!empty($d)) {
    $d = "(".count(explode(",",$d)).")";
  }
  return '<span class="status">' . $s . $d . '</span>';
}

function text_field($name, $value, $label, $optional = false, $type = 'text') {
?>
  <div class="text-field <?=$name?>">
    <? if (!empty($label)) { ?>
    <label for="<?=$name?>"><?= $label ?>
      <? if ($optional) { ?> <span class="optional">(optional)</span><? } ?>
    </label>
    <? } ?>

    <? if ($type == 'text') { ?>
      <input class="text-input" name="<?=esc_attr($name)?>" id="<?=$name?>" type="text" value="<?=esc_attr($value)?>" />
    <? } else if ($type == 'richedit') { ?>
      <?php
      //the_editor($value, $name, "", false);
      wp_editor($value,$name,array('quicktags' => true,'tinymce' => true));
      ?>
    <? } else { ?>
      <textarea name="<?=esc_attr($name)?>" id="<?=$name?>" style="display: block; padding: 2px; margin-bottom: 10px;"><?=esc_html($value)?></textarea>
    <? } ?>
  </div>
<? 
}

function check_field($name, $value, $label, $checkedValues = NULL, $escape = true, $class = '', $published = false) {
  
  $checked = $checkedValues === TRUE;
  if (!is_array($checkedValues)) $checkedValues = array();
  $id = esc_attr("{$name}_$value");

  if ($escape) $label = esc_html($label);

  $checked = $checked || in_array($value, $checkedValues);
  $class .= $published ? " $name check-sent" : " $name";

?>
  <div class="check-field <?= $class ?>">
    <input class="checkbox" type="<?= $published ? 'hidden' : 'checkbox' ?>" id="<?=$id?>" name="<?=$name?>[]" value="<?=esc_attr($value)?>" 
      <?= $checked ? ' checked=""' : '' ?>/>
    <label class="label" for="<?=$id?>"><?= $label ?></label>
  </div>
<?

  return $checked;
}

function select_option($label, $value, $selectValue = NULL) {
?>
<option value="<?=esc_attr($value)?>"<? if ($value == $selectValue) echo ' selected=""'; ?>>
  <?=esc_html($label)?>
</option>
<?
}

function load_story($id) {
  $story = initialize_story();

  if ($id == 0)
    return $story;

  $post = get_post($id);
  if (empty($post))
    return $story;

  $story['ID'] = $id;
  $story['r_Name'] = get_post_meta($post->ID, 'r_Name', true);
  $story['r_Notes'] = get_post_meta($post->ID, 'r_Notes', true);
  $story['r_Dear'] = !get_post_meta($post->ID, 'r_NoDear', true);
  $story['r_Gifts'] = as_ints(get_post_meta($post->ID, 'r_Gifts', true));
  $story['r_Items'] = as_ints(get_post_meta($post->ID, 'donation_items', true));
  $story['r_Body'] = trim($post->post_content);
  $story['r_Title'] = trim($post->post_title);
  $status = $story['old_status'] = $story['status'] = eor($post->post_status, 'draft');
  $story['post_date'] = $post->post_date;
  $story['r_ThumbnailID'] = get_post_thumbnail_id($post->ID);
  if (empty($story['r_Name'])) {
    $words = explode(' ', $story['r_Title']);
    $story['r_Name'] = str_replace("'s", "", $words[0]);
  }

  if (user_can_edit($story, 'publish_posts')) {
    $story['save_action'] = BUTTON_PENDING;
    $story['publish_action'] = BUTTON_PUBLISH;
  } else if (user_can_edit($story)) {
    $story['save_action'] = BUTTON_SAVE;
    $story['publish_action'] = BUTTON_SUBMIT;
  }

  return $story;
}

function upgrade_story(&$story, $gifts, $history) {
  foreach ($gifts->all as $gift) {
    if ($gift->towards_gift_id != 0)
      continue;

    $sent = $history->sent_gifts[$gift->id];
    if ($sent && !in_array($gift->id, $story['r_Gifts']))
      $story['r_Gifts'][] = $gift->id;
  }

  $c = preg_match('/<img (.*?)class="(.*?)wp-image-([0-9]+?)"(.*?)\>/',
    $story['r_Body'], $matches);
  if ($c > 0) {
    $tid = $matches[3];
    if ($tid > 0 && empty($story['r_ThumnailID'])) {
      $story['r_ThumbnailID'] = $tid;
      $story['r_Body'] = str_replace($matches[0], '', $story['r_Body']);
    }
  }
}

function get_all_gifts() {
  global $wpdb, $blog_id;

  // Build gifts array
  $gifts = new stdClass;

  $gs = $wpdb->get_results($wpdb->prepare("
    SELECT * FROM gift 
    WHERE blog_id=%d AND active=1",
    $blog_id));

  foreach ($gs as $gift) { 
    $gifts->all[$gift->id] = $gift;
    if ($gift->towards_gift_id != 0) {
      $gifts->aggregatesTo[$gift->id] = $gift->towards_gift_id;
      $gifts->aggregates[$gift->towards_gift_id][] = $gift->id;
    } else {
      $gifts->aggregatesTo[$gift->id] = $gift->id;
      $gifts->aggregates[$gift->id][] = $gift->id;
    }
  }

  return $gifts;
}

function get_history($gifts, $id) {
  global $wpdb, $blog_id;

  $history = new stdClass;

  if ($id > 0) {
    $history->mails = $wpdb->get_results($sql = $wpdb->prepare(
      "SELECT 
       nh.donorID,donor.firstName,donor.lastName,
       count(*) as qty,nh.notificationID,nh.mailType,nh.donorID,nh.emailSubject,nh.sentDate,
       d.donationDate,
       dg.giftID
      FROM notificationHistory nh
      LEFT JOIN donation d on d.donationID=nh.donationID
      JOIN donationGifts dg on dg.donationID=d.donationID
      JOIN donationGiver donor on donor.ID=d.donorID
      WHERE nh.blogID = %d and dg.story=%d and nh.postID = %d and nh.success = 1 and d.test=0
      GROUP by donor.ID
      ORDER by nh.notificationID desc",
      $blog_id, $id, $id));
  } else
    $history->mails = array();

  if ($_GET['sql'] == 'yes') pre_dump( $sql );

  $history->sent_donors = array();
  $history->sent_gifts = array();

  foreach ($history->mails as $mail) {
    $giftID = $gifts->aggregatesTo[$mail->giftID];
    $history->sent_gifts[$giftID] = true;
    $history->sent_donors[$mail->donorID] = true;
  }

  return $history;
}

function draw_gift_filter($gifts, $this_gift_id, $this_status, $selected_id = 0) {
  global $this_url;

  ?>
  <div style="padding:2px 5px 0;">
    <form class="right" method="GET" action="<?=$this_url?>">
      <? if ($selected_id > 0) { ?>
        <input type="hidden" name="ID" value="<?= esc_attr($selected_id) ?>" />
      <? } ?>
<!--
      <select class="select-go" name="gift" value="<?=$this_gift_id?>">
        <?
          select_option('all gifts', '', $this_gift_id);
          foreach ($gifts->all as $gift) { 
            if ($gift->towards_gift_id != 0) 
              continue;
            select_option($gift->displayName, $gift->id, $this_gift_id);
          }
        ?>
      </select>
-->
      <select class="select-go" name="status" value="<?=$this_status?>">
        <? select_option('all ', "", $this_status); ?>
        <? select_option('unpublished ', "draft,pending", $this_status); ?>
        <? select_option('pending edits ', "pending", $this_status); ?>
        <? select_option('published &#x2713; ', "future,publish", $this_status); ?>
  <? if (false && current_user_can('level10')) { ?>
        <? select_option('unfinished? ', "unfinished", $this_status); ?>
  <? } ?>
      </select>
      <input type="submit" class="select-go" value="&raquo;" />

<!--
      <span style="padding-left:30px;">find in story:</span>
      <input type="text" size="15" style="border: 1px solid #ddd; padding:1px;" id="search" name="search" value="<?=esc_attr($_REQUEST['search'])?>" class="select-go ui" />
      <input type="submit" class="select-go" style="padding:1px 4px; font-size:8pt;" value="go" />
-->

    </form>
    <div style="font: bold 14pt Arial;"><?= bloginfo('name')?>: Publish Stories</div>
  </div>
  <?
}

function draw_thumbs($this_id = 0, $this_page = 0, $this_gift_id = 0, $this_status = '') {
  global $this_url;

  $opts = array(
    'post_status' => eor($this_status, 'publish,draft,future,pending'),
    'posts_per_page' => PAGE_SIZE,
    'paged' => $this_page + 1,
    'orderby' => 'id',
    'order' => 'DESC'
  );
  $opts['s'] = $_REQUEST['search'];
  if ($this_status == 'unfinished') {
    $opts['orderby'] = 'r_StoryVersion';
    $opts['order'] = 'ASC';
    $opts['post_status'] = 'publish,future';
    $opts['meta_key'] = 'r_StoryVersion';
    $opts['meta_value'] = 0;
  }
  $posts = query_posts($opts);

  ?>
  <div class="thumbs">
    <a href="<?= add_query_arg('ID', NULL, $this_url) ?>" class="new empty recipient <? if ($this_id==0) echo 'selected'; else echo 'not-selected'; ?>"><span class="button small-button gray-button new-button">add new</span><div class="notch down-notch"></div></a>
  <?

  if ($this_page == 0) {
    ?><div class="arrow"></div><?
    if (count($posts) == 0) { 
      ?><div class="none-found">No <? 
      if ($_REQUEST['search'] || $_REQUEST['status'])
        echo 'matching';
      ?> recipients found</div><?
    }
  } else {
    ?><a href="<?= add_query_arg('page', eor($this_page - 1, NULL), $this_url) ?>" class="prev arrow"> </a><?
  }

  foreach ($posts as $post) {
    $thumb = get_the_post_thumbnail($post->ID, array(100,100));
    $class = ($post->ID == $this_id) ? "selected" : "not-selected";
    if (empty($thumb)) 
      $class .= " empty";

    ?>
    <a class="recipient <?=$class?> post-<?=$post->post_status?>" href="<?= add_query_arg('ID', $post->ID, $this_url) ?>"><?= $thumb ?><?
      echo draw_name(get_post_meta($post->ID,'r_Name', true));
      echo map_status($post->post_status, get_post_meta($post->ID,'donation_items', true));
      ?>
      <div class="notch down-notch"></div>
    </a>
    <?
  }

  if (count($posts) < PAGE_SIZE) {
    ?><div class="arrow"></div><?
  } else {
    ?><a href="<?= add_query_arg('page', $this_page + 1, $this_url) ?>" class="next arrow"> </a><?
  }

  ?><div class="clearer"></div></div><?
}

function get_gift_quantities() {
  global $wpdb, $blog_id;
  $sql = $wpdb->prepare(
    "SELECT g.id,g.displayName,count(*) AS qty, 
    g2.unitAmount / g.unitAmount AS of,
    SUM(dg.amount) AS sum_amt, g2.unitAmount AS to_amt,
    g.unitAmount as price, g.towards_gift_id as parent,
    GROUP_CONCAT(dg.amount) as amounts
    FROM donationGifts dg
    LEFT JOIN donation d ON d.donationID=dg.donationID
    LEFT JOIN gift g ON dg.giftID=g.id
    LEFT JOIN gift g2 ON g.towards_gift_id=g2.id
    WHERE dg.blog_id=%d AND (dg.story=0 OR dg.story is null) AND d.test = 0
    GROUP BY dg.giftID
    ORDER BY g.towards_gift_id DESC", $blog_id);
  $donations = $wpdb->get_results($sql);

  $qty = array();
  foreach ($donations as $d) {
    if ($d->parent == 0) {
      $qty["g$d->id"] += $d->sum_amt; // Total the sum amount
    } else if (is_avg($d->id)) {
      $qty["g$d->parent"] += $d->sum_amt; // Total the sum amount
    } else {
      $qty["g$d->parent"] += $d->sum_amt; // Total the sum amount
    }

    $a = array();
    foreach (explode(",", $d->amounts) as $amt) {
      $a[$amt]++;
    }
    foreach ($a as $amt=>$ct) {
      $q = ($ct > 1) ? "{$ct}x" : "";
      $a[$amt] = $q . str_replace(".00","", as_money($amt));
    }
    $amounts = implode(', ', $a);

    $qty[$d->id] = "<span class='more'> [#{$d->id}]</span> <span class=\"quantity\"><b>".as_money($d->sum_amt)."</b> $amounts</span>";    
  }

  return $qty;
}

function draw_status($story) {
  if (!user_can_edit())
    return true;

  $needs = array();
  if (empty($story['r_Name']))
    $needs[] = "enter the recipient's name";
  if (count($story['r_Gifts']) == 0)
    $needs[] = "select one or more received gifts";

  if ($story['ID'] > 0)
    $button = BUTTON_SAVE;
  else
    $button = BUTTON_ADD_RECIPIENT;
  
  if (count($needs) > 0) {
    if (trim($story['r_Title']) == '' && trim($story['r_Body']) == '')
      $action = "begin";
    else 
      $action = "update";

    if (intval($story['r_ThumbnailID']) == 0)
      $needs[] = "upload a photo";
    ?>
    <div class="story-status">
      Please <?= comma_list($needs); ?>.
      <? if (count($needs) > 1) echo "<br>"; ?>
      Press "<?= $button ?>" to <?= $action ?> this recipient's story.
    </div>
    <?
    return true;
  }

  return false;
}

function draw_post_status($story) {
  switch ($story['status']) {
    case 'draft': 
      $status = "This story is a draft.";
      break;
    case 'pending':
      $status = "This story has been submitted for editing.";
      break;
    case 'publish':
      $status = "This story has been sent.";
      break;
    default:
      $status = $story['status'];
  }
 
  if (!empty($status)) {
    ?>
    <div class="story-status">
      <?= $status ?>
    </div>
    <?
    return true;
  }

  return false;
}

function draw_errors($errors) {
  if (!is_array($errors) || count($errors) == 0)
    return;

  ?><div class="error errors"><?
  foreach($errors as $error) {
    ?><div><?= $error->get_error_message() ?></div><?
  }
  ?></div><?
}

function draw_recipient_gift_options(&$story, $gifts, $history) {
  global $wpdb;
  $quantities = get_gift_quantities();

  ?><div id="r_Gifts"><?
  foreach ($gifts->all as $gift) {
    if ($gift->towards_gift_id != 0)
      continue;

    $sent = $history->sent_gifts[$gift->id]; 
    $name = esc_html(stripslashes($gift->displayName));

    if (!$sent) {
      $sum_parts = intval($quantities["g$gift->id"]);
      $qty = intval($sum_parts / $gift->unitAmount);
      $remainder = $sum_parts % $gift->unitAmount;

      $sep = "";
      if ($qty > 0) {
        $qty = "<b>$qty</b>";
        $sep = " + ";
      } else
        $qty = "";

      if ($remainder > 0) {
        $remainder = str_replace(".00","", as_money($remainder));
        $qty .= "<span class=\"more\">$sep$remainder of $$gift->unitAmount to next story</span>";
      }

      if (!empty($qty))
        $name .= ": <span class=\"quantity\">$qty</span>";

      foreach ($gifts->aggregates[$gift->id] as $id) {
        if (empty($quantities[$id]))
          continue;
        $ag = $gifts->all[$id];
        $qty = $gifts->all[$gift->id]->unitAmount / $ag->unitAmount;
        $avg_tgi = get_avg_tgi($ag->id);
        $tg = $wpdb->get_row($wpdb->prepare("SELECT * FROM gift WHERE id = %d", $avg_tgi));
        if ($avg_tgi > 0) {
          $name .= "<span class=\"other-gift hidden\">&#183; " . $quantities[$id] . "</span>";
        } else {
          $name .= "<span class=\"other-gift hidden\">&#183; " . esc_html(stripslashes($ag->displayName)) . $quantities[$id] . "</span>";  
        }
      }
    }

    check_field('r_Gifts', $gift->id, $name, $sent ? TRUE : $story['r_Gifts'], false);
  }
  ?></div><script>
    $(".check-field.disabled[.checkbox:not(:checked)]").remove();
    var unchecked = $(".has-story .r_Gifts :checkbox:not(:checked)");
    var disabled = unchecked.closest(".cant-edit .check-field");
    if (unchecked.length > disabled.length + 1) {
      if ($(".has-story .r_Gifts :checkbox:checked, .has-story #r_Gifts .check-sent").length > 0) {
        unchecked.closest('.check-field').hide();
        $("<div class='another-gift'>Edit gifts</div>").appendTo("#r_Gifts");
      }
    }
    disabled.remove();
  </script><?
}

function draw_recipient_info(&$story, $gifts, $history) {
  global $this_url;

  if (user_can_edit($story, 'delete_posts')) {
    $delete_link = '<a class="right delete-link" href="' . add_query_arg('delete', wp_create_nonce("story-{$story['ID']}"), $this_url) . '">delete</a>';
  }

  ?>
  <div class="full-info">
    <div class="left panel-1">
      <? 
      text_field('r_Name', clean_text($story['r_Name']), "<span class=\"left\">Recipient</span> $delete_link");
      draw_recipient_gift_options($story, $gifts, $history);
      ?>
    </div>

    <div class="left panel-3 panel-last">
      <div>
        <? text_field('r_Notes', clean_text($story['r_Notes']), "Notes - not shown to donors", true, 'textarea'); ?>
<?
$an = 0;
if (!empty($story['ID'])) {
  $args = array(
    'post_parent' => $story['ID'],
    'post_status' => 'inherit', 
    'post_type' => 'attachment', 
    'post_mime_type' => 'audio', 
    'order' => 'ASC', 
    'orderby' => 'menu_order ID');
  foreach (get_children($args) as $att) {
    $an++;
    $url =  wp_get_attachment_url($att->ID);
    // $url = "http://ia700706.us.archive.org/32/items/JerryHarringtonAacTest/test.m4a";
    echo '<a class="audio-notes" target="_new" href="' . $url . '">Voice notes</a>';
  }
}
if ($an == 0 && get_option('voice-notes') == TRUE) {
  ?><div class="audio-notes"><input class="upload" type="file" name="r_Audio" /></div><?
}
?>
      </div>
    </div>

    <? if ($story['ID'] > 0) { ?>
      <div class="divider"></div>
    <? } ?>
  </div>
  <?
}

function draw_story_actions($story) {
  global $this_url;

  if (!user_can_edit($story))
    return;

  ?><div class="actions"><?
    if (is_published($story)) { ?>
      <input name="do_publish" type="submit" class="saves left button medium-button green-button" value="<?= BUTTON_UPDATE ?>" />
      <a id="preview-link" target="_new" href="<?= get_permalink($story['ID']) ?>" class="left button small-button white-button"><?= BUTTON_VIEW ?></a>

      <? if (current_user_can('level10')) { ?>
        <a target="_new" class="right button small-button white-button" href="<?= get_site_url($blog_id, "/wp-admin/post.php?action=edit&post={$story['ID']}") ?>">advanced edit &raquo;</a>
      <? } ?>
    <? } else if ($story['ID'] > 0) { ?>
      <input name="do_save" type="submit" class="saves left button medium-button green-button" value="<?= $story['save_action'] ?>" />

      <? if (!empty($story['publish_action'])) { ?>
        <span class="left" style="padding: 7px; font-weight: bold; color: #888;"> or </span>
        <input name="do_submit" type="submit" class="saves left button medium-button green-button" value="<?= $story['publish_action'] ?>" />
      <? } ?>

      <a id="preview-link" target="_new" href="<?= get_permalink($story['ID']) ?>" class="left button small-button white-button"><?= BUTTON_PREVIEW ?></a>
      <a id="cancel-link" href="<?= $this_url ?>" class="left button small-button white-button"><?= BUTTON_CANCEL ?></a>

      <? if (current_user_can('level10')) { ?>
        <a target="_new" class="right button small-button white-button" href="<?= get_site_url($blog_id, "/wp-admin/post.php?action=edit&post={$story['ID']}") ?>">advanced edit &raquo;</a>
      <? } ?>
    <? } else { ?>
      <input name="do_save" type="submit" class="saves button medium-button green-button" value="<?= BUTTON_ADD_RECIPIENT ?>" />
      <input name="do_save" type="submit" class="saves button small-button white-button" value="<?= BUTTON_ADD_NEW ?>" />
    <? } ?>

  </div>
  <?
}

function draw_status2($story, $gifts) {
  $needs = array();

  if (!user_can_edit($story))
    return false;

  if (intval($story['r_ThumbnailID']) == 0)
    $needs[] = "upload a photo";

  $has_needs = array_filter($story['needs'], 'has_need');
  if (count($has_needs) > 0)
    $needs[] = "assign donors";

  switch ($story['status']) {
    case 'draft':
      $needs[] = "compose the story";
      break;
    case 'pending':
      if (user_can_edit($story, 'publish_posts'))
        $needs[] = "review this story";
      break;
  }

  switch ($story['publish_action']) {
    case BUTTON_SUBMIT:
      $needs[] = "press \"" . BUTTON_SUBMIT . "\" when finished";
      break;
    case BUTTON_PUBLISH:
      if (!is_published($story) || count($needs) > 0)
        $needs[] = "press \"" . BUTTON_PUBLISH . "\" to send to the donors"; 
      break;
  }

  if (count($needs) == 0)
    return;

?>
  <div class="story-status">
    Please <?= comma_list($needs); ?>.
  </div>
<? 
}

function draw_history($history) {
  ?><div class="notifications"><?
  foreach ($history->mails as $mail) { 
    ?>
    <div class="notification">
      <?= $mail->firstName ?> - notified on <?= short_date(strtotime($mail->sentDate)) ?>
    </div>
    <? 
  }
  ?></div><?
}

function draw_revisions($id) {
  global $blog_id;

  if (!user_can_edit($id, 'publish_posts')) 
    return false;

  ?><div class="revisions"><?
  foreach (wp_get_post_revisions($id, array('order'=>'DESC')) as $rev) {
    $user = get_userdata($rev->post_author);
    $name = $user->user_nicename;
    $date = short_date(strtotime($rev->post_modified));

    if ($name == $last_name && $date == $last_date)
      continue;
    $last_name = $name; $last_date = $date;

    ?>
    <div class="revision">
      <?= $user->user_nicename ?> edited on 
      <a target="_new" href="<?= get_site_url($blog_id, "/wp-admin/revision.php?revision=$rev->ID&action=edit") ?>">
        <?= $date ?>
      </a>
    </div>
    <?
  }
  ?></div><?
}

function get_story_donors($story, $items) {
  $donors = array();
  foreach ($items as $item) {
    $donors[$item->donorID] = (!empty($item->onbehalf)?$item->onbehalf:$item->firstName);
  }
  return array_values($donors);
}

function get_story_needs($story, $gifts, $items) {
  global $wpdb;

  $needs = array();
  
  // get amount needs 
  foreach ($story['r_Gifts'] as $gid) { // $gid is parent id
    $needs[$gid] = $gifts->all[$gid]->unitAmount; 
  }

  foreach ($items as $item) {  // items for the story 
    $to = $gifts->aggregatesTo[$item->giftID];
//    if (array_key_exists($to, $needs) && $needs[$to] > 0) { // if needed
//      if ($needs[$to] - $item->price >= 0) {
        $needs[$to] = round($needs[$to] - $item->price, 2); //pre_dump($amt);
        if ($needs[$to]==0) unset($needs[$to]);
//      }
//    }
  }

  return $needs;
}

function get_story_donations($story) {
  global $wpdb;

  if (count($story['r_Items']) == 0)
    return array();

  $items = $wpdb->get_results($sql =
    "SELECT dg.ID, donor.ID AS donorID, donor.firstName, 
      g.displayName, dg.giftID, d.donationDate, dg.amount AS price,
	  dg.onbehalf
    FROM donationGifts dg
    JOIN donation d ON d.donationID=dg.donationID
    JOIN donationGiver donor ON donor.ID = d.donorID
    JOIN gift g ON g.id = dg.giftID
    WHERE dg.ID in (" . implode(',', $story['r_Items']) . ")
    ORDER BY g.id ASC, dg.amount DESC, d.donationDate asc"); 

  return $items;
}

function map_donation_ids($dg) {
  return $dg->ID;
}
function has_need($v) {
  return $v != 0;
}

function hide_full_gifts($items, $needs) {
  $full_gifts = array();
  $full_gifts_with_agg = array();
  if (empty($items)) return array();
  foreach ($items as $item) {
    $g = get_gift_by_id($item->giftID);
    if (intval($g->towards_gift_id)>0) {
      if (!in_array($g->towards_gift_id,$full_gifts_with_agg))
          $full_gifts_with_agg[] = $g->towards_gift_id;
      $g = get_gift_by_id($g->towards_gift_id);
      $full_gifts[$g->id] = $g;
    } else {
      $full_gifts[$item->giftID] = $g;
    }
  }

  if (empty($full_gifts_with_agg)) return array();
  $no_needs = array();
  foreach ($needs as $k=>$need) {
    if (in_array($k,$full_gifts_with_agg)) {
       if ($need < $full_gifts[$k]->unitAmount) {
        $no_needs[]=$k;
      }
    }      
  }
  return $no_needs;  
}

function get_available_donations($items, $needs) {
  global $wpdb, $blog_id;

  $wheres = array();
 
  // Include existing donations
  if (count($items) > 0) 
    $wheres[] = "dg.ID in (" .implode(',', array_map('map_donation_ids', $items)) . ")";

  // Includes gifts where there is need
  $has_needs = array_keys(array_filter($needs, 'has_need')); // only if positive need
  $ignored_fulls = hide_full_gifts($items, $needs);

  if (count($has_needs) > 0)
    $wheres[] = "((dg.giftID IN (" . implode(',', $has_needs) . ") OR 
    dg.towards_gift_id in (" . implode(',', $has_needs) . "))    
    ".(empty($ignored_fulls)?"":"AND dg.giftID NOT IN (".implode(",", $ignored_fulls).")")."
    AND (dg.story = 0 or dg.story is null))";

  // Nothing to show? Do no work
  if (count($wheres) == 0)
    return array();

  $wheres = implode(' or ', $wheres);

  $donated = $wpdb->get_results($sql = $wpdb->prepare(
    "SELECT 
      count(*) as qty,GROUP_CONCAT(dg.ID) as ID,dg.giftID,
      d.donorID,u.display_name,donor.firstName,donor.lastName,g.displayName,
      d.donationDate,dg.amount,g.towards_gift_id,dg.story,d.donationID,g.varAmount,
      dg.onbehalf
    FROM donationGifts dg
    LEFT JOIN donation d on dg.donationID = d.donationID
    LEFT JOIN donationGiver donor on donor.ID = d.donorID
    LEFT JOIN wp_users u on u.id = donor.user_id
    LEFT JOIN gift g on g.id = dg.giftID
    WHERE dg.blog_id=%d and d.test = 0
      AND ($wheres)
    GROUP BY dg.ID
    -- GROUP BY donor.ID,g.id,dg.story
    ORDER BY d.donationDate asc", 
    $blog_id));
  if ($_GET['sql'] == 'yes') pre_dump( $sql ); 

  return $donated;
}

function draw_available_donations($story, $gifts, $needs, $donated, $history=null) {
  global $wpdb;

  $total = 0;
  foreach ($donated as $item) {
    if (in_array($item->ID, $story['r_Items'])) {
      $total += $item->amount;
    }
  }

  ?><a class="button white-button small-button" onClick="return clear_selections();" href="#">Clear All</a> <?

  if ($total > 0) echo as_money($total);
  ?><br><br>
  <div id="donations" style="height:500px; overflow:auto;"><?
  $checks = 0;
  $n_errors = 0;

  $need_names = array();
  foreach ($needs as $id=>$qty) {
    if ($qty > 0)
      $need_names[$id] = stripslashes($gifts->all[$id]->displayName);
  }

  if($story['status']!='publish' && $story['status']!='future') $published = false; else $published = true;

////////////////////////////////////////////////////////////////////////////////

  foreach ($donated as $item) {

    if ($item->story != 0 && $item->story == $story['ID']) continue;
    unset($need_names[$item->giftID]);

	if(!empty($item->onbehalf))
	  $name = $item->onbehalf;
	else
      $name = make_name($item->firstName, $item->lastName);
////

    if (is_avg($item->giftID)) {
      $avg_tg = get_avg_tgi($item->giftID,true);
//      pre_dump($item);
      $donor = esc_html("$name - ".as_money($item->amount)." for ".$avg_tg->displayName);
    } else {
      $donor = esc_html("$name - $item->displayName (".as_money($item->amount).")");
      if ($item->qty > 1) $donor .= " (x$item->qty)";
    }

////

    $date = strtotime($item->donationDate);
    $days = days_since($date);

    $donor .= ' <span class="date">' . short_date($date) . ($days > 10 ? '<span class="late">' . $days . ' days</span>' : '') . '</span>';

    // Is this already attached to a different story?
    if ($item->story != 0) {
      $item->error = true;
      $n_errors++;
      $donor .= '<a class="mistake" target="_new" href="' . add_query_arg('ID', $item->story) . '">duplicate?</a>';
    }

    $to = $item->towards_gift_id ? $item->towards_gift_id : $item->giftID;
    if (!in_array($to, $story['r_Gifts'])) {
      $item->error = true;
      $donor .= ' <span class="mistake">wrong gift?</span>';
      $n_errors++;
    }

    if ($needs[$item->giftID] < 0) {
      $item->error = true;
      $donor .= ' <span class="mistake">too many?</span>';
      $n_errors++;
    }

    $item_published = false;
    if (!empty($history->mails)) {     
      foreach ($history->mails as $mail) {
        if($mail->donorID == $item->donorID && $mail->giftID == $item->giftID) {
          if (in_array($item->ID, $story['r_Items']))
            $item_published = true;
          break;  
        }
      }
    }

    check_field('r_Items', $item->ID, $donor, $story['r_Items'], false, $item->error ? 'check-error' : '', $item_published);
  }

////////////////////////////////////////////////////////////////////////////////

  if ($n_errors > 0 && user_can_edit($story)) {
    ?><div class="story-status">Please confirm the donors.</div><?
  }

/*  
  if (count($need_names) > 0) {
    ?><div class="none-available"><b>No donors</b> of <?= comma_list(array_values($need_names), 'or') ?> are available.  You can create a story now and save it for future donations.</div><?
  }
*/

////////////////////////////////////////////////////////////////////////////////

  if ($story['ID'] > 0) {

    foreach ($donated as $item) {
      if ($item->story != $story['ID']) continue;

      if(!empty($item->onbehalf))
	    $name = $item->onbehalf;
	  else
        $name = make_name($item->firstName, $item->lastName);


      if (is_avg($item->giftID)) {
        $avg_tg = get_avg_tgi($item->giftID,true);
  //      pre_dump($item);
        $donor = esc_html("$name - ".as_money($item->amount)." for ".$avg_tg->displayName);

      } else {
        $donor = esc_html("$name - $item->displayName (".as_money($item->amount).")");
        if ($item->qty > 1) $donor .= " (x$item->qty)";
      }

      $date = strtotime($item->donationDate);
      $donor = esc_html($donor) . ' <span class="date">' . short_date($date) . '</span>';

//      $sql = $wpdb->prepare("SELECT notificationID FROM notificationHistory 
//        WHERE donationID=%d AND postID=%d AND success=1 AND (mailType = 2 OR mailType = 6)",
//        $item->donationID,$story['ID']);
//      $sent = $wpdb->get_var($sql);
 
      check_field('r_Items', $item->ID, $donor, TRUE, false, '', $published);
    }
  }

  ?></div><?
}

function process_available_giveany($any_amts,&$selected,&$selected_amts,&$needs,$story) {
  global $dga;
  $dga='';
  $arr_shift = $any_amts; // init the testing array
  foreach ($any_amts as $any) { // work only on give any donations
    array_shift($arr_shift); // move the testing array
    if ($any->story!=0 || in_array($any->ID, $story['r_Items'])) continue; // if assigned already
    $gid = $any->giftID;
    $to = $any->towards_gift_id ? $any->towards_gift_id : $any->giftID;
    if (empty($needs[$to])) continue; // if parents not needed, continue
    if (($selected_amts[$to] + $any->amount) < $needs[$to]) { // partial 
      // try to look fulfilling item(s) in the next elements of the array
      $oks = try_adding_up($any, $arr_shift, $needs[$to]-$selected_amts[$to], $to); 
      if($oks !== false) { // the lookup is successful
        $dga .= ') FOUND ';
        foreach ($oks as $ok) {
          $selected[$to][] = $ok->ID; // assigning dg ID (s) to selected
          $selected_amts[$to] += $ok->amount;        
        }
        unset($needs[$to]); // unset previously needed           
      }
    } else if (($selected_amts[$to] + $any->amount) == $needs[$to]) { // full
      $selected[$to][] = $any->ID; // assigning dg ID to selected
      $selected_amts[$to] += $any->amount;        
      unset($needs[$to]); // unset previously needed
      $dga .= 'FOUND';
    } else { // give any is too much -- multiple amount is not handled at this moment
      // TO DO : work on multiple items
      continue;    
    }
  }

  if($_REQUEST['dga']=='yes') {
    echo '<div style="color:#fff">';
    echo print_r($dga,true);
    echo '</div>';
  }
}

function try_adding_up($any, $remains, $needs, $to) {  
  global $dga;
  $fulfilled = false;
  $oks = array();
  $original_needs = $needs;
  $needs = $needs - $any->amount;
  $oks[] = $any; // assuming the first item will work

  if(empty($remains)) return false;

  $dga .= "<br/> look for ".($needs + $any->amount)." = ".$any->amount." + (";
  foreach ($remains as $rem) { // look for counter part(s) in the remainder of the array   
    $rem_to = $rem->towards_gift_id ? $rem->towards_gift_id : $rem->giftID;
    if($rem_to == $to) { // same needed item
      if ($needs >= $rem->amount) { // amount can be added without exceeding the need

        $dga .= $rem->amount. " + ";
        $needs = $needs - $rem->amount;
        $oks[] = $rem; // add the item to the pool
        if($needs == 0) return $oks; // need is satisfied
      }          
    }
  }
  $dga .= ")";

  // got to the end of the remainder but good combination is not found
  // try skipping the next immidate element and start over 

  array_shift($remains);
  return try_adding_up($any, $remains, $original_needs, $to); 
}


////////////////////////////////////////////////////////////////////////////////
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>
<title><?= bloginfo('name') ?>: Recipient Stories</title>
<?
  wp_deregister_script('jquery');
  wp_enqueue_script('publish', '/publish/publish.js');

//  wp_enqueue_script('tiny_mce', '/wp-includes/js/tinymce/wp-tinymce.php');
//  wp_enqueue_script('tiny_mce_config', '/wp-includes/js/tinymce/tiny_mce_config.php');
  wp_enqueue_script('syi-bbq', "/wp-content/themes/syi/jquery.ba-bbq.min.js", "jquery");

  // Audio:
  wp_enqueue_script('sm2', '/s/sm2/sm2.js');
  wp_enqueue_script('sm2-playable', '/s/sm2/sm2-playable.js');
  wp_enqueue_script('sm2-playable-ui', '/s/sm2/sm2-playable-ui.js');
  wp_enqueue_style('sm2-flashblock', '/s/sm2/flashblock.css');
  wp_enqueue_style('sm2-playable', '/s/sm2/sm2-playable.css');
  wp_enqueue_style('syi-buttons', '/wp-content/themes/syi/buttons.css');
  wp_enqueue_style('syi-style');
  wp_enqueue_style('publish', '/publish/publish.css');
  wp_enqueue_script('plupload-all');
  wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload', 'wpdialogs', 'wpdialogs-popup', 'wplink'));

  wp_deregister_script('jquery-tools');
  wp_deregister_script('swfobject');
  wp_deregister_script('l10n');
  wp_deregister_script('csimport');
  wp_deregister_script('youtube-api');
  wp_deregister_script('rangeinput');
  wp_deregister_script('jquery-dump');
  wp_deregister_script('jquery-ui-core');
  wp_deregister_script('jquery-ui-mouse');
  wp_deregister_script('jquery-ui-widget');
  wp_deregister_script('jquery-ui-sortable');

  $dir = get_bloginfo('template_directory');
  wp_enqueue_script('jq-form', '/publish/jquery.form.js');

  add_action('init', 'my_scripts');

  wp_admin_css('thickbox');
  do_action("admin_print_styles-post-php");
  do_action('admin_print_styles');
  wp_print_scripts('utils');
  wp_print_scripts('editor');

  add_thickbox();
  wp_head();

?>
<script>
$.playable('/s/sm2/swf', {
  autoStart : false, // Start playing the first item
  loopNext : false, // Play first item when no more next item
  playAlone : false, // Force stop/pause previous on skip
  flashVersion: 9,
  autoLoad: false,
  stream: true,
  autoPlay: false,
  loops: 1,
  multiShot: true,
  multiShotEvents: false,
  pan: 0,
  usePolicyFile: false,
  volume: 100,
  usePeakData: false,
  useWaveformData: false,
  useEQData: false,
  bufferTime: 3
});

</script>



</head>
<? 

//wp_tiny_mce();
?>
<body><div class="page">

<? draw_gift_filter($gifts, $this_gift_id, $this_status, $this_id); ?>
<? draw_thumbs($this_id, $this_page, $this_gift_id, $this_status); ?>

<form id="story-form" enctype="multipart/form-data" method="post" action="" class="<?= $this_id == 0? 'new_recipient' :'has-story' ?> <?= user_can_edit($story) ? 'can-edit' : 'cant-edit' ?>">
  <input type="hidden" name="ID" value="<?=esc_attr($this_id)?>" />

  <?  wp_nonce_field("story-$this_id", "story-nonce", false); ?>

  <div style="margin: 0px 20px 10px 20px;">
  <?
  $needs_more = draw_status($story);
  if (!$needs_more)
    draw_status2($story, $gifts);

  draw_errors($errors);
  ?>
  </div>

  <div class="left panel-2" style="padding: 0 0 0 20px;">

    <? draw_recipient_info($story, $gifts, $history); ?>
    <? draw_story_actions($story); ?>
    <? if ($this_id > 0) { // if (count($story['r_Gifts']) >= 0) { ?>
      <? text_field('r_Title', clean_text($story['r_Title']), "Story title"); ?>
      <? if (count(array_filter($needs, 'has_need')) > 0 && user_can_edit($story)) { ?>
        <input type="submit" name="select_donors" value="auto-donor&#8482;" class="right button small-button white-button" style="margin-top:0;"/>
      <? } ?>
      <div class="check-field r_Dear">
        <input class="checkbox" type="checkbox" name="r_Dear" value="1" <?= ($story['r_Dear'] == true) ? ' checked=""' : '' ?>>
        <div class="label" style="font: 12pt Arial;">
          Dear
          <?
          $donors = array_map('esc_html', $donors); 
          if (count($donors) > 0) 
            echo comma_list($donors) . ','; 
          else
            echo ' <span class="no-donors">(select donors)</span>,';
          ?>
        </div>
      </div>
      <div style="clear:both;"></div>
      <?

      text_field('r_Body', clean_body($story['r_Body']), "", false, 'richedit'); ?>
    <? } ?>

  </div>

  <div class="left picture-panel panel-last">
    <? $the_picture = wp_get_attachment_image( intval($story['r_ThumbnailID']), array(250,250), false, '') ?>
    <div class="r_Photo photo-holder <?= empty($the_picture) ? "no" : "yes" ?>-photo" id="photo-area">
      <div class="loading" src="loading.gif"></div>
      <div class="thumb-photo"><?= eor($the_picture, "No photo"); ?></div>
      <div class="buttons">
        <div id="r_Photo_c" style="text-align:center;">
          <input class="upload" type="file" name="r_Photo" />
          <div id="r_Photo" class="button white-button medium-button" style="display:none;"><div class="progress"></div><label>Upload a photo...</label></div>
        </div>
      <? if (empty($the_picture)) { ?>
      <div class="instructions">JPG/GIF/PNG under 2mb</div>
    <? } ?>
      </div>
      <div class="drag-msg">(you can drag a picture into the green box)</div>
      <input type="hidden" id="r_ThumbnailID" name="r_ThumbnailID" value="<?=esc_attr($story['r_ThumbnailID'])?>" style="width:50px;" />
    </div>

    <? if ($story['ID'] > 0) { ?>
      <div class="donor-list">
        <? draw_available_donations($story, $gifts, $needs, $available, $history); ?>
        <? draw_history($history); ?>
        <? draw_revisions($story['ID']); ?>
      </div>
    <? } ?>
  </div>

</form>

<?
do_action('admin_print_footer_scripts');
do_action('admin_footer');
//_WP_Editors::editor_js();

?>
</div></body></html>
