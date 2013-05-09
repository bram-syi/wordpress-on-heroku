<?

function draw_panel($slug, $args = NULL) {
  global $wpdb;
  global $post;

  if (is_numeric($args)) 
    $args = (object)array('blog_id' => $args);
  else if (is_array($args))
    $args = (object)$args;

  if ($args->blog_id > 0) {
    $id = $wpdb->set_blog_id($args->blog_id); // hopeww
    draw_promo_content($slug);
    $wpdb->set_blog_id($id);
    return;

    // code above should be replaced eventually with this path
    $old_blog_id = $wpdb->set_blog_id($blog_id);
  }

  // Make this faster
  $posts = get_posts("post_type=promo&name=$slug");
  if (count($posts) > 0) {
    $old_post = $post;
    $post = $posts[0];
    $pid = $post->ID;
    setup_postdata($post);
    $content = get_the_content();
    $post = $old_post;
    setup_postdata($post);

    echo "<div class=\"promo promo-$slug promo-$pid panel-$slug\">";
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    echo $content;

    if (current_user_can('level_10')) {
      $ret .= '<a class="editable" href="'.get_edit_post_link($pid).'">Edit</a>';
    }
    ?></div><?
  }

  if ($old_blog_id > 0) 
    $wpdb->set_blog_id($old_blog_id);
}
function panel_shortcode($args) {
  ob_start();
  draw_panel($args['id'], $args);
  $str = ob_get_contents();
  ob_end_clean();
  return $str;
}
add_shortcode('panel','panel_shortcode');

// [sidebar]
function sidebar_shortcode($args, $content) {
  return shortcode_widget('sidebar_widget', $args, $content);
}
add_shortcode('sidebar','sidebar_shortcode');
function sidebar_widget($args, $content = NULL) {
  $style = "";
  $class = "";

  extract($args);

  if (!empty($style))
    $style = "style=\"$style\"";

  if (!empty($class))
    $class = "class=\"$class\"";

  ?>
  <div id="page-sidebar" class="right page-sidebar span4 <?=$class?>" <?=$style?>>
    <div class="promo-widget">
      <? draw_promo_c2($id); ?>
    </div>
  </div>
  <?
}








////////////////////////////////////////////////////////////////////////////////
// PROMOS: Custom post type
////////////////////////////////////////////////////////////////////////////////

add_action('init','init_promos');
function init_promos() {
  // Create a "promotion" type for small widget-like content
  register_post_type( 'promo' , array(
    'labels' => array(
      'name' => _x('Promotions','general name'),
      'singular_name' => _x('Promotion','singular name'),
      'add_new' => _x('Add New', 'promo'),
      'add_new_item' => __('Add New Promotion'),
      'edit_item' => __('Edit Promotion'),
      'new_item' => __('New Promotion'),
      'view_item' => __('View Promotion'),
      'search_items' => __('Search Promotions'),
      'not_found' => __('No Promotions Found'),
      'not_found_in_trash' => __('No Promotions Found in Trash'),
      'parent_item_colon' => ''
    ),
    'public' => false,
    'menu_position' => 5,
    'publicly_queryable' => true,
    'exclude_from_search' => true,
    'show_ui' => true,
    'capability_type' => 'page',
    'rewrite' => false,
    'hierarchical' => true,
    'supports' => array('title', 'editor', 'revisions', 'page-attributes')
  ));
}

add_action('init', 'article_add_default_boxes');
function article_add_default_boxes() {
  register_taxonomy_for_object_type('post_tag', 'article');
}








