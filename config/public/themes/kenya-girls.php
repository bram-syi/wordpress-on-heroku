<?

if (is_front_page()) {
  add_action('syi_pagetop', 'draw_the_crumbs', 0);
}

include('default.php');
