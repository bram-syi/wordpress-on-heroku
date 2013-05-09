<?

register_sidebar(array(
  'name' => 'Story Widgets',
  'id' => 'story-widgets',
  'description' => 'Widgets appear at the bottom of an impact story page',
  'before_title' => '<h3>',
  'after_title' => '</h3>'
));
register_sidebar(array(
  'name' => 'Social Widgets',
  'id' => 'social-widgets',
  'description' => 'Widgets appear in the social area under the sidebar',
  'before_title' => '<h3>',
  'after_title' => '</h3>'
));

function charity_info_sidebar() {
  crumb_it(get_bloginfo('name'), '/');

  ?><div class="panel sidebar-panel current-panel charity-panel">
    <? draw_promo_content('cause'); ?>
    <p class="centered buttons">
      <a href="/about/" class="button green-button big-button">Learn more &raquo;</a>
      <a href="/stories/" class="button green-button big-button">Our stories &raquo;</a>
    </p>
  </div><?
}

global $GIFTS_LOC,$blog_id;
$GIFTS_LOC="ch/$blog_id";

add_action('get_sidebar', 'charity_info_sidebar');

add_action('syi_pagetop', 'show_charity_header');

function show_certifying_org() {
  global $blog_id; 

  ?><div class="widget promotion-widget"><div class="interior"><?
    draw_promo_content('certified', 'h3');
  ?></div></div><?

  $old_id = $blog_id;
  switch_to_blog(1);
  dynamic_sidebar('social-widgets');
  switch_to_blog($old_id);
}
add_action('syi_sidebar', 'show_certifying_org');

function show_charity_header() {
  global $blog_id;
?>
  <section id="charity-frame" class="<?= is_front_page() ? '' : 'collapser' ?> charity-header">
    <div class="charity-intro">

<? if(is_front_page()){
  draw_promo_content('header');
  gift_browser_widget(array(
    'page_title' => ' ',
    'preload' => true,
    'shrink' => true,
    'blog_id' => $blog_id,
    'exclude' => 'xno_browser', // SteveE: removed no_browser because I think we do want those gifts here
    'include_small_gifts' => true
  ));
}
?>
    </div>
    <div class="frame-shadow"></div>
  </section>
<?
}

?>
