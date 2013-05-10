<?php
/*
Plugin Name: Allow UTF-8 Filenames on Windows
Plugin URI: http://core.trac.wordpress.org/ticket/15955
Description: A workaround for correct uploading of files with UTF-8 names on Windows systems.
Version: 0.1
Author: Sergey Biryukov (modified by Steve Eisner for SYI)
Author URI: http://profiles.wordpress.org/sergeybiryukov/
*/

function autfw_sanitize_file_name($filename, $utf8 = false) {
  if ( seems_utf8($filename) == $utf8 )
    return $filename;

  if (empty($filename)) 
    return $filename;

  $newf = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename);
  return $newf;
}
add_filter('wp_delete_file', 'autfw_sanitize_file_name');

// could sanitize old attachments but would have to convert existing files.
//add_filter('get_attached_file', 'autfw_sanitize_file_name');

function autfw_handle_upload_input($file) {
  $file['name'] = autfw_sanitize_file_name($file['name']);
  return $file;
}
add_filter('wp_handle_upload_prefilter', 'autfw_handle_upload_input');

function autfw_handle_upload($file) {
  $file['file'] = autfw_sanitize_file_name($file['file'], true);
  $file['url'] = autfw_sanitize_file_name($file['url'], true);
  return $file;
}
add_filter('wp_handle_upload', 'autfw_handle_upload');

function autfw_update_attached_file($file) {
  return autfw_sanitize_file_name($file, true);
}
add_filter('update_attached_file', 'autfw_update_attached_file');

function autfw_generate_attachment_metadata($metadata, $attachment_id) {
  $file = get_attached_file($attachment_id);

  remove_filter('wp_generate_attachment_metadata', 'autfw_generate_attachment_metadata');
  $metadata = wp_generate_attachment_metadata($attachment_id, $file);

  return autfw_update_attachment_metadata($metadata);
}
add_filter('wp_generate_attachment_metadata', 'autfw_generate_attachment_metadata', 10, 2);

function autfw_update_attachment_metadata($metadata) {
  $metadata['file'] = autfw_sanitize_file_name($metadata['file'], true);

  if ( !empty($metadata['sizes']) ) {
    foreach ( (array) $metadata['sizes'] as $size => $resized ) {
      $resized['file'] = autfw_sanitize_file_name($resized['file'], true);
      $metadata['sizes'][$size] = $resized;
    }
  }

  return $metadata;
}
add_filter('wp_update_attachment_metadata', 'autfw_update_attachment_metadata');

function autfw_update_post_metadata($foo, $object_id, $meta_key, $meta_value) {
  if ( '_wp_attachment_backup_sizes' !=  $meta_key )
    return null;

  foreach ( (array) $meta_value as $size => $resized ) {
    $resized['file'] = autfw_sanitize_file_name($resized['file'], true);
    $meta_value[$size] = $resized;
  }

  remove_filter('update_post_metadata', 'autfw_update_post_metadata', 10, 4);
  update_metadata('post', $object_id, $meta_key, $meta_value);

  return true;
}
add_filter('update_post_metadata', 'autfw_update_post_metadata', 10, 4);
?>
