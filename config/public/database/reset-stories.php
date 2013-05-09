<?php
require_once( dirname(__FILE__) . '/../wp-load.php' );
require_once( dirname(__FILE__) . '/../wp-admin/includes/taxonomy.php' );
require_once('db-functions.php');

reset_donation_story();
insert_default_featured_posts();

?>
