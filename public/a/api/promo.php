<?php

require_once(__DIR__.'/api.php');

// need all of this for apply_filter('the_content')
require_once(ABSPATH . 'wp-includes/l10n.php');
require_once(ABSPATH . 'wp-includes/class-wp-walker.php');
require_once(ABSPATH . 'wp-includes/post-template.php');
require_once(ABSPATH . 'wp-includes/post.php');
require_once(ABSPATH . 'wp-includes/link-template.php');
require_once(ABSPATH . 'wp-content/mu-plugins/images.php');

class PromoApi extends Api {

  // backupRef is for backwards compatibility
  public static function load($ref, $backupRef = NULL) {
    // TODO: multiple load
    $promo = static::getOne(array('ref' => $ref));
    if ($promo != NULL)
      return $promo;

    if (!empty($backupRef)) {
      $promo = static::getOne(array('ref' => $backupRef));
      if ($promo != NULL)
        return $promo;
    }

    return (object)array(
      'ref' => $ref,
      'html' => ''
    );
  }

  public static function get($req) {

    $record = req($req, array('blog_id','site', 'ref'));
    $site = static::split_ref($record);

    // Special handling for gift images, for now.
    if ($site == 'gift-images') {
      $html = '';
      $url = image_without_cdn(gift_image_src($record->ref));
      if (!empty($url))
        $html = "<img src=\"{$url}\">";
      return array(array(
        'ref' => "gift-images/{$record->ref}",
        'html' => $html
      ));
    }

    if (isset($record->site)) 
      $record->blog_id = get_site_id($record->site);

    if ($record->blog_id == 0)
      throw new Exception("invalid site");

    $query = new ApiQuery(
      "CONCAT('{$record->blog_id}/', p.post_name) as ref, p.post_content as html, p.id as post_id",
      "wp_{$record->blog_id}_posts p");

    global $wpdb;
    $domain = $wpdb->get_var($wpdb->prepare(
      "SELECT domain FROM charity WHERE blog_id=%s",
      $record->blog_id));
    $query->field("%d as blog_id", $record->blog_id);
    $query->field("%s as partner", $domain);

    if ($record->ref > 0) 
      $query->where("p.id=%d", $record->ref);
    else if (!empty($record->ref))
      $query->where("post_name=%s", $record->ref);

    $query->where("(post_type='promo' OR post_type='page' OR post_type='gift') AND post_status='publish'");

    $results = $query->map_results(array(__CLASS__,'format_row'));

    if (empty($record->ref) || ($record->ref == "thumbnail" && count($result)==0)) {
      $results[] = (object)array(
        'ref' => "{$record->blog_id}/thumbnail",
        'html' => '<img style="display:block;" src="/wp-content/charity-images/charity-' . $domain . '.jpg">',
        'partner' => $domain
      );
    }

    return $results;
  }

  public static function format_row($row) {

    $row->html = apply_filters('the_content', $row->html);
    $row->html = str_replace(']]>', ']]&gt;', $row->html);

    // TODO: remove this when we replace the editor
    $row->editable = get_site_url($row->blog_id, "/wp-admin/post.php?post={$row->post_id}&action=edit");

    return $row;
  }

  // A reference is in the form "site/slug" - split this out and then
  // figure out what site we're referring to in the case that it's just slug
  public static function split_ref(&$record) {
    if (isset($record->blog_id))
      $record->blog_id = get_site_id($record->blog_id);

    // No need to decode the ref?
    if (!isset($record->ref))
      return;

    list($site, $record->ref) = array_map('trim', explode('/', $record->ref));
    if (empty($record->ref)) {
      $record->ref = $site;
      $site = NULL;
    }

    global $blog_id;
    if ($site == 'gift-images')
      $record->blog_id = 1;
    else if (!empty($site))
      $record->blog_id = get_site_id($site);
    else
      $record->blog_id = $blog_id;

    return $site;
  }

  public static function update($req) {
    global $wpdb;

    $record = req($req, array('ref', 'html'));
    $site = static::split_ref($record);

    if ($site == 'gift-images') {
      $html = trim($record->html);

      $matches = array();
      if (empty($html)) {
        $src = versioned_url(SITE_URL . "/wp-content/gift-images/Gift_{$record->ref}.jpg");
      } else if (preg_match('/^\<img .*?src="([^"\>]*)".*?>$/msU', $html, $matches)) {
        $src = image_without_cdn($matches[1]); // Strip any CDN handling
      } else
        throw new Exception("invalid gift-image");

      $wpdb->update('gift', array( 'image' => $src ), array('id' => $record->ref));

/* STEVE: we don't need to do this any more

      if (strpos("/wp-content", $src) !== 0) {
        // Copy and convert file format

        // TODO: more robust handling
        $src = str_replace(SITE_URL,'', $src);
        $src = str_replace("/files/","/wp-content/blogs.dir/1/files/", $src);

        $src = ABSPATH . $src;
        $dest = ABSPATH . "/wp-content/gift-images/Gift_{$record->ref}.jpg";

        exec("convert -auto-orient {$src} {$dest}");
      }
*/
      return;
    }

    static::validate($record);

    $c = $wpdb->get_var($wpdb->prepare(
      "select count(*) from wp_{$record->blog_id}_posts WHERE post_name=%s AND post_status='publish'",
      $record->ref));
     
    if ($c == 0) {
      /* Insert carefully */
      if ($record->blog_id <= 1) {
        if (empty($req->html))
          return;

        throw new Exception("invalid site to create promo $req->ref: $req->html");
      }

      db_new_page(
        $record->blog_id,
        1,
        $record->ref,
        $record->html,
        $record->ref,
        0,
        0,
        'promo'
      );
    } else {
      $wpdb->update("wp_{$record->blog_id}_posts",
        array('post_content' => $record->html),
        array('post_name' => $record->ref, 'post_status' => 'publish' ));
    }
  }

  public static function validate(&$record) {
    if ($record->blog_id < 1)
      throw new Exception("invalid site");

    // ref must be alphanumeric
    if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $record->ref))
      throw new Exception("Invalid ref {$record->ref}");

    if ($record->ref == "thumbnail") {
      // TODO
      throw new Exception("can't set thumbnail yet");
    }
  }

  public static function getColumns($req) {
    return array(
      'ref' => 'id',
      'partner' => 'partner',
      'html' => 'html'
    );
  }
}

register_api(__FILE__, 'PromoApi');
