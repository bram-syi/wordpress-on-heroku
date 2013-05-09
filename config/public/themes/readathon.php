<?

global $header_file;
$header_file = 'pratham';

global $TEMPLATE;
$TEMPLATE = (object)array(
  'type' => "Pratham Readathon",
  'title' => "Pratham Readathon",
  'heading' => 'Every book I read helps educate a child in India.',
  'post_title' => "Pratham Readathon",
  'body_label' => "Tell us about yourself! Why do you want to help kids in India? What inspires you to read? And what books will you be reading during the Readathon?",
  'please' => TRUE,
  'goal' => 500,
  'theme' => basename(__FILE__, '.php'),
  'tag' => 'pratham',
  'fields' => array('body', 'location', 'coordinator', 'num_books', 'goal'),
  'required_fields' => array('body', 'location', 'coordinator', 'num_books'),
  'comments' => "Tell [name] why <b>you love</b> Pratham's cause!",
  'about_promo' => 'readathon-about',
  'thumbnail' => '/members/alpana',
  'can_pledge' => TRUE,
  'start_sidebar' => 'readathon-fundraiser-sidebar',
  'start_date' => '2012-06-01',
  'end_date' => '2012-07-01',
  'public' => TRUE
);

$TEMPLATE->start_banner = <<<EOF
<div style="background: url(http://seeyourimpact.org/themes/pratham/pratham.jpg) no-repeat -25px 0; height: 250px; margin: -16px -36px 20px; position: relative;">
  <div id="words" style=" padding-top: 50px; padding-left: 330px;">
    <h2 style="padding: 0; color: black; font-size: 24pt; font-weight: bold;">Read to Help,</h2>
    <h1 style="padding: 0; color: black; font-size: 28pt; font-weight: bold;">and Help to Read.</h1>
    <p style="padding: 30px 0; font-size:110%;">Join the Pratham Readathon!</p>
  </div>
</div>
EOF;


