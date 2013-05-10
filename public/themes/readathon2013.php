<?

require_once(ABSPATH . '/a/api/fundraiser.php');

function prep_field($a, $i = NULL) {
  if ($i !== NULL) {
    if (!isset($a, $i))
      return "";
    $i = $a[$i];
  }
  return esc_attr(trim($i));
}

function readathon_draw_field($p, $field, $labels) {
  $custom = eor((array)$p['custom'], array());

  switch ($field) {
    case 'location':

      $teams = TeamApi::get(array( 'campaign' => $p['theme'] ));
      $team = trim($p['team']);

      global $TEMPLATE;
      $other_type = $TEMPLATE->required_fields->allow_writein_teams ? "text" : "hidden";

      $other_class = empty($team) ? "hidden" : "focused";
      ?>
      <div class="editfield full-width" id="location-row">
        <label for="team" class="above full-width">Where is your Pratham chapter?</label>
        <select name="team" id="team" style="margin-left:20px;">
           <? 
           draw_select_option("", $team, "choose a city...");
           foreach($teams as $c) {
             draw_select_option($c->team_title, $team);
             if ($c->team_title == $team) {
               $other_class = "hidden";
             }
           }

           if ($other_class == "focused") {
             $other_team = $team;
             $team = "other";
           }

           if ($other_type != "hidden")
             draw_select_option("other", $team, "other...");
           ?>
        </select>

        <input type="<?= $other_type ?>" name="other_team" id="other_team" value="<?=esc_attr($other_team)?>" size="25" maxlength="300" class="<?=$other_class?>" />
      </div>
      <?
      break;

    case 'coordinator':
      ?>
      <div class="editfield full-width" id="coordinator-row" style="margin-top:-10px;">
        <label for="custom[coordinator]" class="above full-width">What is your Pratham Readathon Coordinator's name?</label>

        <div class="labeled" style="width: 260px; margin-left: 20px;">
          <label for="custom[birthday]">Coordinator's name</label>
          <input type="text" id="custom[coordinator]" name="custom[coordinator]" value="<?=prep_field($custom, 'coordinator')?>" class="focused">
        </div>
        <div style="float: left; font-size:80%; color: #666; width: 300px; margin-right: -40px;">If you don't have one, or don't remember,<br>it's okay to skip this and continue.</div>

      </div>
      <?
      break;

    case 'personal':
      $gender = prep_field($custom, 'gender');
      ?>
      <div class="editfield full-width" id="personal-row" style="margin-top:-20px;">
        <label class="above full-width" for="post_title">Tell us a little bit about yourself!</label>

        <div class="labeled" style="width:140px; margin-left: 20px;">
          <label for="custom[gender]">gender</label>
          <select name="custom[gender]" id="custom[gender]">
            <? draw_select_option("M", $gender, "I'm a boy"); ?>
            <? draw_select_option("F", $gender, "I'm a girl"); ?>
          </select>
        </div>

        <div class="labeled" style="width: 80px;">
          <label for="custom[age]" class="stay">age</label>
          <input type="text" name="custom[age]" id="custom[age]" value="<?= prep_field($custom, 'age') ?>" size="70" maxlength="100" class="focused"  style="padding-left: 40px;" />
        </div>

        <div class="labeled" style="width: 200px;">
          <label for="custom[birthday]" class="stay">birthday</label>
          <input type="text" name="custom[birthday]" id="custom[birthday]" value="<?= prep_field($custom, 'birthday') ?>" size="70" maxlength="100" class="focused" style="padding-left: 70px;" />
        </div>

        <div class="labeled" style="width:420px; margin-left: 20px; clear: both;">
          <label for="custom[school]" class="stay">school</label>
          <input type="text" name="custom[school]" id="custom[school]" value="<?= prep_field($custom, 'school') ?>" size="70" maxlength="100" class="focused" style="padding-left: 60px;" />
        </div>

        <div class="labeled" style="width: 100px;">
          <label for="custom[grade]" class="stay">grade</label>
          <input type="text" name="custom[grade]" id="custom[grade]" value="<?= prep_field($custom, 'grade') ?>" size="70" maxlength="100" class="focused"  style="padding-left: 55px;" />
        </div>

      </div>
      <?
      break;

    case 'parents':
      ?>
      <div class="editfield full-width" id="parents-row" style="margin-top:-20px;">
        <label class="above full-width" for="post_title">How can we contact your parents?</label>

        <div class="labeled" style="width: 534px; margin-left: 20px;">
          <label for="custom[parents]" class="stay">name(s)</label>
          <input type="text" name="custom[parents]" id="custom[parents]" value="<?= prep_field($custom, 'parents') ?>" size="70" maxlength="100" class="focused" style="padding-left: 70px;" />
        </div>

        <div class="labeled" style="width:220px; margin-left: 20px; clear: both;">
          <label for="custom[parent_phone]" class="stay">phone</label>
          <input type="text" name="custom[parent_phone]" id="custom[parent_phone]" value="<?= prep_field($custom, 'parent_phone') ?>" size="70" maxlength="100" class="focused" style="padding-left: 60px;" />
        </div>

        <div class="labeled" style="width:300px; ">
          <label for="custom[parent_email]" class="stay">e-mail</label>
          <input type="text" name="custom[parent_email]" id="custom[parent_email]" value="<?= prep_field($custom, 'parent_email') ?>" size="70" maxlength="100" class="focused" style="padding-left: 60px;" />
        </div>

        <div style="clear:both; margin-bottom: 20px;"></div>
      </div>
      <?
      break;

    case 'warning':
      global $TEMPLATE;

      ?>
      <div class="editfield full-width" id="warning-row" style="margin-top:-20px;">
        <label class="above full-width">NOTE: We have authority to remove inappropriate content or inactive pages.<br>
        For questions or re-activation, please contact <b style="color:#333;"><?= get_contact_link() ?></b>.</label>
      </div>
      <?
      break;
  }
}
add_action('draw_editor_field', 'readathon_draw_field', 0, 3);
include('default.php');

