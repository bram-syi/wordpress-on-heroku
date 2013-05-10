<?

define('HTTP_IMAGE_HOST', 'http://res.cloudinary.com/seeyourimpact/');
define('HTTPS_IMAGE_HOST', 'https://d3jpl91pxevbkh.cloudfront.net/seeyourimpact/');
require_once(ABSPATH . '/a/api/fundraiser.php');
require_once(ABSPATH . '/wp-includes/meta.php'); // get_user_meta
require_once(ABSPATH . '/wp-includes/user.php'); // get_user_meta

if( !function_exists( 'get_blog_post_thumbnail' ) ) {
  require_once(ABSPATH . 'wp-includes/post.php');

  function get_blog_post_meta($blog_id, $post_id, $meta_key) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare(
      "SELECT meta_value FROM wp_1_postmeta
       WHERE post_id=%d and meta_key=%s",
      $post_id, $meta_key));
  }
  function get_blog_post_thumbnail($blog_id,$post_id,$size='full',$attrs=NULL) {
    global $current_blog;
    global $wpdb;
    $oldblog = $wpdb->set_blog_id( $blog_id );

    $att_id = get_blog_post_meta($blog_id, $post_id, '_thumbnail_id');
    $url = wp_get_attachment_url($att_id);

    $blogdetails = get_blog_details( $blog_id );
    $thumbcode = str_replace( $current_blog->domain . $current_blog->path, $blogdetails->domain . $blogdetails->path, $url );

    $wpdb->set_blog_id( $oldblog );
    return $thumbcode;
  }
}

// check for known URL patterns that should be handled differently
function parse_image_key($url) {
  $matches = array();
  if (preg_match('/graph\.facebook\.com\/(.+?)\/picture/', $url, $matches)) {
    return array('facebook', $matches[1]);
  }

  if(filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
    $url2 = SITE_URL . $url;  // is it a relative path?
    if(filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
      // TODO
    }

    $url = $url2;
  }

  return array('url', $url);
}

function image_without_cdn($url) {
  // Is it already a CDN src?
  if (strpos($url, HTTP_IMAGE_HOST) == 0 || strpos($url, HTTPS_IMAGE_HOST) == 0) {
    // Extract the image key
    if (preg_match('/(http.*seeyourimpact\/)((image|video)\/.*?)\/(.+?)\/(.*)/', $url, $matches)) {
      if ($matches[2] == 'image/fetch')
        return $matches[5];
    }
  }

  return $url;
}

// Takes an image by ID or URL and a type hint
// breaks it into [id, type]
// if type=facebook, ID = {fbid}.jpg
// if type=file, ID=URL of the file
function image_key($id, $type = NULL) {
  if ($id == NULL)
    return NULL;

  if (empty($type)) {
    // Is it already a CDN src?
    if (strpos($id, HTTP_IMAGE_HOST) == 0 || strpos($id, HTTPS_IMAGE_HOST) == 0) {
      // Extract the image key
      if (preg_match('/(http.*seeyourimpact\/)((image|video)\/.*?)\/(.+?)\/(.*)/', $id, $matches)) {
        return array($matches[5], $matches[2]);
      }
    }

    list($type, $id) = parse_image_key($id);
  }

  switch ($type) {
    case 'facebook':
      // ID can be a number or a facebook URL
      if (intval($id) == 0)
        list($type, $id) = parse_image_key($id);
      if (intval($id) <= 0)
        return NULL;
      return array("$id.jpg", 'image/facebook');

    case 'gift':
      global $wpdb;
      $url = $wpdb->get_var($wpdb->prepare("SELECT image FROM gift WHERE id=%d", $id));

// TODO: a real fix
if (empty($url) || strpos("/home/",$url) === 0)
  $url = SITE_URL . "/wp-content/gift-images/Gift_{$id}.jpg";

      return array(versioned_url($url));

    case 'campaign':
    case 'fundraiser':
      if (intval($id) <= 0)
        return NULL;

      $url = get_blog_post_thumbnail(1, $id);
      if (!empty($url))
        return array($url);

      $id = FundraiserApi::getOwner($id);
      // fall through as if it were a user request

    case 'user':
      // ID can be a number or an avatar URL
      if (is_numeric($id)) {
        if ($id > 0) {
          if( function_exists( 'bp_core_fetch_avatar' )) {
            // Use this function when available
            $url = bp_core_fetch_avatar( array(
              'item_id' => $id,
              'type' => 'full',
              'html' => false
            ));
          } else {
            $url = NULL;
            $path = "blogs.dir/1/files/avatars/$row->user_id";
            $dir = ABSPATH . "/wp-content/$path";
            if (file_exists($dir)) {
              if ($av_dir = opendir($dir)) {
                while (false !== ($av_file = readdir($av_dir))) {
                  if (strpos($av_file, '-bpfull') !== FALSE) {
                    $url = SITE_URL . "/wp-content/$av_file";
                    break;
                  }
                }
              }
            }
          }

          // Is this a gravatar?
          if ($url) {
            if (strpos($url, 'www.gravatar.com') !== FALSE) {
              // TODO: we might use these someday?  But really
              // facebook should take precedence
              $url = str_replace('&amp;','&', $url);
            } else if (strpos($url, 'buddypress/bp-core') === FALSE) {
              // If it's not buddypress's missing user image, return it
              return array($url);
            }
          }

          // Does this user have a Facebook photo?
          $fb_id = get_user_meta($id, 'fb_id', true);
          if ($fb_id > 0)
            return array("$fb_id.jpg", 'image/facebook');
        }

        // Sorry, no photo
        return array(SITE_URL . '/wp-content/images/no-photo.jpg');
      }
      // fall through as if it were just an URL

    case 'url':
      // Normalize just in case
      $id = str_replace('/wp-content/blogs.dir/1/', '/', $id);
      return array($id);
 
    default:
      return NULL; // TODO: error?
  }
}

