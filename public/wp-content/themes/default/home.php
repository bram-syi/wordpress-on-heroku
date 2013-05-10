<?php
/*
Template Name: home
*/
?>
<?php get_header(); ?>
  <div id="content">
  <div id="accordion">
	<div class="element atStart">
  		<div class="intro">
		</div>
	
<?php $postCount = 0; ?>
  <?php if (have_posts()) : ?>
  	<?php while (have_posts()) : the_post(); ?>

	<div class="post" id="post-<?php the_ID(); ?>">
	  <div class="entry">
        
		<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2> 
	  </div>
	  
	   <div style="visibility: hidden; border: 1px solid #F0E0C8; padding: 5px; background-color: #FFF4E5; color: #BD8D71;">
	  	<span>Posted by <?php if (get_the_author_url()) { ?><a href="<?php the_author_url(); ?>"><?php the_author(); ?></a><?php } else { the_author(); } ?> on <?php the_time('F jS, Y') ?></span>
	  </div>
		<div class="post-content">
			<?php the_content('Read the rest of this entry &raquo;'); ?>
		</div>
		<div class="post-info">
			<span class="post-cat"><?php the_category(', ') ?>
			</span> <span class="post-comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span>
		</div>
	</div>
	
	<?php endwhile; ?>
	
	<div class="navigation">
	  <span class="previous-entries"><?php next_posts_link('Previous Entries') ?></span> <span class="next-entries"><?php previous_posts_link('Next Entries') ?></span>
	</div>
	
	<?php else : ?>
	
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		
  <?php endif; ?>
  	
  </div>

	</div>
	
  </div><!--/content -->
  
<?php //get_sidebar(); ?>

<?php //get_footer(); ?>
