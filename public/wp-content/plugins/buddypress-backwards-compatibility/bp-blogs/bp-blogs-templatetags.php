<?php

/***
 * Template loops for blog posts and comments are deprecated.
 * Instead you should be using the activity stream template loop and filtering
 * on blog posts and blog comments either with or without a user_id.
 */

/**********************************************************************
 * User Blog Posts listing template class
 */

class BP_Blogs_Blog_Post_Template {
	var $current_post = -1;
	var $post_count;
	var $posts;
	var $post;

	var $in_the_loop;

	var $pag_page;
	var $pag_num;
	var $pag_links;
	var $total_post_count;

	function bp_blogs_blog_post_template( $user_id, $per_page, $max ) {
		global $bp;

		if ( !$user_id )
			$user_id = $bp->displayed_user->id;

		$this->pag_page = isset( $_GET['fpage'] ) ? intval( $_GET['fpage'] ) : 1;
		$this->pag_num = isset( $_GET['num'] ) ? intval( $_GET['num'] ) : $per_page;

		if ( !$this->posts = wp_cache_get( 'bp_user_posts_' . $user_id, 'bp' ) ) {
			$this->posts = bp_blogs_get_posts_for_user( $user_id );
			wp_cache_set( 'bp_user_posts_' . $user_id, $this->posts, 'bp' );
		}

		if ( !$max || $max >= (int)$this->posts['count'] )
			$this->total_post_count = (int)$this->posts['count'];
		else
			$this->total_post_count = (int)$max;

		$this->posts = array_slice( (array)$this->posts['posts'], intval( ( $this->pag_page - 1 ) * $this->pag_num), intval( $this->pag_num ) );

		if ( $max ) {
			if ( $max >= count($this->posts) )
				$this->post_count = count($this->posts);
			else
				$this->post_count = (int)$max;
		} else {
			$this->post_count = count($this->posts);
		}

		$this->pag_links = paginate_links( array(
			'base' => add_query_arg( 'fpage', '%#%' ),
			'format' => '',
			'total' => ceil($this->total_post_count / $this->pag_num),
			'current' => $this->pag_page,
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'mid_size' => 1
		));
	}

	function has_posts() {
		if ( $this->post_count )
			return true;

		return false;
	}

	function next_post() {
		$this->current_post++;
		$this->post = $this->posts[$this->current_post];

		return $this->post;
	}

	function rewind_posts() {
		$this->current_post = -1;
		if ( $this->post_count > 0 ) {
			$this->post = $this->posts[0];
		}
	}

	function user_posts() {
		if ( $this->current_post + 1 < $this->post_count ) {
			return true;
		} elseif ( $this->current_post + 1 == $this->post_count ) {
			do_action('loop_end');
			// Do some cleaning up after the loop
			$this->rewind_posts();
		}

		$this->in_the_loop = false;
		return false;
	}

	function the_post() {
		global $post;

		$this->in_the_loop = true;
		$post = $this->next_post();

		if ( 0 == $this->current_post ) // loop has just started
			do_action('loop_start');
	}
}

