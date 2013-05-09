<?php
/*
Plugin Name: Theme switcher
Plugin URI: http://seeyourimpact.org/
Version: 1.0
Author: Steve Eisner
Description: Allows us to run both the new and the old home page theme
*/

class WP_Theme_Switcher {
  public $stylesheet;
  public $template;
 
  public function __construct() {
  }
 
  public function get_template($theme){
    return $this->template;
  }
 
  public function get_stylesheet($theme){
    return $this->stylesheet;
  }

  public function is_new_theme() {
    return $_REQUEST['style'] == 'new' || $_COOKIE['style'] == 'new';
  }

  public function handle_new_theme() {
    if (!isset($_REQUEST['qqq']))
      return;
 
    if ($_REQUEST['qqq'] == 'on') { 
      $val = 'new';
    } else {
      $val = 'old';
    }

    unset($_COOKIE['style']);
    $setcookie = setcookie('style', $val, time()+3600, '/',
      ".".str_replace(array("https://","http://","/"),"",get_bloginfo('url')));
    $_COOKIE['style'] = $val;
  }
 
  public function switch_theme(){
    global $blog_id;
    if ($blog_id != 1)
      return;
 
    $this->handle_new_theme();
    if ($this->is_new_theme()) {
      $this->template = "roots";
      $this->stylesheet = "new";
    } else {
      $this->template = "syi";
      $this->stylesheet = "syi-home";
      $url = $_SERVER['SCRIPT_URL'];
      $switch = array( "/beta/", "/xgiving/");
      foreach ($switch as $u) {
        if ($url == $u) {
          $this->template = "roots";
          $this->stylesheet = "new";
          break;
        }
      }
    }

    if ($this->template != "syi") {
      add_filter( 'template', array(&$this, 'get_template') );
      add_filter( 'stylesheet', array(&$this, 'get_stylesheet') );
    }
  }
}
 
$wp_theme_switcher = new WP_Theme_Switcher();
add_action('setup_theme', array(&$wp_theme_switcher,'switch_theme'));
