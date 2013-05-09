<?

global $header_file;
$header_file = 'pratham';

function readathon_scripts() {
?>
<script type="text/javascript" src="http://use.typekit.com/nbw4bxb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<script type="text/javascript">
$(function() {
  function update_kids() {
    var val = Math.abs(parseInt($(this).val(), 10)) * 14;
    if (val > 0) {
      $("#goal-row .optional").html('');
      $("#goal-row .by").html(", by raising <b>$" + val + "</b>");
      $("#goal").val(val);
    } else {
      $("#goal-row .by").html("");
      $("#goal-row .optional").html("(choose a number...)");
      $("#goal").val(140);
    }
  }

  $("#syi_kids").each(update_kids);
  $("#syi_kids").on('keyup click blur', update_kids);
  $("#syi_kids, #books").on('change', function(e) {
    var val = Math.abs(parseInt($(this).val(), 10));
    if (val == 0)
      val = '';
    $(this).val(val);
  });

  if ($(".progress-widget .interior").height() > $(".progress-widget").height()) {
    $(".progress-widget .see-all").show();
    var old_top = $(".campaign-sidebar .progress-widget").position().top;
    $(".campaign-sidebar .progress-widget .interior").live('click', function() {
      var pw = $(this).closest('.progress-widget');
      pw.toggleClass('pledge-table-full');
      if (pw.hasClass('pledge-table-full')) {
        pw.find('.see-all').html('close');
        pw.stop().animate({
          top: 60,
          left: -13,
          right: -13,
          paddingRight: 25
        }, 400, 'easeInOutQuad', function() {
          var w = pw.innerWidth();
          pw.css('overflow-y', 'auto');
          w -= pw.innerWidth();
          pw.css('padding-right', 25 - w);
        });
      } else {
        pw.find('.see-all').html('see all');
        pw.css('overflow-y', 'hidden');
        pw.css('padding-right', 25);
        pw.stop().animate({
          left: 0,
          right: 0,
          top: old_top,
          paddingRight: 20
        }, 400);
      }
    });
  }

  $("#city").change(function() {
    var t = $(this);
    if (t.val() == 'other')
      $("#other_city").fadeIn(300).attr('disabled', false).focus();
    else
      $("#other_city").attr('disabled', true);
  });

});
</script>
<?
}

function after_readathon_appeal($campaign) {
  ?><div style="clear:both;"><?
  draw_pledge_form($campaign);
  ?></div><?
} 

function draw_readathon_campaign_sidebar($campaign = NULL) {
  draw_pledge_box(array('campaign' => $campaign, 'button_only' => true, 'show_edit' => !bp_is_my_profile()));

  syi_progress_widget(array(
    'title' => "Thanks to...",
    'see_all' => true,
    'empty_message' => bp_is_my_profile() ? "Find your first supporters by inviting people to this page!" : "Be the first to pledge your support!",
    'limit' => 100,
    'avatars' => FALSE
  ));
}

function draw_readathon_content($campaign) {
  $name = get_firstname($campaign->owner);

  ?>
  <div class="based readathon-about sample-stories">
    <? draw_promo_c2('readathon-about'); ?>
  </div>
  <div class="campaign-comments">
    <h2 class="section-header heading">Tell <?=xml_entities($name)?> why you support this cause!</h2>
    <? syi_fb_comments(array(
      'width' => 800,
      'no_header' => true,
      'compat' => false
    )); ?>
  </div>
  <?
}




