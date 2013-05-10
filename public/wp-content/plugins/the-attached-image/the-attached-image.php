<?php
/*
Plugin Name: The Attached Image
Plugin URI: http://return-true.com/2008/12/wordpress-plugin-the-attached-image/
Description: Display the first image attached to a post. Use the_attached_image() in the post loop. Order can be changed using menu order via the WP gallery. Based on the post image WordPress plugin by Kaf Oseo.
Version: 2.6.3.1
Author: Paul Robinson
ToDo: Massive code cleanup, basically clean up the code comment it alot & stuff planned for version 2.7.
Author URI: http://return-true.com

	Copyright (c) 2008-2010 Paul Robinson (http://return-true.com)
	The Attached Image is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl.txt

	Based on the post image WordPress plugin by Kaf Oseo.
	Comments are there for those who wish to learn or make mods.
*/


/* Before we start let's make the options page in the WP admin */

add_action('admin_menu', 'att_add_options');

function att_add_options() {
	add_theme_page('The Attached Image Options', 'The Attached Image', 8, 'attachedoptions', 'att_options_page');	
}

function att_options_page() {
    // variables for the field and option names 
    $opt_name = array('function' => 'att_function',
					  'function_number' => 'att_function_number',
					  'img_size' =>'att_img_size',
					  'css_class' => 'att_css_class',
					  'img_width' => 'att_img_width',
					  'img_height' => 'att_img_height',
					  'default_img' => 'att_default_img',
					  'href' => 'att_href',
					  'alt' => 'att_alt',
					  'link_title' => 'att_link_title',
					  'img_tag' => 'att_img_tag',
					  'echo' => 'att_echo',
					  'href_rel' => 'att_href_rel',
					  'img_order' => 'att_img_order',
					  'in_post_image_size' => 'att_in_post_image_size');
    $hidden_field_name = 'att_submit_hidden';

    // Read in existing option value from database
    $opt_val = array('function' => get_option( $opt_name['function'] ),
					 'function_number' => get_option( $opt_name['function_number'] ),
					 'img_size' => get_option( $opt_name['img_size'] ),
					 'css_class' => get_option( $opt_name['css_class'] ),
					 'img_width' => get_option( $opt_name['img_width'] ),
					 'img_height' => get_option( $opt_name['img_height'] ),
					 'default_img' => get_option( $opt_name['default_img'] ),
					 'href' => get_option( $opt_name['href'] ),
					 'alt' => get_option( $opt_name['alt'] ),
					 'link_title' => get_option( $opt_name['link_title'] ),
					 'img_tag' => get_option( $opt_name['img_tag']),
					 'echo' => get_option( $opt_name['echo']),
					 'href_rel' => get_option( $opt_name['href_rel']),
					 'img_order' => get_option( $opt_name['img_order']),
					 'in_post_image_size' => get_option( $opt_name['in_post_image_size']));

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
		if(!isset($_POST[ $opt_name['function'] ]))
			$_POST[ $opt_name['function'] ] = 'false';
			
        $opt_val = array('function' => $_POST[ $opt_name['function'] ],
						 'function_number' => $_POST[ $opt_name['function_number'] ],
						 'img_size' => $_POST[ $opt_name['img_size'] ],
						 'css_class' => $_POST[ $opt_name['css_class'] ],
						 'img_width' => $_POST[ $opt_name['img_width'] ],
						 'img_height' => $_POST[ $opt_name['img_height'] ],
						 'default_img' => $_POST[ $opt_name['default_img'] ],
						 'href' => $_POST[ $opt_name['href'] ],
						 'alt' => $_POST[ $opt_name['alt'] ],
						 'link_title' => $_POST[ $opt_name['link_title'] ],
						 'img_tag' => $_POST[ $opt_name['img_tag'] ],
						 'echo' => $_POST[ $opt_name['echo'] ],
						 'href_rel' => $_POST[ $opt_name['href_rel'] ],
						 'img_order' => $_POST[ $opt_name['img_order'] ],
						 'in_post_image_size' => $_POST[ $opt_name['in_post_image_size'] ]);

        // Save the posted value in the database
		update_option( $opt_name['function'], $opt_val['function'] );
		update_option( $opt_name['function_number'], $opt_val['function_number'] );
        update_option( $opt_name['img_size'], $opt_val['img_size'] );
		update_option( $opt_name['css_class'], $opt_val['css_class'] );
		update_option( $opt_name['img_width'], $opt_val['img_width'] );
		update_option( $opt_name['img_height'], $opt_val['img_height'] );
		update_option( $opt_name['default_img'], $opt_val['default_img'] );
		update_option( $opt_name['href'], $opt_val['href'] );
		update_option( $opt_name['alt'], $opt_val['alt'] );
		update_option( $opt_name['link_title'], $opt_val['link_title'] );
		update_option( $opt_name['img_tag'], $opt_val['img_tag'] );
		update_option( $opt_name['echo'], $opt_val['echo'] );
		update_option( $opt_name['href_rel'], $opt_val['href_rel'] );
		update_option( $opt_name['img_order'], $opt_val['img_order'] );
		update_option( $opt_name['in_post_image_size'], $opt_val['in_post_image_size'] );

        // Put an options updated message on the screen

?>
<div id="message" class="updated fade">
  <p><strong>
    <?php _e('Options saved.', 'att_trans_domain' ); ?>
    </strong></p>
</div>

<?php
	}
