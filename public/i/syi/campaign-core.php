<?

function campaign_init($eid = NULL, $editor = FALSE) {
  global $post, $bp, $event_id;

  if ($post!=null && $post->post_type == 'event')
    $id = $post->ID;
  else {
    // Try to figure out which campaign to display
    // TODO change to CID
    $id = intval($eid);
    if ($id == 0)
      $id = has_active_campaign();

    // Load post data if available
    if ($id > 0) {
      // disable non owner and non admin for draft campaign
      if (get_post_type($id) != 'event')
        $id = 0;
      else if (get_post_status($id) == 'draft' && !can_manage_campaign($id))
        $id = 0;
      else {
        global $post;
        $post = get_post($id);
        setup_postdata($post);
        set_event_cookie($id);
      }
    }
  }

  if ($id == 0 && !$editor)
    return NULL;

  // Helpers
  global $NO_SIDEBAR;
  $NO_SIDEBAR = TRUE;

  if ($bp->current_component != 'updates')
    $bp->current_component = 'campaign';

  $event_id = $id;

  global $norm_url;
  if ($post != null) {
    set_event_cookie($post->ID);
    $norm_url = get_post_permalink($post->ID);
    $uid = get_campaign_owner($post->ID);
    if ($uid > 0) {
      $norm_url = get_member_link($uid, CAMPAIGN_SLUG);
    }
  }

  // Add specialized campaign CSS
  $css = apply_filters('get_css_dir','');
  wp_enqueue_style('campaign', "$css/campaign.css");

  add_action('syi_meta_tags', 'campaign_meta_tags');
  add_action('syi_meta_tags', 'campaign_private');
  add_action('get_crumbs', 'member_crumbs');
  add_filter('body_class','profile_body_class');
  add_filter('body_class','campaign_skin_body_class');
  add_filter('get_update_count', 'campaign_get_update_count');

  load_custom_skin();

  // Used for support hooks
  global $FR;
  if ($id > 0) 
    $FR = get_campaign_stats($id);

  return $FR;
}

function campaign_get_update_count($c) {
  global $FR;
  if ($FR == null)
    return 0;

  return count(as_ints($FR->updates));
}

function campaign_skin_body_class($classes) {
  global $event_theme;

  if (!empty($event_theme))
    $classes[] = "theme-$event_theme";
  return $classes;
}
function campaign_meta_tags() {
  global $post;

  if (has_action('syi_meta_tags', 'update_post_open_graph_meta')) {
    return;
  }

  $props = SyiFacebook::fundraiser_as_array($post->ID);
  echo SyiFacebook::array_as_metatags($props);
}
function campaign_private() {
  if (is_campaign_public())
    return;

  ?><meta name="robots" content="noindex,follow"><?
}

function is_member_campaign() {
  global $bp;

  return $bp->current_component == 'campaign';
}
function is_campaign_public() {
  return has_tag('public');
}


function draw_campaign_photo($campaign, $size = NULL) {
  if ($size == NULL)
    $size = array(CGW_IMG_MAIN_W, CGW_IMG_MAIN_H);

  $img = make_img(fundraiser_image_src($campaign), $size);

  draw_image_uploader($img, "main-image", can_manage_campaign($campaign->id));
}