function draw_readathon_stats($campaign = NULL) {
  return;

  global $wpdb;

  $closed = get_post_meta($campaign->post_id, CLOSED_PLEDGE_META, 1);
  if ($closed) {
    $stats = calculate_campaign_stats($campaign->post_id);
    $goal = intval(get_post_meta($campaign->post_id, 'goal', true));
    if ($goal == 0) $goal = 1000;
    $perc = intval(100 * $stats->total / $goal);
    if ($perc > 100) $perc = 100;
    if ($perc == 0 && $stat->lives > 0) $perc = 1;
    if ($perc == 0) $mperc = 5;
    else if ($perc < 10) $mperc = 10;
    else $mperc = $perc;

    $pledges = $wpdb->get_row($wpdb->prepare(
      "SELECT sum(due) as due,count(id) as num FROM pledges where event_id=%d",
      $campaign->post_id));

    ?>
    <section class="stats stats2" style="padding: 8px 110px 4px;">
      <? if ($pledges->num > 0) { ?>
        <? if ($pledges->due > 0) { ?>
          <div class="stat2"><b>$<?= number_format($pledges->due) ?></b><label>pledged</label></div>
        <? } else { ?>
          <div class="stat2"><b><?= number_format($pledges->num) ?></b><label>pledge<? if ($pledges->num != 1) echo 's';?></label></div>
        <? } ?>
        <div class="stat2"><b>$<?= number_format($stats->total) ?></b><label>received</label></div>
      <? } else { ?>
        <div class="stat2"><b>$<?= number_format($stats->total) ?></b><label>raised</label></div>
      <? } ?>
      <div class="stat2 donors"><b><?= number_format($stats->donors) ?></b><label>donor<? if ($stats->donors != 1) echo 's';?></label></div>
      <div class="stat2 meter2">
        <div class="meter" title="<?=esc_attr($title)?>"><span style="width: <?= $mperc ?>%">
        <? if ($perc > 15) { echo '<span class="reached">' . $perc . '%</span>'; } ?></span>
        <? if ($perc < 90) { ?><div class="full"><?=$stats->goal ?></div><? } ?></div>
      </div>
      <div class="stat2 goal"><b>$<?= number_format($goal) ?></b><label>goal</label></div>
    </section>
    <?
    return;
  }

  return;

  ?><section id="stats-bar" class="stats2"><?

  ?><span class="stat"><?
  $supporters = $campaign->pledge_count + $campaign->donors_count;
  switch($supporters) {
    case 0: 
      break;
    case 1:
      ?>I have <span class="bigger">1</span> sponsor<?
      break;
    default:
      ?>I have <span class="bigger"><?= $supporters ?></span> supporters<?
      break;
  }
  ?></span><?

  $end = strtotime($campaign->end_date);
  if ($end != NULL) {
    $now = time();
    $diff = $end - $now;
    $days = ceil($diff / 86400);
    if ($days > 0) {
      ?>
      <span class="stat">
         I'm reading for <span class="bigger"><?= $days ?></span> more day<? if($days != 1) echo 's'; ?>
      </span>
      <?
    }
  }

  ?></section><?
}

function readathon_defaults($args) {
  $args['post_title'] = 'Pratham Read-a-thon';
  $args['syi_tag'] = 'pratham';
  if (empty($args['goal']))
    $args['goal'] = 140;
  $args['theme'] = 'readathon';
  // $args['end_date'] = '2012-01-15';
  return $args;
}

function get_readathon_stats($campaign) {
  $kids = ceil($campaign->goal / 14);
  $campaign->please = "My reading is going to provide an education for <b>" . plural($kids, 'child','children') . "</b> in India.  Can you help?";
  $campaign->unit = 'book';
  $campaign->books_goal = get_post_meta($campaign->id, 'readathon_books', true);
  $campaign->closed = get_post_meta($campaign->id, CLOSED_PLEDGE_META, 1);
  return $campaign;
}

function readathon_progress_top($args) {
  global $event_id;
 
  if (!current_user_can('edit_post', $event_id))
    return;

  ?>
  <a href="/ajax-pledge.php?list&eid=<?=$event_id?>" class="open-pledge-list block link">review your pledges</a>
  <?
}

function readathon_editor_top() {
  global $TEMPLATE, $event_id;

  if ($event_id > 0) {
    echo sidebar_widget(array('id' => 'edit-fundraiser-sidebar'));
    draw_page_title('Update your Read-a-thon');
  } else {
    echo sidebar_widget(array('id' => 'start-fundraiser-sidebar'));
    ?>
      <div id="start-banner">
        <div id="words">
          <h2>Read to Help,</h2>
          <h1>and Help to Read.</h1>
          <p>Join the Pratham Read-a-thon!</p>
        </div>
      </div>
    <?
  }
}

