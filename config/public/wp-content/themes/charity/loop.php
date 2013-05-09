<? 

$single = FALSE; // is_single();  disabling for now so even single pages get narrow

$theme_name = basename(get_bloginfo('template_directory')); ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( !have_posts() ) : ?>

<?
  $post = null;
  if ($post == null) {
    $posts = get_posts('post_type=page&name=404');
    if (count($posts) > 0)
      $post = $posts[0];
  }
  setup_postdata($post);
?>

  <article id="post-0" class="post error404 not-found based">
    <<?= syi_article_tag() ?> class="entry-title">Sorry, that page doesn't exist! </<?= syi_article_tag() ?>>
    <section class="entry-content">
      <p><?php _e( "We apologize, but we can't find the page you're looking for. Please check the link and try again.", $theme_name ); ?></p>
      <p><br/>You may also be interested in:</p>
      <ul> 
        <li><a href="/">Our home page</a></li>
        <li><a href="/stories">Real stories of our impact</a></li>
        <li><a href="/about">More information about our organization</a></li>
      </ul>
    </section>
  </article>
<?php endif; ?>

<?
global $blog_id; 
if (!$single && isset($post)) { 
  do_action("{$post->post_type}_loop_top");
  ?><div class="<?=$post->post_type?>-loop"><?
} 
  /* Start the Loop.
   *
   * It is broken into three main parts: when we're displaying
   * posts that are in the gallery category, when we're displaying
   * posts in the asides category, and finally all other posts.
   *
   * Additionally, we sometimes check for whether we are on an
   * archive page, a search page, etc., allowing for small differences
   * in the loop on each template without actually duplicating
   * the rest of the loop that is shared.
   */ ?>
<?php while ( have_posts() ) : the_post(); ?>

    <? do_action("syi_before_$post->post_type", $post, is_single()); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('based padded'); ?>>

      <? 
       if(get_post_type()=='article') {
         echo '<div ';
         if (!empty($post->post_excerpt)) { echo ' class="collapser"'; }
         echo '>';
       } else if (get_post_type() != 'page') {
         share_widget(array(
           'title'=>'Check this out!',
           'link'=>$post->guid,
           'size'=>'small'
         ));
       }
global $GIFTS_LOC;
if (!$GIFTS_LOC)
  $GIFTS_LOC = "st/$blog_id/$post->ID";

$show_title = true;
if (get_post_type() == 'page' && $post->post_parent == 0)
  $show_title = false;

if ($show_title) { ?>
     <<?= syi_article_tag() ?> class="entry-title">
       <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s', $theme_name ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?= strtotitle(get_the_title()); ?></a>
     </<?= syi_article_tag() ?>>
<? }

if (get_post_type() == 'post') { ?>
  <p class="byline">posted <? relative_post_the_date(null, null, null, true); ?>
<? if ($blog_id == 1 && $post->post_author > 1) {
  echo ' by ';
  the_author_posts_link();
 } ?>
</p>
<? } ?>

      <section class="entry-meta">
      </section>

      <section class="entry-summary if-collapsed">
         <?= htmlspecialchars( $post->post_excerpt) ?>
      </section>
      <section class="entry-content if-expanded slide">
        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', $theme_name ) ); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', $theme_name ), 'after' => '</div>' ) ); ?>
        <br/><br/>
      </section>

      <? do_action("syi_after_$post->post_type", $post, is_single()); ?>

      <?php comments_template( '', true ); ?>

      <p class="expander-label expander if-collapsed see-all"><br/><u>Read more</u> &raquo;</p>
    <? if(get_post_type()=='article') { ?></div><? } ?>
    </article>

<?php endwhile; // End the loop. Whew. ?>
<? if (!$single) { ?></div><? } ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
   <nav id="nav-below" class="navigation">
     <div class="nav-previous"><?php next_posts_link( __( '<div class="button green-button"><span class="meta-nav">&larr;</span> Older stories</div>', $theme_name ) ); ?></div>
     <div class="nav-next"><?php previous_posts_link( __( '<div class="button green-button">Newer stories <span class="meta-nav">&rarr;</span></div>', $theme_name ) ); ?></div>
   </nav>
<?php endif; ?>
