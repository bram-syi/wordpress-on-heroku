<?

# global $header_file;
# $header_file = 'pratham';

function team_join_message($msg) {
  return "Join in the fundraising!";
}
add_filter('team_join_message', 'team_join_message');

function my_label($msg) {
  return "Get started here";
}
add_filter('team_join_label', 'my_label');

include('default.php');