function bp_has_posts( $args = '' ) {
	global $posts_template;

	$defaults = array(
		'user_id' => false,
		'per_page' => 10,
		'max' => false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$posts_template = new BP_Blogs_Blog_Post_Template( $user_id, $per_page, $max );
	return apply_filters( 'bp_has_posts', $posts_template->has_posts(), &$posts_template );
}

function bp_posts() {
	global $posts_template;
	return $posts_template->user_posts();
}

function bp_the_post() {
	global $posts_template;
	return $posts_template->the_post();
}

function bp_post_pagination_count() {
	global $bp, $posts_template;

	$from_num = bp_core_number_format( intval( ( $posts_template->pag_page - 1 ) * $posts_template->pag_num ) + 1 );
	$to_num = bp_core_number_format( ( $from_num + ( $posts_template->pag_num - 1 ) > $posts_template->total_post_count ) ? $posts_template->total_post_count : $from_num + ( $posts_template->pag_num - 1 ) );

	echo sprintf( __( 'Viewing post %s to %s (of %s posts)', 'buddypress' ), $from_num, $to_num, $posts_template->total_post_count ); ?> &nbsp;
	<span class="ajax-loader"></span><?php
}

function bp_post_pagination_links() {
	echo bp_get_post_pagination_links();
}
	function bp_get_post_pagination_links() {
		global $posts_template;

		return apply_filters( 'bp_get_post_pagination_links', $posts_template->pag_links );
	}

function bp_post_id() {
	echo bp_get_post_id();
}
	function bp_get_post_id() {
		global $posts_template;
		return apply_filters( 'bp_get_post_id', $posts_template->post->ID );
	}

function bp_post_title( $deprecated = true ) {
	if ( !$deprecated )
		bp_get_post_title();
	else
		echo bp_get_post_title();
}
	function bp_get_post_title() {
		global $posts_template;

		return apply_filters( 'bp_get_post_title', $posts_template->post->post_title );
	}

function bp_post_permalink() {
	global $posts_template;

	echo bp_post_get_permalink();
}

function bp_post_excerpt() {
	echo bp_get_post_excerpt();
}
	function bp_get_post_excerpt() {
		global $posts_template;
		echo apply_filters( 'bp_get_post_excerpt', $posts_template->post->post_excerpt );
	}

function bp_post_content() {
	echo bp_get_post_content();
}
	function bp_get_post_content() {
		global $posts_template;
		$content = $posts_template->post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		return apply_filters( 'bp_get_post_content', $content );
	}

function bp_post_status() {
	echo bp_get_post_status();
}
	function bp_get_post_status() {
		global $posts_template;
		return apply_filters( 'bp_get_post_status', $posts_template->post->post_status );
	}

function bp_post_date( $date_format = null, $deprecated = true ) {
	if ( !$date_format )
		$date_format = get_option('date_format');

	if ( !$deprecated )
		return bp_get_post_date( $date_format );
	else
		echo bp_get_post_date();
}
	function bp_get_post_date( $date_format = null ) {
		global $posts_template;

		if ( !$date_format )
			$date_format = get_option('date_format');

		echo apply_filters( 'bp_get_post_date', mysql2date( $date_format, $posts_template->post->post_date ) );
	}

function bp_post_comment_count() {
	echo bp_get_post_comment_count();
}
	function bp_get_post_comment_count() {
		global $posts_template;
		return apply_filters( 'bp_get_post_comment_count', $posts_template->post->comment_count );
	}

function bp_post_comments( $zero = 'No Comments', $one = '1 Comment', $more = '% Comments', $css_class = '', $none = 'Comments Off' ) {
	global $posts_template, $wpdb;

	$number = (int)$posts_template->post->comment_count;

	if ( 0 == $number && 'closed' == $posts_template->postcomment_status && 'closed' == $posts_template->postping_status ) {
		echo '<span' . ((!empty($css_class)) ? ' class="' . $css_class . '"' : '') . '>' . $none . '</span>';
		return;
	}

	if ( !empty($posts_template->postpost_password) ) { // if there's a password
		if ( !isset($_COOKIE['wp-postpass_' . COOKIEHASH]) || $_COOKIE['wp-postpass_' . COOKIEHASH] != $posts_template->postpost_password ) {  // and it doesn't match the cookie
			echo __('Enter your password to view comments', 'buddypress');
			return;
		}
	}

	echo '<a href="';

	if ( 0 == $number )
		echo bp_post_get_permalink() . '#respond';
	else
		echo bp_post_get_permalink() . '#comments';
	echo '"';

	if ( !empty( $css_class ) ) {
		echo ' class="'.$css_class.'" ';
	}
	$title = attribute_escape( $posts_template->post->post_title );

	echo apply_filters( 'comments_popup_link_attributes', '' );

	echo ' title="' . sprintf( __('Comment on %s', 'buddypress'), $title ) . '">';

	if ( 1 == $number )
		printf( __( '%d Comment', 'buddypress' ), $number );
	else
		printf( __( '%d Comments', 'buddypress' ), $number );

	echo '</a>';
}

function bp_post_author( $deprecated = true ) {
	if ( !$deprecated )
		return bp_get_post_author();
	else
		echo bp_get_post_author();
}
	function bp_get_post_author() {
		global $posts_template;

		return apply_filters( 'bp_get_post_author', bp_core_get_userlink( $posts_template->post->post_author ) );
	}

function bp_post_category( $separator = '', $parents = '', $post_id = false, $deprecated = true ) {
	global $posts_template;

	if ( !$deprecated )
		return bp_get_post_category( $separator, $parents, $post_id );
	else
		echo bp_get_post_category();
}
	function bp_get_post_category( $separator = '', $parents = '', $post_id = false ) {
		global $posts_template;

		if ( !$post_id )
			$post_id = $posts_template->post->ID;

		return apply_filters( 'bp_get_post_category', get_the_category_list( $separator, $parents, $post_id ) );
	}

function bp_post_tags( $before = '', $sep = ', ', $after = '' ) {
	global $posts_template, $wpdb;

	/* Disabling this for now as it's too expensive and there is no global tags directory */
	return false;

	switch_to_blog( $posts_template->post->blog_id );
	$terms = bp_post_get_term_list( $before, $sep, $after );
	restore_current_blog();
}

function bp_post_blog_id() {
	echo bp_get_post_blog_id();
}
	function bp_get_post_blog_id() {
		global $posts_template;

		return apply_filters( 'bp_get_post_blog_id', $posts_template->post->blog_id );
	}

function bp_post_blog_name() {
	echo bp_get_post_blog_name();
}
	function bp_get_post_blog_name() {
		global $posts_template;
		return apply_filters( 'bp_get_post_blog_name', get_blog_option( $posts_template->post->blog_id, 'blogname' ) );
	}

function bp_post_blog_permalink() {
	echo bp_get_post_blog_permalink();
}
	function bp_get_post_blog_permalink() {
		global $posts_template;
		return apply_filters( 'bp_get_post_blog_permalink', get_blog_option( $posts_template->post->blog_id, 'siteurl' ) );
	}

function bp_post_get_permalink( $post = null, $blog_id = null ) {
	global $current_blog, $posts_template;

	if ( !$post )
		$post = $posts_template->post;

	if ( !$blog_id )
		$blog_id = $posts_template->post->blog_id;

	if ( !$post || !$blog_id )
		return false;

	$rewritecode = array(
		'%year%',
		'%monthnum%',
		'%day%',
		'%hour%',
		'%minute%',
		'%second%',
		$leavename? '' : '%postname%',
		'%post_id%',
		'%category%',
		'%author%',
		$leavename? '' : '%pagename%',
	);

	if ( 'page' == $post->post_type )
		return get_page_link($post->ID, $leavename);
	else if ( 'attachment' == $post->post_type )
		return get_attachment_link($post->ID);

	$permalink = get_blog_option( $blog_id, 'permalink_structure' );
	$site_url = get_blog_option( $blog_id, 'siteurl' );

	if ( '' != $permalink && !in_array($post->post_status, array('draft', 'pending')) ) {
		$unixtime = strtotime($post->post_date);

		$category = '';
		if ( false !== strpos($permalink, '%category%') ) {
			$cats = get_the_category($post->ID);
			if ( $cats )
				usort($cats, '_usort_terms_by_ID'); // order by ID
			$category = $cats[0]->slug;
			if ( $parent=$cats[0]->parent )
				$category = get_category_parents($parent, FALSE, '/', TRUE) . $category;

			// show default category in permalinks, without
			// having to assign it explicitly
			if ( empty($category) ) {
				$default_category = get_category( get_option( 'default_category' ) );
				$category = is_wp_error( $default_category ) ? '' : $default_category->slug;
			}
		}

		$author = '';
		if ( false !== strpos($permalink, '%author%') ) {
			$authordata = get_userdata($post->post_author);
			$author = $authordata->user_nicename;
		}

		$date = explode(" ",date('Y m d H i s', $unixtime));
		$rewritereplace =
		array(
			$date[0],
			$date[1],
			$date[2],
			$date[3],
			$date[4],
			$date[5],
			$post->post_name,
			$post->ID,
			$category,
			$author,
			$post->post_name,
		);
		$permalink = $site_url . str_replace($rewritecode, $rewritereplace, $permalink);
		$permalink = user_trailingslashit($permalink, 'single');
		return apply_filters('post_link', $permalink, $post);
	} else { // if they're not using the fancy permalink option
		$permalink = $site_url . '/?p=' . $post->ID;
		return apply_filters('post_link', $permalink, $post);
	}
}

function bp_post_get_term_list( $before = '', $sep = '', $after = '' ) {
	global $posts_template;

	$terms = get_the_terms( $posts_template->post->ID, 'post_tag' );

	if ( is_wp_error($terms) )
		return $terms;

	if ( empty( $terms ) )
		return false;

	foreach ( (array)$terms as $term ) {
		$link = get_blog_option( BP_ROOT_BLOG, 'siteurl') . '/tag/' . $term->slug;
		$link = apply_filters('term_link', $link);

		$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
	}

	$term_links = apply_filters( "term_links-$taxonomy", $term_links );

	echo $before . join($sep, $term_links) . $after;
}


/**********************************************************************
 * User Blog Comments listing template class
 */

class BP_Blogs_Post_Comment_Template {
	var $current_comment = -1;
	var $comment_count;
	var $comments;
	var $comment;

	var $in_the_loop;

	var $pag_page;
	var $pag_num;
	var $pag_links;
	var $total_comment_count;

	function bp_blogs_post_comment_template( $user_id, $per_page, $max ) {
		global $bp;

		if ( !$user_id )
			$user_id = $bp->displayed_user->id;

		$this->pag_page = isset( $_GET['compage'] ) ? intval( $_GET['compage'] ) : 1;
		$this->pag_num = isset( $_GET['num'] ) ? intval( $_GET['num'] ) : $per_page;

		if ( !$this->comments = wp_cache_get( 'bp_user_comments_' . $user_id, 'bp' ) ) {
			$this->comments = bp_blogs_get_comments_for_user( $user_id );
			wp_cache_set( 'bp_user_comments_' . $user_id, $this->comments, 'bp' );
		}

		if ( !$max || $max >= (int)$this->comments['count'] )
			$this->total_comment_count = (int)$this->comments['count'];
		else
			$this->total_comment_count = (int)$max;

		$this->comments = array_slice( (array)$this->comments['comments'], intval( ( $this->pag_page - 1 ) * $this->pag_num), intval( $this->pag_num ) );

		if ( $max ) {
			if ( $max >= count($this->comments) )
				$this->comment_count = count($this->comments);
			else
				$this->comment_count = (int)$max;
		} else {
			$this->comment_count = count($this->comments);
		}

		$this->pag_links = paginate_links( array(
			'base' => add_query_arg( 'compage', '%#%' ),
			'format' => '',
			'total' => ceil($this->total_comment_count / $this->pag_num),
			'current' => $this->pag_page,
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
			'mid_size' => 1
		));

	}

	function has_comments() {
		if ( $this->comment_count )
			return true;

		return false;
	}

	function next_comment() {
		$this->current_comment++;
		$this->comment = $this->comments[$this->current_comment];

		return $this->comment;
	}

	function rewind_comments() {
		$this->current_comment = -1;
		if ( $this->comment_count > 0 ) {
			$this->comment = $this->comments[0];
		}
	}

	function user_comments() {
		if ( $this->current_comment + 1 < $this->comment_count ) {
			return true;
		} elseif ( $this->current_comment + 1 == $this->comment_count ) {
			do_action('loop_end');
			// Do some cleaning up after the loop
			$this->rewind_comments();
		}

		$this->in_the_loop = false;
		return false;
	}

	function the_comment() {
		global $comment;

		$this->in_the_loop = true;
		$comment = $this->next_comment();

		if ( 0 == $this->current_comment ) // loop has just started
			do_action('loop_start');
	}
}

function bp_has_comments( $args = '' ) {
	global $comments_template;

	$defaults = array(
		'user_id' => false,
		'per_page' => 10,
		'max' => false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$comments_template = new BP_Blogs_Post_Comment_Template( $user_id, $per_page, $max );
	return apply_filters( 'bp_has_comments', $comments_template->has_comments(), &$comments_template );
}

function bp_comments() {
	global $comments_template;
	return $comments_template->user_comments();
}

function bp_the_comment() {
	global $comments_template;
	return $comments_template->the_comment();
}

function bp_comments_pagination() {
	echo bp_get_comments_pagination();
}
	function bp_get_comments_pagination() {
		global $comments_template;

		return apply_filters( 'bp_get_comments_pagination', $comments_template->pag_links );
	}

function bp_comment_id() {
	echo bp_get_comment_id();
}
	function bp_get_comment_id() {
		global $comments_template;
		echo apply_filters( 'bp_get_comment_id', $comments_template->comment->comment_ID );
	}

function bp_comment_post_permalink( $depricated = true ) {
	if ( !$depricated )
		return bp_get_comment_post_permalink();
	else
		echo bp_get_comment_post_permalink();
}
	function bp_get_comment_post_permalink() {
		global $comments_template;

		return apply_filters( 'bp_get_comment_post_permalink', bp_post_get_permalink( $comments_template->comment->post, $comments_template->comment->blog_id ) . '#comment-' . $comments_template->comment->comment_ID );
	}

function bp_comment_post_title( $deprecated = true ) {
	if ( !$deprecated )
		return bp_get_comment_post_title();
	else
		echo bp_get_comment_post_title();
}
	function bp_get_comment_post_title( $deprecated = true ) {
		global $comments_template;

		return apply_filters( 'bp_get_comment_post_title', $comments_template->comment->post->post_title );
	}

function bp_comment_author( $deprecated = true ) {
	global $comments_template;

	if ( !$deprecated )
		return bp_get_comment_author();
	else
		echo bp_get_comment_author();
}
	function bp_get_comment_author() {
		global $comments_template;

		return apply_filters( 'bp_get_comment_author', bp_core_get_userlink( $comments_template->comment->user_id ) );
	}

function bp_comment_content() {
	echo bp_get_comment_content();
}
	function bp_get_comment_content() {
		global $comments_template;
		$content = $comments_template->comment->comment_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		echo apply_filters( 'bp_get_comment_content', $content );
	}

function bp_comment_date( $date_format = null, $deprecated = true ) {
	if ( !$date_format )
		$date_format = get_option('date_format');

	if ( !$deprecated )
		return bp_get_comment_date( $date_format );
	else
		echo bp_get_comment_date( $date_format );
}
	function bp_get_comment_date( $date_format = null ) {
		global $comments_template;

		if ( !$date_format )
			$date_format = get_option('date_format');

		return apply_filters( 'bp_get_comment_date', mysql2date( $date_format, $comments_template->comment->comment_date ) );
	}

function bp_comment_blog_permalink( $deprecated = true ) {
	if ( !$deprecated )
		return bp_get_comment_blog_permalink();
	else
		echo bp_get_comment_blog_permalink();
}
	function bp_get_comment_blog_permalink() {
		global $comments_template;

		return apply_filters( 'bp_get_comment_blog_permalink', get_blog_option( $comments_template->comment->blog_id, 'siteurl' ) );
	}

function bp_comment_blog_name( $deprecated = true ) {
	global $comments_template;

	if ( !$deprecated )
		return bp_get_comment_blog_permalink();
	else
		echo bp_get_comment_blog_permalink();
}
	function bp_get_comment_blog_name( $deprecated = true ) {
		global $comments_template;

		return apply_filters( 'bp_get_comment_blog_name', get_blog_option( $comments_template->comment->blog_id, 'blogname' ) );
	}

/* DEPRECATED */
function bp_blog_avatar_thumb() { echo bp_get_blog_avatar('type=thumb'); }
function bp_blog_avatar_mini() { echo bp_get_blog_avatar('type=thumb&width=30&height=30'); }


?>