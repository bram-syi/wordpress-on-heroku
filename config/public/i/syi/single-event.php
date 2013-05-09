<? 

global $post, $wpdb, $GIFTS_V2, $GIFTS_LOC, $header_file;

unset($_COOKIE['eid']);
$setcookie = setcookie('eid', $post->ID, time()+3600, '/', 
  ".".str_replace(array("https://","http://","/"),"",get_bloginfo('url')));	

if($_GET['eid']=='yes') { pre_dump($post->ID);pre_dump($_COOKIE['eid']); }

remove_action('syi_pagetop', 'draw_the_crumbs', 0);
include('campaign.php');
die();