// Helper function to create geometry for image resizing
function image_geometry($w = NULL,$h = NULL, $crop = "fill") {
  // Array: could already be geometry
  if (is_array($w)) {
    if (count($w) == 0)
      return $w;  // Empty is empty

    if ($w[0] > 0) {
      // Probably a size array
      if (!empty($w[1]))
        $h = $w[1];
      $w = $w[0];
    } else {
      // Probably already geometry?
      return $w; 
    }
  } else if (is_string($w)) {
    // Probably a size string "WxH"
    list($w,$h) = explode('x', $w);
  }

  $geometry = array();
  if ($w > 0) 
    $geometry[]= "w_$w";
  if ($h > 0)
    $geometry[]= "h_$h";
  if (!empty($crop))
    $geometry[]= "c_$crop";
  if (count($geometry) > 0)
    $geometry[]= "g_faces";

  return $geometry;
}

// Thumbnails an image
// key = an URL or a value from image_key()
function image_src($key, $geometry = array()) {

  // Make it a key if it's a string URL
  if (is_string($key)) {
    $key = image_key($key);
  }
  
  // If someone passed in a string etc
  if (!is_array($geometry))
    $geometry = image_geometry($geometry);

  // Malformed key?
  if (!is_array($key) || count($key) == 0)
    return NULL; // TODO: error?

  global $is_https_page;
  if ($is_https_page)
    $cdn = HTTPS_IMAGE_HOST;
  else
    $cdn = HTTP_IMAGE_HOST;

  // Apply action, default to fetching the image
  if (isset($key[1]))
    $cdn .= $key[1];
  else 
    $cdn .= "image/fetch";

  // Apply geometry, default to c_fill because otherwise it's hard to extract geometry later
  if (is_array($geometry) && count($geometry) > 0)
    $cdn .= "/" . implode(',', $geometry) . "/";
  else
    $cdn .= "/c_fill/";

  // Tack on the filename or ID
  return $cdn . $key[0];
}

// Can pass in an ID or a fundraiser object
function fundraiser_image_src($id, $w = NULL, $h = NULL) {
  if (!empty($id->photo))
    $key = parse_image_key($id->photo);
  else if (!empty($id->id))
    $key = image_key($id->id, 'fundraiser');
  else
    $key = image_key($id, 'fundraiser');

  return image_src($key, image_geometry($w,$h));
}

// Can pass in an ID or a user object
function user_image_src($id, $w = NULL, $h = NULL) {
  if (isset($id->photo))
    $key = parse_image_key($id->photo);
  else if (isset($id->id))
    $key = image_key($id->id, 'user');
  else
    $key = image_key($id, 'user');

  return image_src($key, image_geometry($w,$h));
}

function gift_image_src($id, $w = NULL, $h = NULL) {
  if (is_array($id) && $id['id'])
    $key = image_key($id['id'], 'gift');
  else if (isset($id->id))
    $key = image_key($id->id, 'gift');
  else
    $key = image_key($id, 'gift');

  return image_src($key, image_geometry($w,$h));
}

function is_no_photo_image($key) {
  if (is_array($key) && count($key) > 0)
    return ($key[0] == SITE_URL . '/wp-content/images/no-photo.jpg');
  return FALSE;
}

// W,H in parameters or a (W,H) array as W
function make_img($src, $w = NULL, $h = NULL, $cl = NULL) {
  $geom = image_geometry($w,$h);
  $src = image_src($src, $geom);

  $html = '<img src="' . $src . '"';
  if (!empty($cl))
    $html .= ' class="' . $cl . '"';
  if (is_array($w)) {
    if (!empty($w[0]))
      $html .= ' width="' . $w[0] . '"';
    if (!empty($w[1]))
      $h = $w[1];
  } else if ($w > 0)
    $html .= ' width="' . $w . '"';
  if ($h > 0)
    $html .= ' height="' . $h . '"';
  return $html . ">";
}















