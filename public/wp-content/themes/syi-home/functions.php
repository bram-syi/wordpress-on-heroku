<?

register_sidebar(array(
  'name' => 'Social Widgets',
  'id' => 'social-widgets',
  'description' => 'Widgets appear in the social area under the sidebar',
  'before_title' => '<h3>',
  'after_title' => '</h3>'
));

// Add Fundraisers -- defined in syi/functions.php
add_action('init','init_events');

function social_widgets() {
  dynamic_sidebar('social-widgets');
}
add_action('syi_sidebar', 'social_widgets', 5);

?>
