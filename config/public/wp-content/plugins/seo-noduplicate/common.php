<?php
if (!class_exists('SEONoDuplicateCommon')) {

  class SEONoDuplicateCommon {

    var $plugin = null;

    function SEONoDuplicateCommon($plugin) {
      $this->p = $plugin;
    }

    // admin methods
  
    function a_check_version() {
      // check WP version
      global $wp_version;
      if (!empty($wp_version) && is_admin() &&
        version_compare($wp_version, $this->p->required_wp_version, "<")
      ) {
        add_action('admin_notices', array($this, 'a_notify_version'));
      }
  
      // check plugin version
      $options = get_option($this->p->name);
      if ($options && array_key_exists('version', $options) && is_admin() &&
        version_compare($options['version'], $this->p->version, "<")
      ) {
        if (method_exists($this->p, 'a_upgrade_options')) {
          // run plugin's upgrade options function if it exists
          $this->p->a_upgrade_options();
        }
        else {
          // else run generic upgrade options function
          $this->a_upgrade_options();
        }
        add_action('admin_notices', array($this, 'a_notify_upgrade'));
      }
    }
  
    function a_notify($message, $error=false) {
      if ( !$error ) {
        echo '<div class="updated fade"><p>'.$message.'</p></div>';
      }
      else {
        echo '<div class="error"><p>'.$message.'</p></div>';
      }
    }
  
    function a_notify_version() {
      global $wp_version;
      $this->a_notify(
        sprintf(__('You are using WordPress version %s.', $this->p->name), $wp_version).' '.
        sprintf(__('%s recommends that you use WordPress %s or newer.', $this->p->name),
          $this->p->name_proper,
          $this->p->required_wp_version).' '.
        sprintf(__('%sPlease update!%s', $this->p->name),
          '<a href="http://codex.wordpress.org/Upgrading_WordPress">', '</a>'),
        true);
    }
  
    function a_notify_updated() {
      $this->a_notify(
        sprintf(__('%s options has been updated.', $this->p->name),
          $this->p->name_proper));
    }
  
    function a_notify_upgrade() {
      $this->a_notify(
        sprintf(__('%s options has been upgraded.', $this->p->name),
          $this->p->name_proper));
    }
  
    function a_notify_reset() {
      $this->a_notify(
        sprintf(__('%s options has been reset.', $this->p->name),
          $this->p->name_proper));
    }
  
    function a_notify_imported() {
      $this->a_notify(
        sprintf(__('%s options imported.', $this->p->name),
          $this->p->name_proper));
    }
  
    function a_notify_import_failed() {
      $this->a_notify(
        sprintf(__('%s options import failed!', $this->p->name),
          $this->p->name_proper), true);
    }
  
    function a_notify_import_failed_missing() {
      $this->a_notify(
        sprintf(__('Did not receive any file to be imported. %s options import failed!', $this->p->name),
          $this->p->name_proper), true);
    }
  
    function a_notify_import_failed_syntax() {
      $this->a_notify(
        sprintf(__('Found syntax errors in file being imported. %s options import failed!', $this->p->name),
          $this->p->name_proper), true);
    }
  
    function a_upgrade_options() {
      $options = get_option($this->p->name);
      if ( !$options ) {
        add_option($this->p->name, $this->p->a_default_options());
      }
      else {
        $default_options = $this->p->a_default_options();
  
        // upgrade regular options
        foreach($default_options as $option_name => $option_value) {
          if(!isset($options[$option_name])) {
            $options[$option_name] = $option_value;
          }
        }
        $options['version'] = $this->p->version;
        update_option($this->p->name, $options);
      }
    }
  
    function a_register_scripts() {
      wp_register_script('omni_common_easy_slider',
        $this->get_plugin_url().'js/easySlider1.5.js', array('jquery'));
    } 
      
    function a_enqueue_scripts() {
      wp_enqueue_script('omni_common_easy_slider');
    } 
    
    function a_register_styles() {
      wp_register_style('omni_common_style_admin',
        $this->get_plugin_url().'css/style-admin.css');
    }   
        
    function a_enqueue_styles() {
      wp_enqueue_style('omni_common_style_admin');
    }
  
    // other methods
  
    // Localization support
    function load_text_domain() {
      // get current language
      $locale = get_locale();
  
      if(!empty($locale)) {
        // locate translation file
        $mofile = $this->get_plugin_dir().'lang/'.str_replace('_', '-', $this->p->name).'-'.$locale.'.mo';
        // load translation
        if(@file_exists($mofile) && is_readable($mofile)) load_textdomain($this->p->name, $mofile);
      }
    }
  
    function get_plugin_dir() {
      return trailingslashit(trailingslashit(WP_PLUGIN_DIR).plugin_basename(dirname(__FILE__)));
    }
  
    function get_plugin_url() {
      return trailingslashit(trailingslashit(WP_PLUGIN_URL).plugin_basename(dirname(__FILE__)));
    }
  
  }

}
?>
