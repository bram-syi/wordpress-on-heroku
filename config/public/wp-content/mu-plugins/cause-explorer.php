<?
/*
Plugin Name: Cause Explorer
Plugin URI: http://www.seeyourimpact.org
Description: Adds shortcodes and widgets to the CE
Version: 1.0
Author: Yosia Urip
Author URI: http://www.seeyourimpact.org
*/

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

//Shortcode for facts
function draw_facts_box($args){
  $r = '';
  $r .= '<div id="facts-box">';
  $r .= print_r($args,true);
  $r .= '</div>';

  return $r;
}

add_shortcode('facts-box','draw_facts_box');

//Shortcode for gifts
function draw_gifts_box($args){
  $r = '';
  $r .= '<div id="gifts-box">';
  $r .= print_r($args,true);
  $r .= '</div>';

  return $r;
}

add_shortcode('gifts-box','draw_gifts_box');

////////////////////////////////////////////////////////////////////////////////

function create_article_tag($post_id){
  $post = get_post($post_id);
  wp_set_post_tags($post_id, $post->post_name);
}

add_action('publish_article', 'create_article_tag');


////////////////////////////////////////////////////////////////////////////////

//Cause Explorer Navigation 


function printPosts()
{
    global $post;

    if ($post->post_type == 'article') {
        if ($post->post_parent > 0) {
            $root  = findPathInformation($post);
            $posts = traversePostTree($root['root'], $root['activepath'], $root['depth']);
        } else {
            $root = $post;
            $posts = traversePostTree($root, array($root->ID), 1);
        }
        echo printPostTree($posts);
    }
}

function findPathInformation($post)
{
    // Go up the tree and see what is in the path.
    $reverse_tree = climbPostTree($post);
    // Flattern the tree to get the current active path.
    $activePath = flattenTree($reverse_tree);
    // Make sure current page is in the active path.
    $activePath[] = $post->ID;
    $root = $reverse_tree[0];
    // Set to 2 as if we are in this code we are in the level just below root.
    $depth = 2;
    // Recursivley loop through the pages and find the root page and the depth.
    while (is_array($root->post_parent)) {
       ++$depth;
       $root = $root->post_parent[0];
    }
    return array('root' => $root, 'depth' => $depth, 'activepath' => $activePath);
}

function flattenTree($tree)
{
    $flat = array();
    while (is_array($tree[0]->post_parent)) {
       $flat[] = $tree[0]->ID;
       $tree = $tree[0]->post_parent;
    }
    $flat[] = $tree[0]->ID;
    return $flat;
}

function inActivePath($id, $activePath)
{
    if (in_array($id, $activePath)) {
        return true;
    } else {
        return false;
    }
}

function climbPostTree($post)
{
    global $wpdb;
    $parent = $wpdb->get_results("SELECT ID, post_title, post_parent "
      . "FROM $wpdb->posts "
      . "WHERE post_status = 'publish' AND post_type = 'article' "
      . "AND ID = " . $post->post_parent . " ORDER BY menu_order, post_title", 
      OBJECT);

    if (count($parent) > 0) {
        foreach ($parent as $item => $par) {
            if ($par->post_parent != 0) {
                $parent_parent = climbPostTree($par);
                if ($parent_parent !== false) {
                    $parent[$item]->post_parent = $parent_parent;
                }
            } else {
                // Reached top of tree
                return $parent;
            }
        }
    }
    return $parent;
}

function traversePostTree($post, $activePath = array(), $maxdepth = 10, $depth = 0)
{
    if ($depth >= $maxdepth) {
        // We have reached the maximum depth, stop traversal.
        return array();
    }
    // Get Wordpress db object.
    global $wpdb;
    $children = $wpdb->get_results(
      "SELECT ID, post_title, post_parent FROM $wpdb->posts "
        . "WHERE post_status = 'publish' AND post_type = 'article' "
        . "AND post_parent = " . $post->ID . " "
        . "ORDER BY menu_order, post_title", OBJECT);
    if (count($children) > 0) {
        foreach ($children as $item => $child) {
            if (inActivePath($child->ID, $activePath)) {
                // Current page is in active path, find the children.
                $children[$item]->children = 
                  traversePostTree($child, $activePath, $maxdepth, $depth + 1);
            }
        }
    }
    return $children;
}

function printPostTree($posts)
{
    $class = '';
    $output = '';
    $output .= "\n<ul>\n";
    foreach ($posts as $post) {
        if (is_page($post->ID) === true) {
            $class = ' class="on"';
        }
        $output .=  "<li" . $class . "><a href=\"" . get_page_link($post->ID) 
          . "\" title=\"" . $post->post_title . "\">" 
          . $post->post_title . "</a>";
        $class = '';
        if (isset($post->children) && count($post->children) > 0) {
            $output .= printPostTree($post->children);
        }
        $output .=  "</li>\n";
    }
    $output .=  "</ul>\n";
    return $output;
}

function articleNavigationWidget($args)
{
    extract($args);
    echo printPosts();
}

register_sidebar_widget(__('Article Navigation'), 'articleNavigationWidget');
$wp_registered_widgets[sanitize_title('Article Navigation')]['description'] = 
  'Creates a traversed article navigation.';





?>