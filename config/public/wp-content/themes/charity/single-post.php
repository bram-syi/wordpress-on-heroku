<?

// emits <meta> tags for Open Graph metadata
function story_meta_tags() {
  global $post;

  $story = SyiFacebook::story_as_array(get_current_blog_id(), $post->ID);
  echo SyiFacebook::array_as_metatags($story);
}
add_action('syi_meta_tags', 'story_meta_tags');

add_action('post_loop_top', 'draw_top_of_single_post');

get_template_part( 'index' );

function draw_top_of_single_post() {
  $name = get_bloginfo('name');

  ?>
  <div class="page-context">
    This impact story was published by <a href="/" class="link"><b><?= xml_entities($name) ?></b></a>.
    Learn more <a class="link" href="/about">about us</a>, or see the <a class="link" href="/stories">lives we've changed</a>!
  </div>
  <?
}
