<?
/* Handle redirection of old pages

set_page_redirect(<from>, <to>);
 -> set a new redirect

set_page_redirect(<from>);
set_page_redirect(<from>, NULL);
 -> erase the redirect

set_page_redirect(<from>, <to>, <owner>);
set_page_redirect(<from>, NULL, <owner>);
 -> set or erase a redirect, within an owner namespace,
 -> if we ever need to allow different parts of the code
 -> to handle the same <from> URL
*/

function set_page_redirect($from_url, $to_url = NULL, $owner = NULL) {
  global $wpdb;

  $a = array( 'from_url' => get_normalized_path($from_url) );
  if (!empty($owner))
    $a['owner'] = $owner;

  $wpdb->delete('redirects', $a);
  // No to_url? Just delete the old one.
  if (empty($to_url))
    return;

  // Add the redirect
  $a['to_url'] = get_normalized_path($to_url);
  $wpdb->insert('redirects', $a);
}


function get_redirect_path($path) {
  $path = apply_filters('bp_uri', $path);

  // Remove any query string
  if ( $noget = substr( $path, 0, strpos( $path, '?' ) ) )
    $path = $noget;

  // Fetch the current URI and explode each part separated by '/' into an array
  $bp_uri = explode( '/', $path );

  // Loop and remove empties
  foreach ( (array)$bp_uri as $key => $uri_chunk )
    if ( empty( $bp_uri[$key] ) ) unset( $bp_uri[$key] );

  // Return the normalized path
  return implode('/', $bp_uri);
}

// This hooks into the BuddyPress hooks and runs after bp_core_set_uri_globals()
// so if any legitimate page is available, it won't redirec
function redirect_old_pages() {
  global $bp;
  if (!empty($bp->current_component))
    return;

  // Look up the redirect
  global $wpdb;
  $path = get_redirect_path(esc_url( $_SERVER['REQUEST_URI'] ));
  $new_path = $wpdb->get_var($wpdb->prepare(
    "SELECT to_url FROM redirects WHERE from_url=%s",
    $path));

  if (empty($new_path))
    return;

  // Perform the redirec
  $qs = $_SERVER['QUERY_STRING'];
  if (!empty($qs))
    $new_path = "$new_path?$qs";
  wp_redirect("/$new_path");
  die;
}
add_action( 'bp_init', 'redirect_old_pages', 10);