function readathon_scripts() {
?>
<script type="text/javascript">
$(function() {
  function update_kids() {
    var val = Math.abs(parseInt($(this).val(), 10)) * 14;
    if (val > 0) {
      $("#goal-row .optional").html('');
      $("#goal-row .by").html(" (<b>$" + val + "</b>)");
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

  $("#team").change(function() {
    // TODO: Update the coordinator select
  });

/*
  $("#coordinator").change(function() {
    var t = $(this);
    if (t.val() == 'other')
      $("#other_coordinator").fadeIn(300).attr('disabled', false).focus();
    else
      $("#other_coordinator").attr('disabled', true);
  });
*/

});
</script>
<?
}
add_action('wp_head', 'readathon_scripts', 100);

function after_readathon_appeal($campaign) {
  ?><div style="clear:both;"><?
  draw_pledge_form($campaign);
  ?></div><?

  ?></div></div></section><? // Closes the campaign-content DIV

  ?><section><div style="clear:both; background: white; position: relative; z-index: 10;"><div class="template-about based"><?
} 
add_action('after_campaign_appeal_message', 'after_readathon_appeal');


function get_readathon_stats($campaign) {
  $campaign->please = "Every <b>$14</b> I raise helps a child in India learn to read. Can you help?";
  $campaign->unit = 'book';
  $campaign->books_goal = get_post_meta($campaign->id, 'readathon_books', true);
  $campaign->closed = get_post_meta($campaign->id, CLOSED_PLEDGE_META, 1);
  return $campaign;
}
add_action('get_campaign_stats', 'get_readathon_stats');


function readathon_progress_top($args) {
  global $event_id;
 
  $campaign = $args['campaign'];
  if ($campaign->pledge_count <= 0)
    return;

  if (!current_user_can('edit_post', $event_id))
    return;

  ?>
  <a href="/ajax-pledge.php?list&eid=<?=$event_id?>" class="open-pledge-list block link">review your pledges</a>
  <?
}
add_action('progress_widget_top', 'readathon_progress_top');

function readathon_matching_activity($d) {
  if ($d->donorID == 2259) {
    ?> from their SeeYourImpact matching fund<?
  }
}
add_action('progress_widget_row','readathon_matching_activity');


function readathon_profile_labels($labels) {
  $labels['fundraiser'] = 'readathon';
  return $labels;
}
add_filter('profile_tab_labels', 'readathon_profile_labels');

function readathon_draw_field($p, $field, $labels) {
  global $TEAMS;

  switch ($field) {
    case 'num_books':
      if (empty($p['books']))
        $p['books'] = 7;

      ?>
      <div class="editfield full-width" id="goal-row">
        <label for="goal" class="above full-width">How many books are you going to read?</label>
        <input type="number" value="<?=$p['books']?>" id="books" name="books" size="3" maxlength="3" class="with-tip" style="width: 55px; margin-left:20px;"/>
        <label for="books">books</label>
      </div>
      <?
      break;

    case 'new_goal':
      $g = $p['goal'];
      $kids = ceil($g / 14);
      if ($kids <= 0) {
        $kids = 10;
        $g = $kids * 14;
      }

      ?>
      <div class="editfield full-width" id="goal-row">
        <label for="goal" class="above full-width">How much money are you trying to raise?
          <span class="optional">(You can change this later!)</span>
        </label>
        <input type="hidden" value="<?=$g?>" id="goal" name="goal" />
        <label for="syi_kids" style="margin-left:20px;">Enough to change the lives of </label>
        <input type="number" value="<?=$kids?>" id="syi_kids" name="syi_kids" size="5" maxlength="5" class="with-tip" style="width: 70px;"/>
        <label for="syi_kids"> children<span class="by"></span></label>
      </div>
      <?
      break;

    case 'location':

      $team = $p['team'];
      if (empty($team) || in_array($team, $TEAMS))
        $hidden = "hidden ";
      else {
        $other_team = $team;
        $team = "other";
      }
      ?>
      <div class="editfield full-width" id="location-row">
        <label for="team" class="above full-width">Where is your Pratham chapter?</label>
        <select name="team" id="team" style="margin-left:20px;">
           <? 
           draw_select_option("", $team, "choose a city...");
           foreach($TEAMS as $c) {
             draw_select_option($c, $team);
           }
           draw_select_option("other", $team, "other...");
           ?>
        </select>
        <input type="text" name="other_team" id="other_team" value="<?=esc_attr($other_team)?>" size="25" maxlength="300" class="<?=$hidden?>focused" />
      </div>
      <?
      break;

    case 'coordinator':
      ?>
      <div class="editfield full-width" id="coordinator-row" style="margin-top:-20px;">
        <label for="coordinator" class="above full-width">Do you have a Pratham readathon coordinator? If so, enter his or her name here.<br><span style="font-size:80%; color: #444;">If you don't have one, or don't remember, please skip this field and continue with registration.</span></label>
        <input type="text" id="coordinator" name="coordinator" style="margin-left:20px;" value="<?=esc_attr($p['coordinator'])?>" class="focused">
      </div>
      <?
      break;

    case 'coordinator.better':
      $coordinators = array(
        'Steve Eisner' => 'Seattle',
        'Laura Hoffman' => 'Seattle',
        'John Burry' => 'Chicago'
      );

      $coordinator = $p['coordinator'];
      if (empty($coordinator) || array_key_exists($coordinator, $coordinators))
        $hidden2 = "hidden ";
      else {
        $other_coordinator = $coordinator;
        $coordinator = "other";
      }

      ?>
      <div class="editfield full-width" id="coordinator-row" style="margin-top:-25px;">
        <label for="coordinator" class="above full-width">Who is your Pratham coordinator?</label>
        <select id="coordinator" name="coordinator" style="margin-left:20px;">
          <? draw_select_option("", $coordinator, "choose..."); ?>
          <? foreach ($TEAMS as $c) { ?>
            <optgroup id="<?=$c?>" label="<?=$c?>">
              <? 
              foreach ($coordinators as $coo=>$team) {
                if ($team != $c) 
                  continue;
                draw_select_option($coo, $coordinator);
              }
              ?>
            </optgroup>
          <? } ?>
          <? draw_select_option("other", $coordinator, "other..."); ?>
        </select>
        <select id="not_groups" name="not_groups" style="display:none;"></select>
        <input type="text" name="other_coordinator" id="other_coordinator" value="<?=xml_entities($other_coordinator)?>" size="25" maxlength="300" class="<?=$hidden2?>focused" style="width: 230px;" />
      </div>
      <?
      break;

    case 'book_list':
      ?>
      <div class="editfield full-width" id="book_list-row">
        <div class="left">
          <label for="book_list" class="above full-width">What books will you be reading during the Readathon?</label>
          <textarea name="book_list" id="book_list" value="<?=xml_entities($p['book_list'])?>" size="25" maxlength="300" class="full-width focused"></textarea>
        </div>
      </div>
      <?
      break;
  }
}
add_action('draw_editor_field', 'readathon_draw_field', 0, 3);

function load_readathon_metadata($p) {
  $id = $p['ID'];
  $p['coordinator'] = get_post_meta($id, 'readathon_coordinator', true);
  $p['books'] = get_post_meta($id, 'readathon_books', true);

  return $p;
}
add_filter('load_campaign_metadata', 'load_readathon_metadata');

function update_readathon_metadata($p) {
  $args = stripslashes_deep($_REQUEST);

  $p['coordinator'] = $args['coordinator'];
  if ($p['coordinator'] == 'other')
    $p['coordinator'] = $args['other_coordinator'];
  $p['books'] = absint($args['books']);
  if ($p['books'] == 0)
    $p['books'] = '';
  return $p;
}
add_action('update_campaign_metadata', 'update_readathon_metadata');

function save_readathon_metadata($p) {
  $id = $p['ID'];
  update_post_meta($id, 'readathon_coordinator', $p['coordinator']);
  update_post_meta($id, 'readathon_books', $p['books']);
}
add_filter('save_campaign_metadata', 'save_readathon_metadata');

function readathon_check_errors($errors, $campaign, $post) {
  // TODO - break down into individual fields?

/*
  if (empty($post['coordinator']))
    $errors->add( 'coordinator', __( "choose a Pratham coordinator" ), array( 'form-field' => 'coordinator' ) );
*/

  if (empty($post['team']))
    $errors->add( 'team', __( "choose the nearest Pratham chapter" ), array( 'form-field' => 'team' ) );

  return $errors;
}
add_filter('campaign_editor_form_errors', 'readathon_check_errors',10,3);

function readathon_header() {
  global $blog_id;

  $title = "Summer Readathon 2012";

  ?><div style="padding: 21px 0 0 465px; font-weight: bold;"><?

  ?><a href="http://pratham.seeyourimpact.org/readathon/"><?= $title ?></a><?

  ?></div><?
}
add_action('pratham_header', 'readathon_header');

function readathon_fundraisers_heading($team) {
  return "Support our " . ($team ? $team : '') . " readers";
}
add_filter('fundraisers_heading', 'readathon_fundraisers_heading');

function readathon_member_location($location) {
  return '';
}
add_filter('member_location', 'readathon_member_location');

include('default.php');

function readathon_join_message($msg) {
  return "Ready to join the readathon?";
}
add_filter('team_join_message', 'readathon_join_message');

add_filter('show_donor_last_names', 'readathon_show_donor_last_names');
function readathon_show_donor_last_names($val) {
  return TRUE;
}

