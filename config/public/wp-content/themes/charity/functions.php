<?

global $NO_SIDEBAR;
$NO_SIDEBAR = true;

global $GIFTS_LOC,$blog_id;
$GIFTS_LOC="ch/$blog_id";

add_action('get_crumbs', 'charity_crumbs');
add_filter('body_class', 'profile_body_class');

add_action('facebook_meta', 'charity_facebook_meta');
add_action('syi_before_post', 'show_post_info');
add_action('syi_after_page', 'show_post_info');

add_action('init', 'charity_init');
function charity_init() {
  $css = apply_filters('get_css_dir', '');
  wp_enqueue_style('campaign', "$css/campaign.css");
  wp_enqueue_script('twitter', "http://platform.twitter.com/widgets.js");
}

global $norm_url;
$norm_url = home_url() . '/';

function charity_facebook_meta() {
  $name = get_bloginfo('name'); 
  $desc = get_bloginfo('description');
  if (stristr($desc, "weblog") !== FALSE)
    $desc = "";
  else
    $desc = "$name: $desc";

?>
  <meta name="description" content="<?= esc_attr($desc) ?>" />
  <meta property="og:description" content="<?= esc_attr($desc) ?>" />
  <meta property="og:title" content="<?= esc_html($name) ?> on SeeYourImpact.org"/>
<?
}

function charity_title($title, $sep = ' - ') {
  global $post;
  return "$sep$post->post_title";
}

function show_post_info($post) {
  global $blog_id;

  ?><div class="post-info"><?

  if ($post->post_type == 'post') {
    ?><h3>This donation:</h3><?
 
    syi_progress_widget(array(
      'blog_id' => $blog_id,
      'story_id' => $post->ID,
      'limit' => 50,
      'empty_message' => ' ',
      'show_avatars' => TRUE
      ));
  }

  if (empty($theme_name))
    $theme_name = '';

  if (current_user_can_for_blog($blog_id, 'publish_posts')) {
  ?>
    <div class="entry-utility" style="font-size:80%;">
      <?php if ( count( get_the_category() ) > 0 ) : ?>
        <span class="cat-links">
          <?php printf( __( '<span class="%1$s">Posted in</span> %2$s', $theme_name ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
        </span>
      <?php endif; ?>
      <?php
        $tags_list = get_the_tag_list( '', ', ' );
        if ( $tags_list ):
      ?>
        <span class="meta-sep">|</span>
        <span class="tag-links">
          <?php printf( __( '<span class="%1$s">Tagged</span> %2$s', $theme_name ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
        </span>
      <?php endif; ?>
      <?php edit_post_link( __( 'Edit', $theme_name ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>

      <? if ($post->post_type == 'post') {
        $feat = intval(get_story_featured($blog_id, $post->ID));
        $cls = "set-featured";
        if ($feat)
          $cls .= " is-featured";
        ?><div class="<?=$cls?>" id="<?= $blog_id ?>/<?= $post->ID ?>">featured</div><?
      }
    ?></div><?

  }
  ?></div><?
}

