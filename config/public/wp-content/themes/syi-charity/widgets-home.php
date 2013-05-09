<?
global $blog_id;

draw_widget('stories_widget', array(
  'cols' => 6,
  'blog_id' => $blog_id,
  'title' => 'See stories of lives changed',
  'see_all' => '<a class="see-all" href="/stories"><u>read more</u> &raquo;</a>',
  'banner' => true
));

/*
draw_widget('posts_widget', array(
  'cols' => 6,
  'blog_id' => $blog_id,
  'title' => 'Latest posts from our blog',
  'see_all' => '<a class="see-all" href="/blog"><u>visit our blog</u> &raquo;</a>',
  'banner' => true
));
*/

?>
<? dynamic_sidebar('sidebar-home-bottom'); ?>
