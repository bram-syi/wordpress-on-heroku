<?php
/*
Plugin Name: SEO No Duplicate
Plugin URI: http://omninoggin.com/wordpress-plugins/seo-no-duplicate-wordpress-plugin/
Description: This plugin helps you manage your search engine duplicate content, by setting your post page's canonical to the permalink.
Version: 0.5.0
Author: Thaya Kareeson
Author URI: http://omninoggin.com
*/

/*
Copyright 2009-2010 Thaya Kareeson (email : thaya.kareeson@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!class_exists('SEONoDuplicate')) {

  class SEONoDuplicate {

    var $author_homepage = 'http://omninoggin.com/';
    var $homepage = 'http://omninoggin.com/wordpress-plugins/seo-no-duplicate-wordpress-plugin/';
    var $name = 'seo_no_duplicate';
    var $name_dashed = 'seo-no-duplicate';
    var $name_proper = 'SEO No Duplicate';
    var $required_wp_version = '2.7';
    var $version = '0.5.0';

    var $c = null;

    function SEONoDuplicate() {
      // initialize common functions
      $this->c = new SEONoDuplicateCommon($this);

      // load translation
      $this->c->load_text_domain();

      // remove default 2.9+ canonical settings
      remove_action('wp_head', 'rel_canonical');

      // set canonical
      add_action('wp_head', array($this, 'set_canonical'));

      // register admin scripts
      add_action('admin_init', array($this->c, 'a_register_scripts'));
      add_action('admin_init', array($this->c, 'a_register_styles'));

      // check wp version
      add_action('admin_head', array($this->c, 'a_check_version'));

      // load admin menu
      add_action('admin_menu', array($this, 'a_menu'));

      // post options
      add_action('edit_form_advanced', array($this, 'a_post_options'));
      add_action('edit_page_form', array($this, 'a_post_options'));
      add_action('publish_post', array($this, 'a_store_post_options'));
      add_action('publish_page', array($this, 'a_store_post_options'));
      add_action('save_post', array($this, 'a_store_post_options'));

      // advertise hook
      if (!is_admin()) {
        add_action('wp_footer', array($this, 'advertise'));
      }
    }

    // admin functions

    function a_default_options() {
      return array(
        'version' => $this->version,
        'show_link' => false
      );
    }

    function a_post_options() {
      global $post;
      echo '<div class="postbox">
        <h3>'.$this->name_proper.'</h3>
        <div class="inside">
        <p>'.__('Override this post\'s canonical URL with: ', $this->name).'
        ';
      $canonical_override = get_post_meta($post->ID, 'canonical', true);
      if ( !empty($canonical_override) ) {
        $link = $canonical_override;
      }
      else {
        $link = '';
      }
      echo '<input type="text" name="snd_canonical" id="snd_canonical" size=60 value="',$link,'" />';
      echo '
        </p>
        </div><!--.inside-->
        </div><!--.postbox-->
      ';
    }

    function a_store_post_options($post_id) {
      if (!isset($_POST['snd_canonical']) || empty($_POST['snd_canonical'])) {
        delete_post_meta($post_id, 'canonical');
        return;
      }
      $post = get_post($post_id);
      if (!$post || $post->post_type == 'revision') {
        return;
      }
      update_post_meta($post_id, 'canonical', $_POST['snd_canonical']);
    }

    function a_update_options() {
      $snd_new_options = stripslashes_deep($_POST['snd_options']);

      // convert "on" to true and "off" to false for checkbox fields
      // and set defaults for fields that are left blank
      if ( isset($snd_new_options['show_link']) && $snd_new_options['show_link'] == "off")
        $snd_new_options['show_link'] = false;
      else
        $snd_new_options['show_link'] = true;

      // Update options
      $snd_options = get_option($this->name);
      foreach($snd_new_options as $key => $value) {
        $snd_options[$key] = $value;
      }
      update_option($this->name, $snd_options);
    }

    function a_request_handler() {
      if ( isset($_POST['snd_options_submit']) ) {
        check_admin_referer($this->name);
        $this->a_update_options();
        add_action('admin_notices', array($this->c, 'a_notify_updated'));
      }
    }

    function a_menu() {
      $options_page = add_options_page($this->name_proper, $this->name_proper, 'manage_options', $this->name, array($this, 'a_page'));
      add_action('admin_head-'.$options_page, array($this, 'a_request_handler'));
      add_action('admin_print_scripts-'.$options_page, array($this->c, 'a_enqueue_scripts'));
      add_action('admin_print_styles-'.$options_page, array($this->c, 'a_enqueue_styles'));
    }

    function a_page() {
      $snd_options = get_option($this->name);
      echo(
        '<div class="wrap">'.
          '<h2>'.$this->name_proper.' '.__('Options', $this->name).'</h2>'.
          '<div>'.
            '<a href="'.preg_replace('/&snd-page=[^&]*/', '', $_SERVER['REQUEST_URI']).'">'.__('General Configuration', $this->name).'</a>&nbsp;|&nbsp;'.
            '<a href="'.$this->homepage.'">'.__('Documentation', $this->name).'</a>'.
          '</div>'.
          '<div style="clear:both;"></div>'.
          '<div class="omni_admin_main">'
      );

      if(isset($_GET['snd-page'])) {
        if($_GET['snd-page'] == 'generic') {
          require_once('options-generic.php');
        }
      }
      else {
        require_once('options-generic.php');
      }

      echo(
          '</div>'
      );

      require_once('options-sidebar.php');

      echo(
        '</div>'
      );
    }

    // other functions

    function set_canonical() {
      global $wp_query;
    
      // Shamelessly ripped [and slightly modified] from Joost De Valk's Canonical plugin, http://yoast.com/wordpress/canonical/
      if ($wp_query->is_404 || $wp_query->is_search) {
        return false;
      }
    
      $haspost = count($wp_query->posts) > 0;
      $has_ut = function_exists('user_trailingslashit');
    
      if (get_query_var('m')) {
        // Handling special case with '?m=yyyymmddHHMMSS'
        // Since there is no code for producing the archive links for
        // is_time, we will give up and not try to produce a link.
        $m = preg_replace('/[^0-9]/', '', get_query_var('m'));
        switch (strlen($m)) {
          case 4: // Yearly
            $link = get_year_link($m);
            break;
          case 6: // Monthly
            $link = get_month_link(substr($m, 0, 4), substr($m, 4, 2));
            break;
          case 8: // Daily
            $link = get_day_link(substr($m, 0, 4), substr($m, 4, 2), substr($m, 6, 2));
            break;
          default:
            return false;
        }
      } elseif (($wp_query->is_single || $wp_query->is_page) && $haspost) {
        $post = $wp_query->posts[0];
        $canonical_override = get_post_meta($post->ID, 'canonical', true);
        if ( !empty($canonical_override) ) {
          $link = $canonical_override;
        }
        else {
          $link = get_permalink($post->ID);
          $page = get_query_var('paged');
          if ($page && $page > 1) {
            $link = trailingslashit($link) . "page/". "$page";
            if ($has_ut) {
              $link = user_trailingslashit($link, 'paged');
            } else {
              $link .= '/';
            }
          }
          // WP2.2: In Wordpress 2.2+ is_home() returns false and is_page() 
          // returns true if front page is a static page.
          if ($wp_query->is_page && ('page' == get_option('show_on_front')) && 
            $post->ID == get_option('page_on_front'))
          {
            $link = trailingslashit($link);
          }
        }
      } elseif ($wp_query->is_author && $haspost) {
        global $wp_version;
        if ($wp_version >= '2') {
          $author = get_userdata(get_query_var('author'));
          if ($author === false)
            return false;
          $link = get_author_link(false, $author->ID,
            $author->user_nicename);
        } else {
          // XXX: get_author_link() bug in WP 1.5.1.2
          //    s/author_nicename/user_nicename/
          global $cache_userdata;
          $userid = get_query_var('author');
          $link = get_author_link(false, $userid,
            $cache_userdata[$userid]->user_nicename);
        }
      } elseif ($wp_query->is_category && $haspost) {
        $link = get_category_link(get_query_var('cat'));
      } elseif ($wp_query->is_day && $haspost) {
        $link = get_day_link(get_query_var('year'),
                   get_query_var('monthnum'),
                   get_query_var('day'));
      } elseif ($wp_query->is_month && $haspost) {
        $link = get_month_link(get_query_var('year'),
                     get_query_var('monthnum'));
      } elseif ($wp_query->is_year && $haspost) {
        $link = get_year_link(get_query_var('year'));
      } elseif ($wp_query->is_home) {
        // WP2.1: Handling "Posts page" option. In WordPress 2.1 is_home() 
        // returns true and is_page() returns false if home page has been 
        // set to a page, and we are getting the permalink of that page 
        // here.
        if ((get_option('show_on_front') == 'page') &&
          ($pageid = get_option('page_for_posts'))) 
        {
          $link = trailingslashit(get_permalink($pageid));
        } else {
          $link = trailingslashit(get_option('home'));
        }
      } else {
        return;
      }
    
      echo '<link rel="canonical" href="'.$link.'"/>';
    }

    function advertise() {
      $sed_options = get_option($this->name);
      if ($sed_options['show_link']) {
        printf("<p align='center'><small>Canonical URL by <a href='$this->homepage' title='$this->name_proper WordPress plugin' style='text-decoration:none;'>$this->name_proper</a> <a href='$this->author_homepage' title='WordPress Plugin' style='text-decoration:none;'>WordPress Plugin</a></small></p>");
      }
    }

  } // class SEONoDuplicate

} // if !class_exists

require_once('common.php');

if (class_exists('SEONoDuplicate')) {
  $seo_no_duplicate = new SEONoDuplicate();
}
?>