function readathon_fields($f) {
  return  array('location', 'coordinator', 'personal', 'parents', 'body', 'warning');
}
add_filter('campaign_editor_fields', 'readathon_fields');

function load_readathon_metadata($p) {
  $id = $p['ID'];
  $p['custom[coordinator]'] = get_post_meta($id, 'readathon_coordinator', true);
  $p['custom[books]'] = get_post_meta($id, 'readathon_books', true);

  return $p;
}
add_filter('load_campaign_metadata', 'load_readathon_metadata');

function update_readathon_metadata($p) {
  $args = stripslashes_deep($_REQUEST);

  $p['custom[coordinator]'] = $args['custom[coordinator]'];
  $p['custom[books]'] = absint($args['custom[books]']);
  if (empty($p['custom[books]']))
    $p['custom[books]'] = '';
  return $p;
}
add_action('update_campaign_metadata', 'update_readathon_metadata');


function save_readathon_metadata($p) {
  $id = $p['ID'];
  update_post_meta($id, 'readathon_coordinator', $p['custom[coordinator]']);
  update_post_meta($id, 'readathon_books', $p['custom[books]']);

  FundraiserApi::update(array(
    'id' => $id,
    'owner' => $p['owner'],
    'custom' => $p['custom']
  ));
}
add_filter('save_campaign_metadata', 'save_readathon_metadata');


function readathon_check_errors($errors, $campaign, $post) {
  $custom = eor((array)$post['custom'], array());

  if (empty($post['team']))
    $errors->add( 'team', __( "choose the nearest Pratham chapter" ), array( 'form-field' => 'team' ) );

  if (empty($custom['age']))
    $errors->add( 'custom[age]', __( "tell us how old you are" ), array( 'form-field' => 'custom[age]' ) );

  if (empty($custom['parent_phone']) && empty($custom['parent_email']))
    $errors->add( 'custom[parent_phone]', __( "provide a phone number or e-mail for a parent" ), array( 'form-field' => 'custom[parent_phone]' ) );
  
  return $errors;
}
add_filter('campaign_editor_form_errors', 'readathon_check_errors',10,3);

function readathon_team_context($result) {
  $result->other_team = "Independent";
  return $result;
}
add_filter('modify_team_context', 'readathon_team_context');

