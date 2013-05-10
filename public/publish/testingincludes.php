<?
require_once('../wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/post.php');

include('storyTools.php');

define('PAGE_SIZE', 7);

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
$gifts = get_all_gifts();

////////////////////////////////////////////////////////////////////////////////

photoUpdate($update, $_POST, $this_id, $_REQUEST);

////////////////////////////////////////////////////////////////////////////////

$history = get_history($gifts, $this_id);
$story = load_story($this_id);
upgrade_story($story, $gifts, $history);
modifyStory($story, $_POST, $_REQUEST);

$story['items'] = $items = get_story_donations($story);
$story['needs'] = $needs = get_story_needs($story, $gifts, $items);
$available = get_available_donations($items, $needs);

switch ($update) {
  case 'donations':
	draw_available_donations($story, $gifts, $needs, $available);
    die();
}

////////////////////////////////////////////////////////////////////////////////

$story['donors'] = $donors = get_story_donors($story, $items);


if (!empty($_POST['select_donors'])) {
  $selected = array();
  foreach ($available as $donated) {
    if ($donated->story != 0)
      continue; // Already allocated
    if (in_array($donated->ID, $story['r_Items']))
      continue;

    $gid = $donated->giftID;
    if (empty($needs[$gid]))
      continue; // Don't need this

    $selected[$gid][] = $donated->ID;
    if (count($selected[$gid]) < $needs[$gid]) 
      continue; // Not enough yet

    $to = $donated->towards_gift_id ? $donated->towards_gift_id : $donated->giftID;
    foreach ($gifts->aggregates[$to] as $id) {
      if ($id == $gid) continue;
      unset($needs[$id]); unset($selected[$id]);
    }

    unset($needs[$gid]);
  }

  $n = array();
  foreach ($needs as $giftID => $qty) {
    unset($selected[$giftID]);
    $n[] = $gifts->all[$giftID]->displayName;
  }

  foreach ($selected as $id => $dgs)
    $story['r_Items'] = array_merge($story['r_Items'], $dgs);

  if (count($n) > 0)
    $errors[] = new WP_Error('auto', "There aren't enough donations of " . comma_list($n, ' or ') . " available right now.<br>You can still create your story now, and assign donors later.");

  $story['items'] = $items = get_story_donations($story);
  $story['needs'] = $needs = get_story_needs($story, $gifts, $items);
  $available = get_available_donations($items, $needs);
}

if (!empty($_POST['do_submit'])) {
  if (user_can_edit($story['ID'], 'publish_posts'))
    $story['new_status'] = 'publish';
  else
    $story['new_status'] = 'pending';
} else if ($story['status'] == 'draft' && user_can_edit($story['ID'], 'publish_posts')) {
  $story['new_status'] = 'pending';
}

saveStory($story, $donors, $thumbnail_id, $_POST, $_REQUEST);

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

//save_story($story) can be found in includes publishAPI.php

////////////////////////////////////////////////////////////////////////////////

function days_since($d) {
  $diff = time() - $d;
  $days = floor($diff / (60 * 60 * 24));

  return $days;
}

