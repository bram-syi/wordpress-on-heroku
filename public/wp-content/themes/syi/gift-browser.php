<?

function gift_browser_sidebar() {
  global $REGIONS, $CAUSES, $FOCUS;
?>
  <form id="gift-browser-sidebar" class="gift-tags panel sidebar-panel current-panel evs">
<!--    <h2 class="sidebar-title banner">How do you want to help?</h2> -->
    <h3 style="margin-top:-20px;">choose a cause:</h3>
    <ol id="causes">
      <? foreach ($CAUSES as $tag=>$name) { ?>
        <a id="tag-<?=$tag?>" href="?tag=<?= $tag ?>" class="gift-tag cause-gift-tag button tag-button ev">
        <input id="choose-<?= $tag ?>" type="checkbox" name="tags[]" value="<?= $tag ?>" />
        <label for="choose-<?= $tag ?>"><?= $name ?></label></a>
      <? } ?>
      <div class="clearer"></div>
    </ol>
    <h3>change a life:</h3>
    <ol id="people">
      <? foreach ($FOCUS as $tag=>$name) { ?>
        <a id="tag-<?=$tag?>" href="?tag=<?= $tag ?>" class="gift-tag focus-gift-tag button tag-button ev">
        <input id="choose-<?= $tag ?>" type="checkbox" name="tags2[]" value="<?= $tag ?>" />
        <label for="choose-<?= $tag ?>"><?= $name ?></label></a>
      <? } ?>
      <div class="clearer"></div>
    </ol>
    <h3>in this region:</h3>
    <ol id="regions">
      <? foreach ($REGIONS as $tag=>$name) { ?>
        <a id="tag-<?=$tag?>" href="?tag=<?= $tag ?>" class="gift-tag region-gift-tag button tag-button ev">
        <input id="choose-<?= $tag ?>" type="checkbox" name="tags3[]" value="<?= $tag ?>" />
        <label for="choose-<?= $tag ?>"><?= $name ?></label></a>
      <? } ?>
      <div class="clearer"></div>
    </ol>
    <h3 id="cost-label">for about:</h3>
    <div id="cost">
      <div class="labels"><span>$</span><span>$$</span><span>$$$</span></div>
      <input class="hidden range" type="range" name="cost" min="0" max="5" step="1" value="0" />
    </div> 
    <div class="reset"><a href="#">start over</a></div>
  </form>
  <div id="gift-details-sidebar" class="panel sidebar-panel charity-panel">
    <? draw_promo_content("pay-sidebar", false); ?>
    <p><a href="/give/#" class="button green-button medium-button">See all available gifts</a></p>
  </div>
<?
}

function render_gift_browser($post = null) {
  if ($post == null) {
    $post = get_posts('post_type=page&name=give');
    $post = $posts[0];
  }
  setup_postdata($post);

  gift_browser_widget(array(
    'empty_html' => get_the_content(),
    'main_gift_browser' => TRUE
  ));
?>
<script type="text/javascript">
$(window).bind('gift-browse', function() {
  $("#bottom-panels").slideDown();
});
$(window).bind('gift-details', function() {
  $("#bottom-panels").slideUp();
});
</script>
<script type="text/html" id="story_template">
<?
draw_story(array(
  'blog_id' => '${blog_id}',
  'title' => '${post_title}',
  'ref' => '${ref}',
  'url' => '${guid}',
  'excerpt' => isset($story) ? $story->post_excerpt : '',
  'img' => '${post_image}'
));
?>
</script>
<script type="text/html" id="funded_gift">
  <div class="empty-gifts-box">
    <? draw_promo_content('gift-funded', 'h2'); ?>
  </div>
</script>
<?
  global $GIFTS_LOC;
  $GIFTS_LOC='${itemtag}';
  json_template('draw_gift_details',
    array('imageUrl','headline','description','unitAmount','id','excerpt','siteurl','title','excerpt','image'));
  json_template('draw_var_gift_details',
    array('imageUrl','headline','description','unitAmount','id','excerpt','siteurl','title','excerpt','image'));
  json_template('draw_agg_gift_details',
    array('imageUrl','headline','description','unitAmount','id','excerpt','siteurl','title','excerpt','image',
      'towards_gift_id','master_amount','master_current','master_name','full_count','left_count','displayName',
      'current_percent','left_amount','current_amount'));

}

