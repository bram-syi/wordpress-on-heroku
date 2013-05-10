<?php
/* This Block Adds The My Brand Login To The Menu and Defines The Option and Action Functions of MBL */
  add_action('admin_menu', 'my_brand'); 
    function my_brand() {
add_theme_page('My Brand Login', 'My Brand Login', 'level_10', 'loginbrand', 'login_brand', '../wp-content/plugins/my-brand/loader/images/mybrand.sidebar.png','999998'); 
add_option('mybrandimg', ''); add_option('mybrandbody', ''); add_option('mybrandpic', ''); add_option('mybrandbdytp', ''); add_option('mybrandbaklgin', ''); add_option('mybrandloginnav', ''); add_option('mybrandformposition', ''); add_option('mybrandadminlogo', ''); add_option('mybrandadminbg', ''); 
}
add_action('login_head','my_brand_body'); add_action('login_head','my_brand_js'); add_action('admin_footer','my_brand_admin'); add_action('login_head','my_brand_page'); add_filter('admin_footer_text', 'remove_footer_admin'); add_filter('login_headerurl', 'change_logo_link'); add_filter('login_headertitle', 'change_logo_title'); add_action( 'in_plugin_update_message-' . plugin_basename(__FILE__), 'mybrandlogin_notice' ); add_filter('the_generator', 'mybrandlogin_remove_version');add_filter( 'avatar_defaults', 'mybrand_gravatar' ); add_action('login_head','my_brand_template');

/* End Add The My Brand Login To The Menu and Define The Option and Action Functions of MBL */
?>