function short_date($d) {
  $days = days_since($d);

  if ($days > 150)
    return date('M j, Y', $d);

  return date('M j', $d);
}

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
function map_status($s) {
  switch ($s) {
    case 'draft': $s = 'new'; break;
    case 'pending': $s = 'edit'; break;
    case 'future': $s = 'sending'; break;
    case 'publish': $s = "&#x2713;"; break;
  }
  return '<span class="status">' . $s . '</span>';
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
      <?php the_editor($value, $name, "", false); ?>
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
      LEFT join donation d on d.donationID=nh.donationID
      JOIN donationGifts dg on dg.donationID=d.donationID
      JOIN donationGiver donor on donor.ID=d.donorID
      WHERE dg.blog_id = %d and dg.story=%d and nh.postID = %d and nh.success = 1 and d.test=0
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
      echo map_status($post->post_status);
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

  $donations = $wpdb->get_results($wpdb->prepare(
    "select g.id,g.displayName,count(*) as qty, g2.unitAmount / g.unitAmount as of
    from donationGifts dg
    left join donation d on d.donationID=dg.donationID
    left join gift g on dg.giftID=g.id
    left join gift g2 on g.towards_gift_id=g2.id
    where dg.blog_id=%d and (dg.story=0 or dg.story is null) and d.test = 0
    group by dg.giftID", $blog_id));
  
  $qty = array();
  foreach ($donations as $d) {
    $qty[$d->id] = " <span class=\"quantity\">[<b>$d->qty</b>" . (!empty($d->of) ? " of $d->of" : "") . "]</span>";
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
  $quantities = get_gift_quantities();

  ?><div id="r_Gifts"><?
  foreach ($gifts->all as $gift) {
    if ($gift->towards_gift_id != 0)
      continue;

    $sent = $history->sent_gifts[$gift->id]; 
    $name = esc_html(stripslashes($gift->displayName));

    if (!$sent) {
      $name .= $quantities[$gift->id];
    }

    foreach ($gifts->aggregates[$gift->id] as $id) {
      if ($id == $gift->id) continue;
      $ag = $gifts->all[$id];
      $qty = $gifts->all[$gift->id]->unitAmount / $ag->unitAmount;
      $name .= "<span class=\"other-gift\">or {$qty}x " . esc_html(stripslashes($ag->displayName));
      if (!$sent)
        $name .= $quantities[$id];
      $name .= "</span>";
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
    $donors[$item->donorID] = $item->firstName;
  }
  return array_values($donors);
}

function get_story_needs($story, $gifts, $items) {
  global $wpdb;

  $needed = array();

  foreach ($story['r_Gifts'] as $gid) {
    foreach ($gifts->aggregates[$gid] as $gift_id) {
      $needed[$gift_id] = $gifts->all[$gid]->unitAmount / $gifts->all[$gift_id]->unitAmount;
    }
  }

  $have = array();

  foreach ($items as $item) {
    $have[$item->giftID]++;

    // When one is completed, all of its aggregate equivalents are no longer necessary
    if ($have[$item->giftID] == $needed[$item->giftID]) {
      $to = $gifts->aggregatesTo[$item->giftID];

      foreach ($gifts->aggregates[$to] as $id) {
        if ($id != $item->giftID)
          $needed[$id] = 0;
      }
    }
  }

  $needs = array();
  foreach ($needed as $k=>$v) {
    $needed[$k] -= $have[$k];
    if ($needed[$k] == 0)
      unset($needed[$k]);
  }

  return $needed;
}

function get_story_donations($story) {
  global $wpdb;

  if (count($story['r_Items']) == 0)
    return array();

  $items = $wpdb->get_results($sql = $wpdb->prepare(
    "select
      dg.ID,donor.ID as donorID,donor.firstName,g.displayName,dg.giftID,d.donationDate
    from donationGifts dg
    left join donation d on d.donationID=dg.donationID
    left join donationGiver donor on donor.ID = d.donorID
    left join gift g on g.id = dg.giftID
    where 
     dg.ID in (" . implode(',', $story['r_Items']) . ")
    order by d.donationDate asc")); 

  return $items;
}

function map_donation_ids($dg) {
  return $dg->ID;
}
function has_need($v) {
  return $v > 0;
}

function get_available_donations($items, $needs) {
  global $wpdb, $blog_id;

  $wheres = array();
 
  // Include existing donations
  if (count($items) > 0) 
    $wheres[] = "dg.ID in (" .implode(',', array_map('map_donation_ids', $items)) . ")";

  // Includes gifts where there is need
  $has_needs = array_keys(array_filter($needs, 'has_need')); // only if positive need
  if (count($has_needs) > 0)
    $wheres[] = "(dg.giftID in (" . implode(',', $has_needs) . ") and (dg.story = 0 or dg.story is null))";

  // Nothing to show? Do no work
  if (count($wheres) == 0)
    return array();

  $wheres = implode(' or ', $wheres);

  $donated = $wpdb->get_results($sql = $wpdb->prepare(
    "select 
      count(*) as qty,GROUP_CONCAT(dg.ID) as ID,dg.giftID,
	  d.donorID,u.display_name,donor.firstName,donor.lastName,g.displayName,
	  d.donationDate,dg.amount,g.towards_gift_id,dg.story,d.donationID
    from donationGifts dg
    left join donation d on dg.donationID = d.donationID
    left join donationGiver donor on donor.ID = d.donorID
    left join wp_users u on u.id = donor.user_id
    left join gift g on g.id = dg.giftID
    where dg.blog_id=%d and d.test = 0
     and ($wheres)
    group by dg.ID
    -- group by donor.ID,g.id,dg.story
    order by d.donationDate asc", 
    $blog_id));
  if ($_GET['sql'] == 'yes') pre_dump( $sql ); 

  return $donated;
}

function draw_available_donations($story, $gifts, $needs, $donated) {
  global $wpdb;
  ?><div id="donations"><?
  $checks = 0;
  $n_errors = 0;

  $need_names = array();
  foreach ($needs as $id=>$qty) {
    if ($qty > 0)
      $need_names[$id] = stripslashes($gifts->all[$id]->displayName);
  }

//pre_dump($item); 
//pre_dump($story); 

  if($story['status']!='publish' && $story['status']!='future') $published = false; else $published = true;

////////////////////////////////////////////////////////////////////////////////

  foreach ($donated as $item) {

    if ($item->story != 0 && $item->story == $story['ID']) continue;
    unset($need_names[$item->giftID]);

    $name = make_name($item->firstName, $item->lastName);

    $donor = esc_html("$name - $item->displayName");
    if ($item->qty > 1) $donor .= " (x$item->qty)";

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

    check_field('r_Items', $item->ID, $donor, $story['r_Items'], false, $item->error ? 'check-error' : '', $published);
  }

////////////////////////////////////////////////////////////////////////////////

  if ($n_errors > 0 && user_can_edit($story)) {
    ?><div class="story-status">Please confirm the donors.</div><?
  }
  
  if (count($need_names) > 0) {
    ?><div class="none-available"><b>No donors</b> of <?= comma_list(array_values($need_names), 'or') ?> are available.  You can create a story now and save it for future donations.</div><?
  }

////////////////////////////////////////////////////////////////////////////////

  if ($story['ID'] > 0) {

    foreach ($donated as $item) {
      if ($item->story != $story['ID']) continue;

      $name = make_name($item->firstName, $item->lastName);

      $donor = esc_html("$name - $item->displayName");
      if ($item->qty > 1) $donor .= " (x$item->qty)";

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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>
<title><?= bloginfo('name') ?>: Recipient Stories</title>

<?
  wp_enqueue_script("jquery");
  wp_enqueue_script('word-count');
  wp_enqueue_script('post');
  wp_enqueue_script('editor');
  wp_enqueue_script('media-upload');
  wp_enqueue_script('utils');
  wp_enqueue_script('common');
  wp_enqueue_script('jquery-color');
  wp_enqueue_script('syi-bbq');

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

<style>
body { background: #eee; margin: 0; padding: 0; }
.page { font: 9pt Arial; width: 900px; border: 1px solid #666; border-top: 0px none; background: white; margin:0;padding: 0 0 100px 0; margin:0 auto; }
form { margin: 0; }
h1 { font-weight: bold; font-size: 11pt; }
.right { float: right; }
.left { float: left; } 
form, div { overflow: hidden; }
a img { border: 0px none; }

.clearer { clear:both; }
.zerosize { width: 0; height: 0; }
:disabled { background: white; }

select.select-go { border: 0px none; text-align: right; }
select.select-go option { text-align: right; }

/* IE6 */
.notch {
  _border-top-color: pink;
  _border-left-color: pink;
  _border-bottom-color: pink;
  _border-right-color: pink;
  _filter: chroma(color=pink);
  height:0;
  width:0;
  position: absolute;
  border-style: solid;
}

.error {
  background: #fbb url(warning.gif) no-repeat 7px 5px;
  color: #400;
  border: 1px solid #f88;
  margin: -1px;
}
.errors {
  font-size: 11pt;
  font-weight: bold;
  text-align: center;
  padding: 4px;
  margin: 0 0 20px 0;

  border-radius: 7px;
  -moz-border-radius: 7px;
}
.story-status {
  font-size: 11pt;
  font-weight: bold;
  padding: 5px 20px; 
  margin: 0px 0px 10px; 
  text-align: center;

  background: #cdf url(info.gif) no-repeat 7px 6px;
  color: #004;

  border-radius: 7px;
  -moz-border-radius: 7px;
}

.optional { color: #aaa; }
.text-field { position: relative; margin-bottom: 10px; }
.text-field .text-input, .text-field textarea {
  font-size: 14pt;
  font-weight: bold;
  padding: 2px;
  width: 100%;
  border: 1px solid #f0f0f0 !important;
  border-top: 1px solid #888 !important;
}
.r_Body {
  xpadding: 2px;
  border: 1px solid #f0f0f0 !important;
  border-top: 1px solid #888 !important;
}
.r_Body textarea {
  border: 0px none !important;
}
.text-field textarea {
  font: 10pt Arial;
  margin-top: 2px;
}
.text-field label { 
  display: block;
  height: 11pt;
  padding-left: 3px;
  font-size: 9pt;
  color: #444;
}
.cant-edit .text-field input, .cant-edit textarea { 
  border-left: 1px solid white !important;
  border-bottom: 1px solid white !important;
  border-right: 1px solid white !important;
  color: black !important;
}

/* RICH TEXT */
.mceLayout .mceStatusbar { height: 0 !important; display:none !important; }
.mceFirst, .mceLast { background: #eee; }
.mceButton, .mceOpen, .mceText, .mceAction {
  border: 0px none !important;
  background-color: white !important;
  cursor: hand; cursor: pointer;
}
.mceButton {
  margin: 1px 1px 0 !important; 
  border: 1px solid #ddd !important;
}
.mceMenu {
  background: white !important;
}
.mceMenuItemTitle { background: #eee; }
.mceButton, .mceText, #r_Body_formatselect_open {
  background: white !important;
}
.mceButton:hover {
  background-color: #EEF7FF !important;
}
.mceButtonActive { background: #DEF !important; }
.mceSplitButtonMenu {
  background: white !important;
}
#quicktags { background: #eee; }
.ed_button { 
  display: block;
  float: left;
  padding: 4px 4px;
  margin: 5px 0 5px 2px;
  background: white;
  border: 1px solid #f7f7f7;
}
.mceToolbar {
  padding: 2px !important;
  margin: 0px !important;
}
#editor-toolbar {
  position: absolute;
  right: 0;
  margin: 10px 10px 0 0;
}
#editor-toolbar a {
  padding: 2px;
  margin-left: 5px;
  cursor: hand; cursor: pointer;
  display: block;
}
#editor-toolbar a.active {
  display: none;
}

.check-field { margin-bottom: 5px; }
.check-field .checkbox {
  float: left;
  width: 18px;
  margin: 3px 0 0 3px;
  _margin: 0 0 0 6px;
}
.check-field .label {
  display: block;
  font-size: 12pt;
  margin: 0 0 0 24px;
  cursor: hand; cursor: pointer;
}
.check-field .strike {
  text-decoration: line-through;
  color: #ccc;
}
.check-sent {
  background: url(/wp-content/images/16_RightArrow.gif) no-repeat 4px 2px;
}
.check-error {
  color: red;
  background: #fdd;
}
.check-error .mistake {
  padding: 3px;
  margin-left: 10px;
  font-size: 9pt;
  color: red;
}
.need-donors {
  padding: 4px 8px;
  background: #eee;
}

.thumbs, .thumbs a, .thumbs a:hover { text-decoration: none; }
.thumbs img { border: 0; width: 100px; height: 100px; }
.thumbs .arrow { width: 28px; border: 0px none; _display: none; }
.thumbs .recipient { width: 100px; height: 100px; text-align: center; position: relative; overflow: hidden; }
.thumbs .empty { background: url(/wp-content/images/defuser.jpg) no-repeat 0 0; }
.thumbs .none-found {
  padding: 35px 10px;
  float: left;
  font-size: 12pt;
  color: #666;
}

.recipient .status { font-size: 8pt; font-weight: bold; position: absolute; top: -1px; right: -1px; background: #080; color: white; padding: 1px 5px; display: block; border: 1px solid white; }
.post-pending .status { background: #C60; }
.post-draft .status { background: #DEF; color: black; }
.recipient .name { font-size: 8pt; position: absolute; bottom: 0; left: 0; width: 100%; background: black; color: white; padding: 1px 0; overflow: hidden; text-align:center; display: block; opacity: 0.8; }
.recipient .down-notch {
  border-color: black transparent transparent transparent;
  bottom: -22px;
  left: 39px;
  border-width: 11px;
}

.thumbs .post-draft { border: 2px solid white; }
.thumbs a, .thumbs .arrow, .thumbs .new { float: left; display: block; height: 100px;}
.thumbs .next { background: url(/wp-content/images/right_arrow.png) no-repeat 50% 50%; }
.thumbs .prev { background: url(/wp-content/images/left_arrow.png) no-repeat 50% 50%; }
.thumbs .not-selected { border: 2px solid white; }
.thumbs .selected { border: 2px solid #004; }
.not-selected:hover { border: 2px solid #888; -webkit-transition: border 0.3s linear;  }
.not-selected:hover .status { -webkit-opacity: 0.2; -webkit-transition: opacity 0.5s linear; }
.not-selected:hover .name { -webkit-opacity: 0.4; -webkit-transition: opacity 0.5s linear; }
.selected .status { border-color: black; }
.not-selected img { opacity: 0.6; -webkit-transition: opacity 1s linear; }
.not-selected:hover img { opacity: 1; -webkit-transition: opacity 0.5s linear; }
.not-selected .down-notch { display: none; }
.thumbs { background: url(/wp-content/images/top-shadow-footer.png) repeat-x 0 100%; padding: 5px 5px 20px;}

.new-button { margin-top: 75px; }
.divider { clear: both; display:none; border-top: 1px dashed #ccc; margin-bottom: 15px; }
.actions { padding-bottom: 14px; clear:both; height: 40px; }
.actions .action { padding: 4px; font-size: 10pt; color: #008; }
.delete-link { color: #A22; text-decoration: none; }
.delete-link:hover { color: #C00; text-decoration: underline; }
.actions .small-button { margin-top: 7px; }
.actions #cancel-link { display: none; }


.picture-panel { margin: 0 25px 10px 0; position: relative; }
.panel-1 { width: 325px; margin: 0 20px 0 0; _width: 305px; }
.panel-2 { width: 590px; margin: 0 20px 0 0; }
.panel-3 { width: 240px; margin-right: 25px; overflow: visible; }
.panel-last { margin-right: 0px; }
.picture-panel .photo-holder { margin-bottom: 15px; }
.picture-panel input { width: auto; }

.picture { position: relative; display: block; text-align: center; margin-top: 5px; overflow: hidden; }

.picture-panel .draggable .thumb-photo {
  border: 2px solid #484;
}
.picture-panel .drag-msg {
  display: none;
  text-align: center;
  font-size: 10px;
  padding: 2px;
  width: 100%;
  color: #484;
}
.picture-panel .draggable .drag-msg {
  display: block;
}

.photo-holder { position: relative; width: 252px; }
.photo-holder .loading { position: absolute; 
  width: 252px; height: 100px;
  top: 0; left: 0;
  border-radius: 10px; -moz-border-radius: 10px;
  background: url(loading.gif) no-repeat 50% 50%; z-index: 50; display: none; }
.photo-holder .buttons { position: absolute; left: 0; bottom: 20px; width: 250px; text-align: center; }
.photo-holder .buttons form input { cursor:pointer; z-index:2; }
.photo-holder .button {
  overflow: hidden;
  z-index: 2;
  cursor:pointer;
}
.photo-holder .button .progress {
  width: 0;
  position: absolute;
  top: 0; bottom: 0; left: 0;
  background: #AFA;
  webkit-border-radius: .5em;
  -moz-border-radius: .5em;
  border-radius: .5em;

}
.photo-holder .button label {
  position: relative;
  z-index: 0;
}

.thumb-photo { text-align: center; }
.no-photo .thumb-photo { 
  border: 2px dashed #ddd; text-align: center; font-size: 2.0em; color: #ccc; background: #f7f7f7;
  padding: 60px 30px 80px 30px; }
input:active { outline: none; }
.yes-photo .thumb-photo { padding: 1px; border: 1px solid #ccc; background: #f8f8f8; }
.no-donors { color: #c0c0c0; }
.instructions { font-size: 8pt; font-style: italic; color: black; }
.upload { font-size: 9pt; margin-bottom: 10px; height: 20px; }

.none-available { padding: 10px 20px; font-size: 11pt; color: #666; }
.notifications,.revisions { margin-top: 20px; }
.revisions a { text-decoration: underline; }

#r_Body { height: 500px; }
#r_Notes { height: 102px; font-size: 10pt; }
.r_Dear { margin-left: -22px; overflow: visible; }
#r_Gifts { margin-bottom: 10px; }
.r_Gifts .other-gift {
  display: block;
  font-size: 10pt;
  margin: 2px 0 0 0;
}
.r_Gifts .quantity { color: #666; }
.r_Gifts .quantity b { color: black; }
.another-gift {
  display: block;
  padding: 3px 15px;
  text-decoration: underline;
  cursor: hand; cursor: pointer;
  color: #008;
}

.donor-list { width: 252px; }
.donor-list .date { font-size: 8pt; color: #888; padding-left: 8px; white-space: nowrap; }
.donor-list .date .late { padding-left: 5px; font-weight: bold; color: #a00; }

.x-full-info { display: none; }
.short-info { font-size: 12pt; }
.short-info .recipient-name { font-weight: bold; }

</style>
</head>
<? wp_tiny_mce(); ?>
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
      <? text_field('r_Body', clean_body($story['r_Body']), "", false, 'richedit'); ?>
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
        <? draw_available_donations($story, $gifts, $needs, $available); ?>
        <? draw_history($history); ?>
        <? draw_revisions($story['ID']); ?>
      </div>
    <? } ?>
  </div>

</form>

<script type="text/javascript">
var dis = $(".cant-edit").find(".text-field, .check-field");
dis.addClass('disabled').find("input, textarea").attr("disabled", true);
//$("#edButtonPreview").click();

$(function() {
  //switchEditors.go('r_Body','tinymce');
  $("select.select-go").change(function() {
    $(this).closest('form').submit();
  }).siblings('.select-go[type=submit]').hide(); 
  $(".recipient.selected").live('click', function() {
    return false;
  });

  var last = '';
  var fnChange = $.debounce(500, function onChange(ev) {
    var now = $(this).val();
    if (now == last)
      return true;
    last = now;
    refresh_thumbs();
  });
  $("#search").bind("change keyup", fnChange).bind("keypress", function(ev) {
    if (ev.keyCode != 13) return true;
    //refresh_thumbs();
    return false;
  });
  
  $gifts = $(".r_Gifts input");
  if ($gifts.length <= 1) {
    $("select[name=gift]").hide(); // Hide the gift dropdown
    $(".new_recipient .r_Gifts input").attr('checked',true);
  }

  var thumbs_url = window.location + "";
  var ajax_req = null;
  function refresh_thumbs(url) {
    $(".thumbs .recipient:not(.new)").css('opacity',0.5);
    if (ajax_req != null)
      ajax_req.abort();
    ajax_req = $.ajax({
      url: url || thumbs_url,
      data: { update: 'recipients',
        search: $("#search").val() },
      success: function(data) {
        $(".thumbs").replaceWith(data);
        thumbs_url = url;
      }
    });
  }

  $(".thumbs a.arrow").live('click', function() {
    refresh_thumbs($(this).attr('href'));
    return false;
  });

  var photo = $('.r_Photo');

  if(photo.length > 0) {
    var loading = photo.find('.loading');
    var thePhoto = photo.find('.thumb-photo');

    var r_Photo = new plupload.Uploader({
      runtimes : 'html5,html4',
      browse_button : 'r_Photo',
      container : 'r_Photo_c',
      drop_element : 'photo-area',
      max_file_size : '5mb',
      //unique_names: true,
      //chunk_size: '5mb',
      url: $.param.querystring(window.location + "", {update: 'photo'}),
      flash_swf_url: '/wp-content/plugins/wplupload/js/plupload.flash.swf',
      silverlight_xap_url: '/wp-content/plugins/wplupload/js/plupload.silverlight.xap',
      resize : {width : 1024, height : 1024, quality : 95},
      filters : [
        {title : "Image files", extensions : "jpg,jpeg,gif,png"}
      ]
    });

    r_Photo.bind('Init', function(up, params){

      if (($.browser.msie && $.browser.version<8) || (!$.browser.msie && up.runtime == "html4"))//
        return;

      photo.find("input[type=file]").hide();
      $("#r_Photo").show();

      try {
      if(!!FileReader && !((up.runtime == "flash") || (up.runtime == "silverlight")))
        photo.addClass('draggable');
      }
      catch(err){}
    });
    r_Photo.init();
    r_Photo.bind('FilesAdded', function(up, files) {
//alert(up.runtime);
      if(up.runtime == 'html4') { up.settings.url=up.settings.url+'&html4' }
      loading.show();
      thePhoto.css('opacity', 0.3);
      $("input[type=submit]").addClass('hold');
      photo.find('.button label').html('Uploading...');

      up.start();
      up.refresh();
    });
    r_Photo.bind('UploadProgress', function(up, file) {
      var prog = up.total.percent;
      $(".photo-holder .progress").css('width', prog + '%');
    });
    r_Photo.bind('FileUploaded', function(up, file, ret) {
      $(".photo-holder .progress").css('width', 0);
      loading.hide();
      thePhoto.css('opacity', 1);
      $(".hold").removeClass("hold");

      var data = $.parseJSON(ret.response);
      if (!data || (data.error != null)) {
        photo.find('.button label').html('<span style="color:red;">Upload failed!</span>');
        return;
      }
      photo.find('.button label').html('Upload a photo');

      photo.removeClass('no-photo').addClass('yes-photo');
      photo.find('input[name=r_ThumbnailID]').val(data.ID);
      photo.find('.instructions').remove();
//alert(data.html);
      thePhoto.html(data.html);

      //refresh_thumbs();

      up.refresh();
    });
    r_Photo.bind('Error', function(up, err) {
      $(".photo-holder .progress").css('width', 0);
      loading.hide();
      thePhoto.css('opacity', 1);
      $(".hold").removeClass("hold");

      photo.find('.button label').html('<span style="color:red;">Upload failed</span>');
      up.refresh();
    });
  }

  $("#r_Gifts .another-gift").live('click', function() {
    $("#r_Gifts .check-field").fadeIn();
    $(this).hide();
  });

  $(".hold").live('click', function() {
    return false;
  });

  $(".delete-link").live('click', function() {
    return (confirm("Delete this recipient: are you sure?") == true);
  });
  $("#cancel-link").live('click', function() {
    if (!confirm("Cancel your edits: are you sure?"))
      return false;
    $(this).closest('form').removeClass('changed');
    return true;
  });

  $("input:not(.ui), textarea").live('change', function() {
    var tm = 0; // No animation for now
    $("body form.can-edit").addClass('changed').find('#preview-link').hide(tm).end().find('#cancel-link').show(tm);
  });
  $("#story-form").submit(function() {
    $(this).removeClass("changed");

    $('.page').block({ message: 'Saving...', css: { padding: 10, top: 250 }, centerY: false, overlayCSS: {  background: '#666', opacity: 0.2 } });
  });

  if ($.browser.msie) {
      $('#r_Photo.button label').onselectstart = function() { return(false); };
      window.onbeforeunload = function() {
        if ($("form.changed").length == 0) {
          return;
        }
        return "You made some changes to the story.  Do you want to leave this page without saving?";
      }
  } else {
    $(window).bind('beforeunload', function() {
      if ($("form.changed").length == 0) {
        return;
      }
      return "You made some changes to the story.  Do you want to leave this page without saving?";
    });
  }

  function update_dear() {
    var d = $(".r_Dear");
    if (d.find(":checkbox").is(":checked"))
      d.find(".label").removeClass('strike');
    else
      d.find(".label").addClass('strike');
  }
  update_dear();
  $(".r_Dear :checkbox").live('change', update_dear);

});
</script>

</div></body></html>