?>
    <div class="wrap">
    	<h2><?php _e( 'The Attached Image Options', 'att_trans_domain' ); ?></h2>
    	Created by <strong>Paul Robinson</strong>.
    	<div style="width: 500px; margin-top:10px;">
    		<div style="border: 1px solid rgb(221, 221, 221); padding: 10px; float: left; background-color: white; margin-right: 15px;">
    			<div style="width: 350px; height: 130px; float:left;">
        			<h3>Donate</h3>
   					<p>If you like this plugin and have found it to be useful, please help me keep this plugin free by clicking the <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9155415" target="_blank"><strong>donate</strong></a> button, or you can send me a gift from my <a href="https://www.amazon.co.uk/registry/wishlist/3IACY9WPVEPXC/ref=wl_web" target="_blank"><strong>Amazon wishlist</strong></a>. Thank you.</p>
    			</div>
    			<div style="width:100px; float:left; margin:15px 0 0 10px;">
        			<a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9155415"><img src="<?php echo WP_PLUGIN_URL; ?>/the-attached-image/donate-paypal.jpg" alt="Donate with Paypal">	</a>
    
        <a target="_blank" title="Amazon Wish List" href="https://www.amazon.co.uk/registry/wishlist/3IACY9WPVEPXC/ref=wl_web">
        <img src="<?php echo WP_PLUGIN_URL; ?>/the-attached-image/amazon-wishlist.jpg" alt="My Amazon Wish List"> </a>
    </div>
    <div style="clear:both;"></div>
        
    </div>
    </div>
    <div style="clear: both;"></div>

<?php
if(isset($_GET['wpatt-page']) && $_GET['wpatt-page'] == 'docs') {
	require_once('att_docs.php'); //select the documentation.
} else {
	require_once('att_options.php'); //select the options entry page.	
}
?>
</div>
<?php
}


