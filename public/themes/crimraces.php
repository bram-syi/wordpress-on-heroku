<?

global $TEMPLATE;
$TEMPLATE = (object)array(
  'please' => FALSE,
  'post_title' => "Let's cheer for CrimFit's Youth Programs!",
  'theme' => basename(__FILE__, '.php'),
  'goal' => 50,
  'fields' => array('photo', 'body', 'goal'),
  'comments' => "Tell [name] why <b>you love</b> Crim's cause!"
);

include('default.php');
