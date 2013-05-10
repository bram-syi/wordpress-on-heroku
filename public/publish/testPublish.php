<?
global $errors;

@ini_set('log_errors','On');
@ini_set('display_errors','Off');
@ini_set('error_log','/home/digvijay/php_error.log');
error_log('hello');

try {
  require('storyTools.php');

  if (!$_POST)
    throw new Exception("Must POST to API.");

  error_log(1);

  ob_start();
  $story = initialize_story();
  modify_story($story, $_REQUEST);
  echo "story loaded\r\n";
  $new_story_id = save_story($story);
  error_log(2);
  echo "story id: $new_story_id\r\n";
  $debug = "" . ob_get_contents();
  ob_end_clean();
  error_log(3);

  if ($new_story_id == null)
    throw new Exception("Upload failed.");

  error_log(4);
  $resp = json_encode(array(
    'id' => $new_story_id,
    'debug' => $debug
  ));
}
catch (Exception $e) {
  $resp = json_encode(array(
    "error" => $e->getMessage(),
    "debug" => $debug));
}
echo $resp;
error_log($resp);
debug($resp, 'steveeisner@gmail.com', 'Phone story published');
die();
?>