function promo_shortcode($args, $content) {
  ob_start();
  draw_promo_c2($args['id']);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
add_shortcode('promo','promo_shortcode');




function draw_sidebar_promo($slug, $banner = false) {
  echo '<div class="sidebar-panel">';
  draw_promo_content($slug, 'h2', $banner);
  echo '</div>';
}

// title is null, 'h2', or 'h3'
function draw_promo_content ($slug, $titleTag=false, $banner=false, $return=false, $raw=false) {
  global $wpdb;
  global $post;
  $ret = '';
  $old_post = $post;

  $posts = get_posts("post_type=promo&name=$slug");
  if (!is_array($posts) || count($posts) == 0)
    return;

  $post = $posts[0];
  setup_postdata($post);

  if (!empty($titleTag) && !$raw) {
    $title = trim(get_the_title());
    if (!empty($title) && substr($title, 0,1)!='(' ) {
    $ret .= '<'.$titleTag.' class="sidebar-title'.($banner ? " banner" : "" ).'">'.$title.'</'.$titleTag.'>';
    }
  }

  if (!$raw) {
    $ret .= do_shortcode(xml_entities(get_the_content()));
  } else {
    $ret .= get_the_content();
  }

  if (current_user_can('level_10') && !$raw) {
    $ret .= '<a class="editable" href="'.get_edit_post_link($post->ID).'">Edit</a>';
  }

  $post = $old_post;
  if ($post != null)
    setup_postdata($post);

  if(!$return) echo $ret; else return $ret;

}

function is_true($val) {
  return $val === TRUE || $val === "true";
}

function section_value($page, $part, $key, $default) {
  if (!$page || !isset($page->$part) || !isset($page->$part->$key))
    return $default;
  return $page->$part->$key;
}

function is_showing($page, $part, $legacy = FALSE) {
// TODO: once we're sure legacy == current, we can drop this
  $legacy = ($legacy == TRUE);

  if ($page === NULL)
    return $legacy;
  if (!isset($page->show))
    return $legacy;

  // If page->show is set, ignore legacy
  if ($page->show->$part === TRUE || $page->show->$part == "true")
    return TRUE;
  return FALSE;
}

function draw_if_showing($page, $part, $gallery = NULL, $gallery_part = NULL) {
  if (!is_showing($page, $part))
    return;


  if (!$gallery)
    $gallery = $page->$part;

  $promo = $gallery[$gallery_part];

  draw_gallery_part($promo, $gallery_part);
}

function draw_gallery_part($promo, $cl = NULL) {
  if ($promo == NULL)
    return;

  list($bid, $slug) = explode('/', $promo->ref);

  if (empty($cl))
    $cl = "";

  echo "<div class=\"promo promo-$slug $cl\">";
  $content = apply_filters('the_content', $promo->html);
  $content = str_replace(']]>', ']]&gt;', $content);
  echo $content;

  if (is_super_admin() && $promo->editable)
    echo '<a class="editable" href="' . $promo->editable .'">Edit</a>';

  echo "</div>";
}

function draw_promo_c2($slug, $warn_if_missing = FALSE) {
  $promo = get_page_by_path($slug, OBJECT, 'promo');
  if ($promo == NULL || $promo->ID == NULL || $promo->post_status != 'publish') {
    if ($warn_if_missing && is_super_admin()) {
      echo "<div class=\"promo promo-$slug promo-missing\">";
      echo "Missing: $slug";
      echo "</div>";
    }
    return FALSE;
  }

  echo "<div class=\"promo promo-$slug\">";
  $content = apply_filters('the_content', $promo->post_content);
  $content = str_replace(']]>', ']]&gt;', $content);
  echo $content;

  if (current_user_can('level_10')) {
    echo '<a class="editable" href="'.get_edit_post_link($promo->ID).'">Edit</a>';
  }
  echo "</div>";
}

function try_draw_promo($slug, $body, $title) {
  if (!empty($slug)) {
    if (draw_promo_c2($slug) !== FALSE) {
      return TRUE;
    }

    if (!empty($body)) {
      db_new_page(1, 1, $title, $body, $slug, 0, 0, 'promo', '', true);
      draw_promo_c2($slug);
      return TRUE;
    }
  } else if (!empty($body)) {
    echo '<div class="promo">' . do_shortcode($body) . '</div>';

    return TRUE;
  }

  return FALSE;
}

function render_client_template($filename, $data) {
  global $client_templates;
  $client_templates[$filename] = TRUE;

  $random = substr(number_format(time() * rand(),0,'',''),0,10);
  $id = "replace_$random";
  ?><script id="<?=$id?>">$('#<?=$id?>').template('<?=$filename?>_template', <?= json_encode($data) ?>);</script><?
}

add_action('draw_client_templates', 'draw_client_templates');
function draw_client_templates() {
  global $client_templates;
  if ($client_templates == NULL)
    return;

  foreach ($client_templates as $filename=>$b) {
    ?><script type="text/html" id="<?=$filename?>_template">
<? locate_template("templates/$filename.html", TRUE); ?>
</script><?
  }
}



