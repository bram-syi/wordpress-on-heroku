<?

function hipad_scripts() {
?>
<script type="text/javascript" src="http://use.typekit.com/nbw4bxb.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?
}

function draw_hipad_content($campaign = NULL) {
  global $event_id;

  ?>
  <section class="campaign-content based">
    <section style="float:right; width: 340px;">
      <? if ($campaign->donors_count > 0) { ?>
      <div class="board" style="background: #ffc; border-color: #dd8;">
        <h3>My progress</h3>
        <div class="stat"><?= plural($campaign->donors_count, "donor") ?> - $<?= round($campaign->raised) ?></div>
      </div>
      <? } ?>
      <div class="board">
        <h3>The leaderboard</h3>
        <?
        echo leaderboard_shortcode(array(
          'mode' => 'team',
          'theme' => 'jsmt',
          'order' => 'donors',
          'limit' => 5,
          'donors' => true
        ));
        ?>
      </div>
    </section>
    <div class="left gallery-left" style="width: 680px; margin-right: -50px;">
      <div class="sample-stories" style="padding: 0 45px 0 30px; width: 550px;">
        <h2 class="section-header" style="margin: 0 -15px;">A competition where everyone wins!</h2>

        <p>I'm participating in a contest to see who can find the most donors for the JSM girls' school in India. The competition ends on March 1, 2012 and <b>100%</b> of all the money we raise is sent to India.</p>
        <p> Here are three things you can to do to help out RIGHT NOW: give $10 or more, spread the word to your friends, and then leave a comment of support at the bottom of this page!</p>
      </div>
      <? show_sample_stories($event_id, 4, "You'll see exactly who received your gift!"); ?>
    </div>
  </section>
  <?
  syi_social_section();
}

remove_action('draw_campaign_content', 'draw_campaign_content');
add_action('draw_campaign_content', 'draw_hipad_content');

function draw_hipad_stats($campaign = NULL) {
  return;
  ?><div id="stats-bar"><?
  // Need better stats here?
  syi_stat_section();
  ?></div><?
}

remove_action('draw_campaign_stats', 'draw_campaign_stats');
add_action('draw_campaign_stats', 'draw_hipad_stats');


function before_appeal() {
  ?>
  <div id="right-sidebar">
    <p>
      Some test about some stuff.
      Some test about some stuff.
    </p>
  </div>
  <?
}
function after_appeal() {
  ?>
  <div id="give">
    <?= donate_shortcode(array()); ?>
  </div>
  <?
}

add_action('wp_head', 'hipad_scripts');

//add_action('before_campaign_appeal_message', 'before_appeal');
add_action('after_campaign_appeal_message', 'after_appeal');




// =============================
// EDITOR

function hipad_defaults($args) {
  if (empty($args['post_title']))
    $args['post_title'] = 'Help me change lives in India for as little as $10!';
  if (empty($args['post_content']))
    $args['post_content'] = <<<EOF
80% of women in rural Uttar Pradesh are illiterate. Since 2005, JSM girl's school has been fighting poverty through education. They offer quality middle and high school education to rural girls, free of charge.

A donation of just $10 can change a girl's life by providing the funding for a whole month of school. Please join me in support of this incredible organization by contributing $10 or more!
EOF;

  $args['syi_tag'] = 'jsmt-girls';
  if (empty($args['goal']))
    $args['goal'] = 100;
  $args['theme'] = 'jsmt';
  return $args;
}
add_filter('campaign_editor_defaults', 'hipad_defaults');

function hipad_goal_row($post) {
}
add_filter('draw_goal_row', 'hipad_goal_row');

function hipad_draw_editor_field($post, $field, $labels) {
  switch ($field) {
    case 'body':
      $labels['body'] = "<span style=\"font-size:120%;\">Create an <b>inspirational appeal</b>.  Use this text, or edit it to make your own!</span><br><br>";
      draw_editor_field($post, $field, $labels);
      return;

    case 'photo':
      ?>
      <img class="left" style="padding:4px; background: white; border: 1px solid #888; box-shadow: 0 0 10px #888;" src="http://seeyourimpact.org/thumbs/300x400/50/2011/12/Nisha1.jpg" alt="" width="260">
     <?
     return;
  }
}

remove_action('draw_editor_field', 'draw_editor_field', 0, 3);
add_action('draw_editor_field', 'hipad_draw_editor_field', 0,3);