function the_attached_image($args='', $qry_obj = FALSE) {
	global $wp_query, $post;
	
	if($qry_obj !== FALSE) {
		$post = $qry_obj->post;
	} elseif(empty($post)) {
		$post = $wp_query->post;
	}
	
	parse_str($args);
	
	/* 
	   To anyone reading... Yes this section is increadably nasty & needs cleaning desperately.
	   I've started on a new version of TAI which should be
	   done for the next major version, probably 2.6/2.7. Until then I'm afraid this is what authoring a plugin when you are a
	   novice will get you. We all have to start somewhere though I guess. 
	*/
	
	if( get_option('att_function') == "true") {
		$in_post_override['status'] = true;
		$in_post_override['value'] = get_option('att_function_number');
	}
		
	if( !isset($img_size) && !get_option('att_img_size') )
		$img_size = 'thumb';
	elseif(isset($img_size))
		$img_size = $img_size;
	else
		$img_size = get_option('att_img_size');
		
	if( !isset($css_class) && !get_option('att_css_class') )
		$css_class = 'attached-image';
	elseif(isset($css_class))
		$css_class = $css_class;
	else
		$css_class = get_option('att_css_class');
		
	if( !isset($img_tag) && !get_option('att_img_tag') )
		$img_tag = true;
    elseif(isset($img_tag))
		$img_tag = $img_tag;
	else 
		$img_tag = get_option('att_img_tag');
		
	if( !isset($echo) && !get_option('att_echo') ) 
		$echo = true;
    elseif(isset($echo))
		$echo = $echo; 
	else
		$echo = get_option('att_echo');
		
	if( !isset($href) )
		$href = false;
		
	if( !isset($link) && !get_option('att_href') ) {
		$link = 'none';
    } elseif(isset($link)) {
		$link = $link;
                
        if($link != 'none') {
			$href = true;
		} else {
			$href = false;	
		}
	} else {
		$link = get_option('att_href');
		
		if($link != 'none') {
			$href = true;
		} else {
			$href = false;	
		}		
	}
	
	if( !isset($alt) && !get_option('att_alt') ) {
		$alt = 'image-name'; 
	} elseif(isset($alt)) {
		$alt = $alt;
	} else {
		if(get_post_meta($post->ID, 'att_custom_alt', true)) {
			$alt = 'custom';
		} else {
			$alt = get_option('att_alt');
		}
	}
		
	if( !isset($link_title) && !get_option('att_link_title') ) {
		$link_title = 'image-name'; 
	} elseif(isset($link_title)) {
		$link_title = $link_title;
	} else {
		if(get_post_meta($post->ID, 'att_custom_link_title', true)) {
			$link_title = 'custom';
		} else {
			$link_title = get_option('att_link_title');
		}
	}
	
	if( !isset($default) && !get_option('att_default_img') )
		$default = false;
	elseif(isset($default))
		$default = $default;
	else 
		$default = get_option('att_default_img');
		
	if( !isset($width) && !get_option('att_img_width'))
		$width = false;
	elseif(isset($width))
		$width = $width;
	else
		$width = get_option('att_img_width');
		
	if( !isset($height) && !get_option('att_img_height'))
		$height = false;
	elseif(isset($height))
		$height = $height;
	else 
		$height = get_option('att_img_height');
		
	if( !isset($image_order) && !get_option('att_img_order') && get_post_meta($post->ID, 'att_img_order', true) == "") {
		$image_order = 1;
	} elseif(get_post_meta($post->ID, 'att_img_order', true)) {
		$image_order = get_post_meta($post->ID, 'att_img_order', true);
	} elseif(isset($image_order)) {
		if(is_numeric($image_order))
			$image_order = intval($image_order);
		else
			$image_order = 1;
	} else {
		$image_order = get_option('att_img_order');
		if(is_numeric($image_order))
			$image_order = intval($image_order);
		else
			$image_order = 1;
	}
	
	//var_dump(get_post_meta($post->ID, 'att_img_order', true));
	
	if( !isset($rel) && !get_option('att_href_rel') )
		$rel = false; 
    elseif(isset($rel))
		$rel = $rel;
	else
		$rel = get_option('att_href_rel');
		
	if( !isset($in_post_image) && get_post_meta($post->ID, 'att_in_post_image', true) == "")
		$in_post_image = false;
	elseif(isset($in_post_image))
		$in_post_image = $in_post_image;
	else
		$in_post_image = get_post_meta($post->ID, 'att_in_post_image', true);
		
	if(isset($in_post_override) && $in_post_override['status'] === true)
		$in_post_image = $in_post_override['value'];
		
	if( !isset($in_post_image_size) && !get_option('att_in_post_image_size') )
		$in_post_image_size = 'thumb';
	elseif(isset($in_post_image_size))
		$in_post_image_size = $in_post_image_size;
	else {
		if(get_post_meta($post->ID, 'att_in_post_image_size', true)) {
			$in_post_image_size = get_post_meta($post->ID, 'att_in_post_image_size', true);
		} else {
			$in_post_image_size = get_option('att_in_post_image_size');
		}
	}
	
	if(get_post_meta($post->ID, 'att_custom_link', true) != "") {
		$href = true;
		$link = "custom"; //override the link to custom because the custom field is set.
	}
	
	if($custom_img_meta = get_post_meta($post->ID, 'att_custom_img', true)) {
			$attachments = array(get_post($custom_img_meta));
			$custom_img = true;
	} else {
			$custom_img = false;	
	}
		
	//If WP's post array is empty we can't do anything but only if a custom image hasn't been set.
	if(empty($post) && $custom_img === false && get_post_meta($post->ID, 'att_default_pic', true) == "")
		return false;
			
	if($in_post_image != false) {
		
		$image_cache = array();
		
		preg_match_all("/<img[^']*?src=\"([^']*?)\"[^']*?>/", $post->post_content, $matches, PREG_PATTERN_ORDER);
		
		$image_cache = $matches[1];
				
		--$in_post_image;
		
		if($in_post_image < count($image_cache)) {
			
			$img_url = $image_cache[$in_post_image];
			
		} else {
			
			echo create_default_image($link, $link_title, $width, $height, $href, $post, $default, $css_class, $alt, $img_tag);
			return;
		
		}
				
		if($in_post_image_size == 'full') {
			
			$img_url = urldecode($img_url);
			
			if(stristr($img_url, $_SERVER['HTTP_HOST'])) {
				$img_full_path = $_SERVER['DOCUMENT_ROOT'] . str_replace('http://'.$_SERVER['HTTP_HOST'], '', $img_url);
				
				if(file_exists($img_full_path)) {
					$imagesize = @getimagesize($img_full_path);
					$in_post_num_matches = preg_match_all('/\-(\d+)x(\d+)/is', $img_url, $in_post_matches);
					if($in_post_num_matches > 0) {
						$in_post_num_matches--;
						$second_img_url = str_replace($in_post_matches[0][$in_post_num_matches], '', $img_url);
						$thumb_path = $_SERVER['DOCUMENT_ROOT'] . str_replace('http://'.$_SERVER['HTTP_HOST'], '', $thumb_url);
						if(file_exists($thumb_path)) {
							$img_url = $second_img_url;
							$imagesize = @getimagesize($thumbpath);
						}
					}
				} else {	
					$imagesize = array();
				}
				
			} else {
				//if image is offsite we try our best to get the image size;
				$imagesize = @getimagesize($img_url);
				//if not we have to resort to making an empty array to stop PHP taking a fit.
				if(!isset($imagesize[0])) {
					$imagesize = array('', '', '', '');	
				}
			}

		} else {
						 
			$thumbsize = array(
						 'width' => get_option($in_post_image_size.'_size_w'),
						 'height' => get_option($in_post_image_size.'_size_h'),
						 'crop' => get_option('thumbnail_crop')
						 );
			
			$img_url = urldecode($img_url);
					
			if(stristr($img_url, $_SERVER['HTTP_HOST'])) {
				$img_full_path = $_SERVER['DOCUMENT_ROOT'] . str_replace('http://'.$_SERVER['HTTP_HOST'], '', $img_url);
				
				if(($thumbsize['crop'] == 1 && $in_post_image_size != 'thumbnail') || ($thumbsize['crop'] == 0)) {
				
					list($org_w, $org_h) = @getimagesize($img_full_path);
					
					if($org_w > $org_h) {
						$ratio = $org_w / $thumbsize['width'];
						$new_h = $org_h / $ratio;
						$thumbsize['height'] = floor($new_h);	
					} elseif($org_h > $org_w) {
						$ratio = $org_h / $thumbsize['height'];
						$new_w = $org_w / $ratio;
						$thumbsize['width'] = floor($new_w);
					}
				
				}
				
				$pathinfo = pathinfo($img_url);
			
				$thumb_url = str_replace($pathinfo['filename'], $pathinfo['filename'].'-'.$thumbsize['width'].'x'.$thumbsize['height'], $img_url);
				$thumb_path = $_SERVER['DOCUMENT_ROOT'] . str_replace('http://'.$_SERVER['HTTP_HOST'], '', $thumb_url);
				$thumb_url = str_replace('.'.$pathinfo['extension'], '.'.strtolower($pathinfo['extension']), $thumb_url);
				$thumb_path = str_replace('.'.$pathinfo['extension'], '.'.strtolower($pathinfo['extension']), $thumb_path);
				
				if(!file_exists($thumb_path)) {
					$in_post_num_matches = preg_match_all('/\-(\d+)x(\d+)/is', $img_url, $in_post_matches);
					if($in_post_num_matches > 0) {
						$in_post_num_matches--;
						$second_img_url = str_replace($in_post_matches[0][$in_post_num_matches], '', $img_url);
						$pathinfo = pathinfo($second_img_url);
						$thumb_url = str_replace($pathinfo['filename'], $pathinfo['filename'].'-'.$thumbsize['width'].'x'.$thumbsize['height'], $second_img_url);
						$thumb_path = $_SERVER['DOCUMENT_ROOT'] . str_replace('http://'.$_SERVER['HTTP_HOST'], '', $thumb_url);
						$thumb_url = str_replace('.'.$pathinfo['extension'], '.'.strtolower($pathinfo['extension']), $thumb_url);
						$thumb_path = str_replace('.'.$pathinfo['extension'], '.'.strtolower($pathinfo['extension']), $thumb_path);
					}
				
				}
				
				if(file_exists($thumb_path)) {
					$imagesize = @getimagesize($thumb_path);
					$img_url = $thumb_url;
				} else {
					if(file_exists($img_full_path)) {
						$imagesize = @getimagesize($img_full_path);
					} else {	
						$imagesize = array();
					}
				}
			} else {
				//if image is offsite we try our best to get the image size;
				$imagesize = @getimagesize($img_url);
				//if not we have to resort to making an empty array to stop PHP taking a fit.
				if(!isset($imagesize[0])) {
					$imagesize = array('', '', '', '');	
				}
			}
		}
		
		$info = array('title' => $post->post_title,
					  'url' => $img_url,
					  'size' => $imagesize[3],
					  'width' => $imagesize[0],
					  'height' => $imagesize[1],
					  'type' => $imagesize[2]
					  );
		
		if(get_post_meta($post->ID, 'att_img_width', true))
			$width = get_post_meta($post->ID, 'att_img_width', true);
		if(get_post_meta($post->ID, 'att_img_height', true))
			$height = get_post_meta($post->ID, 'att_img_height', true);
		
		if($width !== false && $height !== false) {
			$info['width'] = $width;
			$info['height'] = $height;
			$info['size'] = 'width="'.$width.'" height="'.$height.'"';
		} elseif($width !== false) {
			$info['width'] = $width;
			$info['height'] = 0;
			$info['size'] = 'width="'.$width.'"';
		} elseif($height !== false) {
			$info['height'] = $height;
			$info['width'] = 0;
			$info['size'] = 'height="'.$height.'"';
		}
		
		if($img_tag === true || $img_tag == 'true') {
			$image_output = '<img class="' . $css_class . '" src="' . $info['url'] . '" ' . $info['size'] . ' title="' . $info['title'] . '" alt="' . $info['title'] . '" />';
		} else {
			$image_output = $info['url'];
		}
		
		if($echo === true || $echo == 'true') {
			echo $image_output;
			return true;
		} else {
			return $image_output;
		}
	}
	
	if($custom_img === false) {
		//Get the attachments for the current post. Limit to one and order by the menu_order so that the image shown can be changed by the WP gallery.
		if(function_exists('wp_enqueue_style')) {
			$attachments = get_children(array('post_parent' => $post->ID,
											  'post_status' => 'inherit',
											  'post_type' => 'attachment',
											  'post_mime_type' => 'image',
											  'order' => 'ASC',
											  'orderby' => 'menu_order ID'));
		} else { 
			//WP2.5 Compat...
			$attachments = get_children('post_parent='.$post->ID.'&post_type=attachment&post_mime_type=image&orderby="menu_order ASC, ID ASC"');
		};
	}
	
	$m_width = get_post_meta($post->ID, 'att_width', true);
	$m_height = get_post_meta($post->ID, 'att_height', true);
	
	if(!$width === false && !$m_width == false && !$height === false) {
		$width = $m_width;
		$height = false;
	} elseif(!$height === false && !$m_height == false && !$width === false) {
		$height = $m_height;
		$width = false;
	} else {
		$width = (!$m_width == false) ? $m_width : $width;
		$height = (!$m_height == false) ? $m_height : $height;
	}
	// ^^ Check for custom fields. To stop function call follow through we need to cancel out the $width or the $height if only one has been set by meta.
	
	if(empty($attachments)) { //If attachments is empty then we should check for a default image via meta or via function call.
		
		$image = create_default_image($link, $link_title, $width, $height, $href, $post, $default, $css_class, $alt, $img_tag);
		
		if($image === false)
			return false;
		elseif(empty($image))
			unset($image);
		
	}
	
	if(!isset($image) && empty($image)) { //Gets the correct image depending upon whether or not $image has been set or not.
	
		$i = 0;
		$attach_total = count($attachments);
		
		if($attach_total < $image_order)
			$image_order = $attach_total;
				
		foreach($attachments as $id => $attachment) :
			$i++;
			if($i == $image_order) :
				$attachment = $attachment;
				break;
			endif;
		endforeach;

	
		//$attachment = current($attachments);

		$img_url = wp_get_attachment_url($attachment->ID); //Get URL to attachment
		
		//Pick the right size & get it via WP. If a custom height & width was set cancel out WP's.
		if ( $img_size == 'medium' ) {
			if ( $intermediate = image_get_intermediate_size($attachment->ID, $img_size) ) {
				$img_url = str_replace(basename($img_url), $intermediate['file'], $img_url);
				if($width === false && $height === false) {
					$width = $intermediate['width'];
					$height = $intermediate['height'];
				}
			}
		} elseif ( $img_size == 'thumb' ) {
			if ( $intermediate = image_get_intermediate_size($attachment->ID, 'thumbnail') ) {
				$img_url = str_replace(basename($img_url), $intermediate['file'], $img_url);
				if($width === false && $height === false) {
					$width = $intermediate['width'];
					$height = $intermediate['height'];
				}
			}
		} elseif ( $img_size == 'large' ) {
			if ( $intermediate = image_get_intermediate_size($attachment->ID, 'large') ) {
				$img_url = str_replace(basename($img_url), $intermediate['file'], $img_url);
				if($width === false && $height === false) {
					$width = $intermediate['width'];
					$height = $intermediate['height'];
				}
			}
		} elseif ( $img_size == 'full' ) {
			//Get the image's size since it will make our HTML invalid & the code won't close the img tag without a width & height.
			//$split_pos = strpos($img_url, 'wp-content');
			//$split_len = (strlen($img_url) - $split_pos);
			//$abs_img_url = substr($img_url, $split_pos, $split_len);
			//$full_info = @getimagesize(ABSPATH.$abs_img_url);
			//Thanks to Upekshapriya for pointing out that using this function is much cleaner.
			$full_info = wp_get_attachment_image_src($attachment->ID, 'full'); 
			if($width === false && $height === false) {
				$width = $full_info[1];
				$height = $full_info[2];
			}
		} else { //If it's not one of the previous it must be a post-thumbnail size. Check it out.
			if( $intermediate = image_get_intermediate_size($attachment->ID, $img_size) ) {
				$img_url = str_replace(basename($img_url), $intermediate['file'], $img_url);
				if($width === false && $height === false) {
					$width = $intermediate['width'];
					$height = $intermediate['height'];
				}
			} else { //No custom thumbnail detected... Use Viperbonds regen thumbnail to create thumbnails for old images when adding post-thumbnail support.
				return false;
			}
		}
		
		if($img_tag === true || $img_tag == 'true') { //Do they want an image tag along with setting the height & width.
			$image = '<img src="'.$img_url.'" class="'.$css_class.'"';
			
			$alt_text = get_alt($alt, $attachment, $default); //Get alt text
			if(!empty($alt_text)) {
				$image .= ' alt="'.$alt_text.'"';
			}
			
			if(stristr($link, 'none')) {
				$title_text = get_title($link_title, $attachment, $default);
				if(!empty($title_text)) {
					$image .= ' title="'.$title_text.'"';
				}
			}
			
			if(!$width === false && !$height === false) {
				$image .= ' width="'.$width.'" height="'.$height.'" />'; 
			} elseif(!$width === false) {
				$image .= ' width="'.$width.'" />';
			} elseif(!$height === false) {
				$image .= ' height="'.$height.'" />';
			} else {
				$image .= ' />';	
			}
		} else { //You don't want a img tag then? Well heres the URL.
			$image = $img_url;
		}
		
		if($href === true || $href == 'true') { //Do you want a href & where should it point.
			//First lets figure out what title text they want...
			
			$a_title_text = get_title($link_title, $attachment, $default);
			
			if(!$rel === false) {
                $a_href_rel = 'rel="' . $rel . '"';
			} else {
                $a_href_rel = '';
            }
            
            $a_href_url = wp_get_attachment_url($attachment->ID);
			
			switch ($link) {
				case 'post' :
                    $a_href_url = get_permalink($post->ID);
                break;
                case 'attachment' :
					$a_href_url = get_attachment_link($attachment->ID);
                break;
                case 'custom' :
                    $a_href_url = get_post_meta($post->ID, 'att_custom_link', true);
				break;
                case 'large_image' :
	                $resized = image_get_intermediate_size($attachment->ID, 'large');
                    if($resized !== false){
                        $a_href_url = str_replace(basename($img_url), $resized['file'], $img_url);
                    }
                break;
			}
			
			$a_href = '<a href="' . $a_href_url . '" ' .  $a_href_rel .' title="' . $a_title_text . '">%%%</a>';
			
		}
		if(isset($a_href) && !empty($a_href)) { //If they wanted a link put the img tag into it.
			$image = str_replace('%%%', $image, $a_href);
		}
	
	}
	
	if($echo === true || $echo == 'true') //Echo it?
		echo $image;
	else //Ok we'll return it instead.
		return $image;
	
}

