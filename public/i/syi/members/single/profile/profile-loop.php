<?


do_action( 'bp_before_profile_loop_content' );

if ( function_exists('xprofile_get_profile') ) {

/*
	if ( bp_has_profile() ) {
    while ( bp_profile_groups() ) {
      bp_the_profile_group();
			if ( bp_profile_group_has_fields() ) {
				do_action( 'bp_before_profile_field_content' );

        ?><div class="bp-widget <? bp_the_profile_group_slug() ?>"><?

        if ( 1 != bp_get_the_profile_group_id() ) {
          ?><h4><? bp_the_profile_group_name() ?></h4><?
        }

        while ( bp_profile_fields() ) {
          bp_the_profile_field(); 
          if( bp_get_the_profile_field_name() != 'About Me')
            continue;
               
          if (bp_field_has_data())  {
            global $field;
            $val = stripslashes(bp_unserialize_profile_field( $field->data->value ));

            echo wpautop($val);
          } else if (bp_is_my_profile()) {
            ?><p><em>You have not yet created a public donor profile.</em></p><?
          } else {
            ?><p><em>This member has not made a public donor profile.</em></p><?
          }
        }
        ?></div><?

			  do_action( 'bp_after_profile_field_content' );
      }
	  }
    do_action( 'bp_profile_field_buttons' );

  }
*/
} else {
	/* Just load the standard WP profile information, if BP extended profiles are not loaded. */ 
	bp_core_get_wp_profile();

}

do_action( 'bp_after_profile_loop_content' );

?>
