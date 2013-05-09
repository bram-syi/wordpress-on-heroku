<?php
function bp_core_register_widgets_deprecated() {
	add_action('widgets_init', create_function('', 'return register_widget("BP_Core_Welcome_Widget");') );
}
add_action( 'bp_register_widgets', 'bp_core_register_widgets_deprecated' );

/*** WELCOME WIDGET *****************/

class BP_Core_Welcome_Widget extends WP_Widget {
	function bp_core_welcome_widget() {
		parent::WP_Widget( false, $name = __( 'Welcome', 'buddypress' ) );
	}

	function widget($args, $instance) {
		extract( $args );
	?>
		<?php echo $before_widget; ?>
		<?php echo $before_title
			. $instance['title']
			. $after_title; ?>

		<?php if ( $instance['title'] ) : ?><h3><?php echo attribute_escape( stripslashes( $instance['title'] ) ) ?></h3><?php endif; ?>
		<?php if ( $instance['text'] ) : ?><p><?php echo apply_filters( 'bp_core_welcome_widget_text', $instance['text'] ) ?></p><?php endif; ?>

		<?php if ( !is_user_logged_in() ) { ?>
		<div class="create-account"><div class="visit generic-button"><a href="<?php bp_signup_page() ?>" title="<?php _e('Create Account', 'buddypress') ?>"><?php _e('Create Account', 'buddypress') ?></a></div></div>
		<?php } ?>

		<?php echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = strip_tags( wp_filter_post_kses( $new_instance['text'] ) );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags( $instance['title'] );
		$text = strip_tags( $instance['text'] );
		?>
			<p><label for="bp-widget-welcome-title"><?php _e('Title:', 'buddypress'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo attribute_escape( stripslashes( $title ) ); ?>" /></label></p>
			<p>
				<label for="bp-widget-welcome-text"><?php _e( 'Welcome Text:' , 'buddypress'); ?>
					<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="widefat" style="height: 100px"><?php echo attribute_escape( stripslashes( $text ) ); ?></textarea>
				</label>
			</p>
	<?php
	}
}
add_filter( 'bp_core_welcome_widget_text', 'attribute_escape' );
add_filter( 'bp_core_welcome_widget_text', 'wptexturize' );
add_filter( 'bp_core_welcome_widget_text', 'convert_smilies' );
add_filter( 'bp_core_welcome_widget_text', 'convert_chars' );
add_filter( 'bp_core_welcome_widget_text', 'stripslashes' );
add_filter( 'bp_core_welcome_widget_text', 'wpautop' );
add_filter( 'bp_core_welcome_widget_text', 'force_balance_tags' );

?>