function readathon_goal_row($p) {
  $g = $p['goal'];
  $kids = ceil($g / 14);
  if ($kids <= 0) {
    $kids = 10;
    $g = $kids * 14;
  }

?>
  <input type="hidden" value="<?=$g?>" id="goal" name="goal" />
  <label for="syi_kids">Set a goal: to change the lives of </label>
  <input type="number" value="<?=$kids?>" id="syi_kids" name="syi_kids" size="5" maxlength="5" class="with-tip" style="width: 55px;"/>
  <label for="syi_kids"> kids<span class="by"></span>.</label>
  <span class="optional">(You can change this later!)</span>
<?
}

function readathon_profile_labels($labels) {
  $labels['fundraiser'] = 'read-a-thon';
  return $labels;
}

function readathon_labels($labels) {
  $labels['body'] = "Tell us about yourself! Why do you want to help kids in India? What inspires you to read? And what books will you be reading during the Read-a-thon?";
  return $labels;
}

function readathon_matching_activity($d) {
  if ($d->donorID == 2259) {
    ?> from their SeeYourImpact matching fund<?
  }
}
add_action('progress_widget_row','readathon_matching_activity');


function readathon_bottom_mission() {
  ?><div style="padding: 10px; font-size: 130%;" class="campaign-bottom centered handwriting">Pratham: Every Child in School and Learning Well</div><?
}

function draw_readathon_title($campaign = NULL) {
  ?><h1>Every book I read helps educate a child in India.</h1><?
}

function readathon_fields($fields) { 
  if ($fields == NULL)
    return array('body', 'location', 'new_goal'); // Body needed until we have a default

  return array('photo', 'body', 'location', 'new_goal');
}

function readathon_city_option($option, $value, $text = NULL) {
  $selected = ($option === $value) ? ' selected=""' : '';
  if (empty($text))
    $text = $option;
?>
  <option value="<?=$option?>"<?=$selected?>><?= xml_entities($text) ?></option>
<?
}

function readathon_draw_field($p, $field, $labels) {
  switch ($field) {
    case 'new_goal':
      $g = $p['goal'];
      $kids = ceil($g / 14);
      if ($kids <= 0) {
        $kids = 10;
        $g = $kids * 14;
      }

      if (empty($p['books']))
        $p['books'] = 5;

      ?>
      <div class="editfield full-width" id="goal-row">
        <label for="goal" class="above full-width">What are your goals for this Read-a-thon?
          <span class="optional">(You can change these later!)</span>
        </label>
        <ul>
          <li>
            <label for="books">I'll read </label>
            <input type="number" value="<?=$p['books']?>" id="books" name="books" size="3" maxlength="3" class="with-tip" style="width: 55px;"/>
            <label for="books">books, and </label>
          </li><li>
            <input type="hidden" value="<?=$g?>" id="goal" name="goal" />
            <label for="syi_kids">I'll change the lives of </label>
            <input type="number" value="<?=$kids?>" id="syi_kids" name="syi_kids" size="5" maxlength="5" class="with-tip" style="width: 55px;"/>
            <label for="syi_kids"> children<span class="by"></span>.</label>
          </li>
        </ul>
      </div>
      <?
      break;

    case 'location':
      $cities = array('Charlotte','Chicago','Dallas','Denver','Houston','Los Angeles','Phoenix','Raleigh-RTP','Seattle','SF Bay Area');
      $city = $p['city'];
      if (empty($city) || in_array($city, $cities))
        $hidden = "hidden ";
      else {
        $other_city = $city;
        $city = "other";
      }
      ?>
      <div class="editfield full-width" id="location-row">
        <label for="city" class="above full-width">Which Pratham chapter do you live near?</label>
        <div style="margin-bottom: 10px;">
        <select name="city" id="city" style="margin-left: 20px;">
           <? 
           readathon_city_option("", $city, "choose a city...");
           foreach($cities as $c) {
             readathon_city_option($c, $city);
           }
           readathon_city_option("other", $city, "other...");
           ?>
        </select>
        <input type="text" name="other_city" id="other_city" value="<?=xml_entities($other_city)?>" size="25" maxlength="300" class="<?=$hidden?>focused" />
        </div>
        <div class="left" style="margin-right: 10px; margin-left: 20px;">
          <label for="school" class="above small">What school do you go to? <span class="optional">(optional)</span></label>
          <input type="text" name="school" id="school" value="<?=xml_entities($p['school'])?>" size="25" maxlength="300" class="focused" />
        </div><div class="left">
          <label for="classroom" class="above small">Who's your teacher?</label>
          <input type="text" name="classroom" id="classroom" value="<?=xml_entities($p['classroom'])?>" size="25" maxlength="300" class="focused" />
        </div>
      </div>
      <?
      break;
  }
}