function have_attached_image($query = false) {
	global $wp_query;
	
	if($query !== false) {
		$post = $query->post;
	} else {
		$post = $wp_query->post;
	}
	
	if(function_exists('wp_enqueue_style')) {
		$attachments = get_children(array('post_parent' => $post->ID,
										  'post_status' => 'inherit',
										  'post_type' => 'attachment',
									 	  'post_mime_type' => 'image',
										  'order' => 'ASC',
										  'orderby' => 'menu_order ID'));
	} else { 
		//WP2.5 Compat...
		$attachments = get_children('post_parent='.$post->ID.'&post_type=attachment&post_mime_type=image&orderby="menu_order ASC, ID ASC"');
	};
	
	if(empty($attachments)) {
		
		return false;
		
	} else {
		
		return true;
		
	}
	
}

function create_default_image($link, $link_title, $width, $height, $href, $post, $default, $css_class, $alt, $img_tag) {
	
	if($pic_meta = get_post_meta($post->ID, 'att_default_pic', true)) {
		$default = $pic_meta;
	} elseif($default === false) {
		return false;
	}
	
	// Thanks to Eduardo Gonzalez for the bug report & fix. Fix needs a little modification due to unforseen bug. 
	if($img_tag === true || $img_tag == 'true')	{
		//Do they want an image tag along with setting the height & width.
		$image = '<img src="'.get_bloginfo('url').$default.'" class="'.$css_class.'" ';
	} else {
		$image = get_bloginfo('url').$default;
		return $image;
	}
	// End of fix
	
	//get the alt text
	$attachment = '';
	$alt_text = get_alt($alt, $attachment, $default);
	if(!empty($alt_text)) {
		$image .= 'alt="'.$alt_text.'" ' ;
	}
			
	if(stristr($link, 'post') === false && stristr($link, 'custom') === false) {
		//get the title text
		$img_title_text = get_title($link_title, $attachment, $default);
		if(!empty($title_text)) {
			$image .= 'title="'.$img_title_text.'" ';
		}
	}
	
	if($height === false && $width === false) { //Sort out the height & width depending on what has been supplied by the user.
		//Get the image size using ABSPATH. Suppresion of errors via @ is not expensive despite what you have heard. It's the generation of the error.
		$default_info = @getimagesize(substr(ABSPATH,0,-1).$default); 
		$image .= !empty($default_info[3]) ? $default_info[3].' />' : ' />'; 
	} else {
		if(!$width === false && !$height === false) {
			$image .= 'width="'.$width.'" height="'.$height.'" />'; 
		} elseif(!$width === false) {
			$image .= 'width="'.$width.'" />';
		} elseif(!$height === false) {
			$image .= 'height="'.$height.'" />';
		}
	}
	
	if($href === true || $href == 'true') { //Do you want a href & where should it point.
		switch ($link) {
			case 'post' :
				$a_href = '<a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">%%%</a>';
			break;
			case 'custom' :
				$link_meta = get_post_meta($post->ID, 'att_custom_link', true); //no need to check since it wouldn't be here if it were empty.
				$a_href = '<a href="'.$link_meta.'">%%%</a>';
			break;
		}
	}
	
	if(isset($a_href) && !empty($a_href)) { //If they wanted a link put the img tag into it.
		$image = str_replace('%%%', $image, $a_href);
	}
		
	return $image;
	
}

