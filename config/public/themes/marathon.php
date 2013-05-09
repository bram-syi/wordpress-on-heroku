<?

global $header_file;
$header_file = 'pratham';

function my_heading($team) {
  return "Support our runners";
}
add_filter('fundraisers_heading', 'my_heading');

function my_join_message($msg) {
  return "Ready to join in the fundraising?";
}
add_filter('team_join_message', 'my_join_message');

include('default.php');
