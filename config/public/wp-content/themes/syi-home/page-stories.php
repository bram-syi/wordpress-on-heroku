<? 
global $NO_SIDEBAR;
$NO_SIDEBAR = TRUE;

get_header(); 

get_template_part( 'loop', basename(__FILE__, '.php') );

?><article style="padding: 0 30px 30px;"><?
$stories = get_stories_by_tag(NULL, 30, TRUE, 'rand()');
if (count($stories) > 0)
  draw_stories($stories, TRUE);
?></article><?

get_footer();
