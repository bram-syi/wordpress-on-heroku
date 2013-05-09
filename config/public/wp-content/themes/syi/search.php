<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

standard_page();

add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');
add_filter('excerpt_more', 'new_excerpt_more');

 
?>

<? get_header(); ?>

<div id="search-page" class="syi-page based evs" action="<?= get_permalink($post->ID) ?>" method="post">
  <section class="syi-contents">
    <section class="right syi-sidebar">
      These are your search results!
      <br><br>
      I could put blog search of some kind over here
    </section>

    <?php if (have_posts()) : ?>

      <h2 class="pagetitle">Search Results</h2>

      <? get_search_form(); ?>

      <?php while (have_posts()) : the_post(); ?>

        <div <?php post_class() ?>>
          <small style="float:right;"><?php the_time('l, F jS, Y') ?></small>
          <h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
          <div><? the_excerpt(); ?></div>

        </div>

      <?php endwhile; ?>

      <div class="navigation">
        <div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
        <div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
      </div>

    <?php else : ?>

      <h2 class="center">No posts found. Try a different search?</h2>
      <?php get_search_form(); ?>

    <?php endif; ?>

  </section>
</div>

<? get_footer();

function posts_link_attributes(){
  return 'class="button green-button medium-button"';
}

function new_excerpt_more($more) {
  global $post;
  return '... <a class="link" href="'. get_permalink($post->ID) . '">more</a>';
}