function load_readathon_metadata($p) {
  $id = $p['ID'];
  $p['city'] = get_post_meta($id, 'readathon_city', true);
  $p['school'] = get_post_meta($id, 'readathon_school', true);
  $p['classroom'] = get_post_meta($id, 'readathon_classroom', true);
  $p['books'] = get_post_meta($id, 'readathon_books', true);

  return $p;
}
function update_readathon_metadata($p) {
  $args = stripslashes_deep($_REQUEST);

  $p['city'] = $args['city'];
  if ($p['city'] == 'other')
    $p['city'] = $args['other_city'];
  $p['school'] = $args['school'];
  $p['classroom'] = $args['classroom'];
  $p['books'] = absint($args['books']);
  if ($p['books'] == 0)
    $p['books'] = '';
  return $p;
}
function save_readathon_metadata($p) {
  $id = $p['ID'];
  update_post_meta($id, 'readathon_city', $p['city']);
  update_post_meta($id, 'readathon_school', $p['school']);
  update_post_meta($id, 'readathon_classroom', $p['classroom']);
  update_post_meta($id, 'readathon_books', $p['books']);
}

function can_end_fundraiser($args) {
  return FALSE; // Can't end fundraisers right now
}
add_filter('can_end_fundraiser', 'can_end_fundraiser');

add_action('wp_head', 'readathon_scripts', 100);
remove_action('draw_fundraiser_ad','draw_fundraiser_ad');

add_action('progress_widget_top', 'readathon_progress_top');

add_action('get_campaign_stats', 'get_readathon_stats');
add_filter('load_campaign_metadata', 'load_readathon_metadata');
add_filter('save_campaign_metadata', 'save_readathon_metadata');
add_action('update_campaign_metadata', 'update_readathon_metadata');

remove_action('after_campaign_appeal_message', 'draw_campaign_content');
add_action('after_campaign_appeal_message', 'after_readathon_appeal');

remove_action('draw_campaign_content', 'draw_campaign_content');
add_action('draw_campaign_content', 'draw_readathon_content');
add_action('draw_campaign_sidebar', 'draw_readathon_campaign_sidebar');
remove_action('draw_campaign_stats', 'draw_campaign_stats');
remove_action('draw_campaign_title', 'draw_campaign_title');
add_action('draw_campaign_title', 'draw_readathon_title');
add_action('draw_campaign_stats', 'draw_readathon_stats');
add_action('campaign_bottom', 'readathon_bottom_mission', 100);

add_filter('profile_tab_labels', 'readathon_profile_labels');

// Alter default campaign editor behaviors
replace_action('campaign_editor_top', 'readathon_editor_top');
add_filter('campaign_editor_labels', 'readathon_labels');
add_filter('campaign_editor_fields', 'readathon_fields');
add_action('draw_editor_field', 'readathon_draw_field', 0, 3);
add_filter('campaign_editor_defaults', 'readathon_defaults');

// Insert the handler for selecting goal via number of kids
add_filter('draw_goal_row', 'readathon_goal_row');
remove_filter('draw_goal_row', 'draw_goal_row');
