<?

require_once(__DIR__.'/api.php');

require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');

$req = req($_REQUEST, array('data', 'site', 'for'));

if (!empty($_FILES)) { // file upload
  global $blog_id;

  if (empty($req->data))
    $req->data = "file";

  // Make sure upload goes to appropriate site
  if (!empty($req->site))
    $bid = get_site_id($req->site);
  if ($bid > 0 && $bid != $blog_id)
    switch_to_blog($bid);
  else
    $bid = $blog_id;

  $mid = media_handle_upload($req->data,0);
  // TODO: handle error cases

  unset($_FILES);
  $mid_obj = get_post($mid);

  switch ($req->for) {
    case 'charity-thumb':
      // TODO: generate charity thumbnail
      break;
  }

/* To replace a current post thumbnail - not used 

  $old_mid = intval(get_post_meta($pid,'_thumbnail_id',true));
  wp_delete_post($old_mid);
  update_post_meta($pid,'_thumbnail_id',$mid);
*/

  $guid = str_replace("wp-content/blogs.dir/$bid/",'', $mid_obj->guid);

  if(isset($_REQUEST['html4'])) 
    echo htmlentities($guid);
  else
    echo $guid;

  // restore_current_blog(); <-- no need?
  exit;
}