function get_title($link_title, $attachment, $default) {
	global $post;

	switch($link_title) {
		case 'image-name' :
			if(!empty($attachment->post_title)) {//if this is a default image we won't be able to use the $attachments object
				$title_text = $attachment->post_title; 
			} else {
				$parts = pathinfo($default); //use the filename instead
				$title_text = $parts['filename'];
			}
		break;
		case 'image-description' :
			if(empty($attachment->post_content) && empty($attachment->post_title)) { //If we cant find both then it's the default again
				$parts = pathinfo($default); //use the filename instead
				$title_text = $parts['filename'];
			} else {
				$title_text = (!empty($attachment->post_content)) ? $attachment->post_content : $attachment->post_title;
			}
		break;
		case 'post-title' :
			$title_text = $post->post_title;
		break;
		case 'post-slug' :
			$title_text = $post->post_name;
		break;
		case 'custom' :
			//if it doesn't match any of those it must be custom.
			$title_text = str_replace('"', '', get_post_meta($post->ID, 'att_custom_link_title', true));
		break;
	}	
	  
	return $title_text;
}

function get_alt($alt, $attachment, $default) {
	global $post;
	
	switch($alt) {
		case 'image-name' :
			if(empty($attachment->post_title)) {
				$parts = pathinfo($default);
				$alt_text = $parts['filename'];
			} else {
				$alt_text = $attachment->post_title;
			}
		break;
		case 'image-description' :
			if(empty($attachment->post_content) && empty($attachment->post_title)) { //If we cant find both then it's the default again
				$parts = pathinfo($default); //use the filename instead
				$alt_text = $parts['filename'];
			} else {
				$alt_text = (!empty($attachment->post_content)) ? $attachment->post_content : $attachment->post_title;
			}
		break;
		case 'post-title' :
			$alt_text = $post->post_title;
		break;
		case 'post-slug' :
			$alt_text = $post->post_name;
		break;
		case 'custom' :
			//if it doesn't match any of those it must be custom.
			$alt_text = str_replace('"', '', get_post_meta($post->ID, 'att_custom_alt', true));
		break;
	}
	
	return $alt_text;
}
?>