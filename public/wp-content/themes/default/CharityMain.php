<?php
/*
Template Name: CharityMain
*/
?>

<?php get_header(); ?>
  <div id="content">
  <div id="accordion">
	<div class="element atStart">
  		<div class="intro">
  			<br/>
  			<h1><?php echo get_option("blogname"); ?></h1>
			<font size="2"><?php echo get_option("blogdescription"); ?></font>
		</div>
		<br/>	
	<div>
	<?php
	
	// Gets a custom field to display in this case the 'video' field
	 $key="Blog_video"; echo get_post_meta($post->ID, $key, true); 
	?>
	</div>
	<br/>
	<div>
	<?php
	
	// Gets a custom field to display in this case the 'video' field
	 $key="freeHTML"; echo get_post_meta($post->ID, $key, true); 
	?>
	</div>
	
	<div class="ListResult">
	<!-- *** DONATIONS MODULE **** -->

	<form name="_xclick" action="https://www.paypal.com/us/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="donations@ieaf.org">
	<input type="hidden" name="item_name" value="Deepalaya Donation">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="on0" value="Donation">Make A Donation</td>
    <table>
	<tr>
		<td>
	    <select name="os0">
	     <option value="Select Donation">Select Donation
	    <option value="$10.00">$10.00
	    <option value="$20.00">$20.00
	    <option value="$30.00">$30.00
	    </select>
    	</td>
    	<td>
    	<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
    	</td>
    </tr>
    </table>
	<input type="hidden" name="amount" value="25.00">
	
	</form>
	
	<!-- *** END DONATIONS MODULE *** -->
</div>

	
	<?php if(function_exists('wp_email')) { email_link(); } ?>  


	
	
<?php $postCount = 0; ?>
  <?php if (have_posts()) : ?>
  	<?php while (have_posts()) : the_post(); ?>

	<div class="post" id="post-<?php the_ID(); ?>">
<!--
	  <div class="entry">
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	  </div>
-->  
	  <!-- <div style="visibility: hidden; border: 1px solid #F0E0C8; padding: 5px; background-color: #FFF4E5; color: #BD8D71;"> -->
	  	<!---	<span>Posted by <?php if (get_the_author_url()) { ?><a href="<?php the_author_url(); ?>"><?php the_author(); ?></a><?php } else { the_author(); } ?> on <?php the_time('F jS, Y') ?></span> -->
	  </div>
		<div class="post-content">
			<?php the_content('Read the rest of this entry &raquo;'); ?>
		</div>
		<div class="post-info">
			<!-- <span class="post-cat"><?php the_category(', ') ?> -->
			</span> <span class="post-comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span>
		</div>
	</div>
	
	<?php endwhile; ?>
	<?php //ah_recent_posts_mu(5, 30, true, '<li>', '</li>'); ?>
	
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
  
<?php get_sidebar(); ?>

<?php //get_footer(); ?>
