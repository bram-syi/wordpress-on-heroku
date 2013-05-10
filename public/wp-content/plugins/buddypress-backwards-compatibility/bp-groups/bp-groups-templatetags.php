<?php

/* Individual group loop functions */
function bp_group_pagination_count() {
	bp_groups_pagination_count();
}

function bp_group_pagination() {
	bp_groups_pagination_links();
}

if ( !function_exists( 'bp_has_site_groups' ) ) {
	/* Site level group loop functions */
	function bp_has_site_groups( $args = '' ) {
		$defaults = array(
			'type' => 'active',
			'page' => 1,
			'per_page' => 20,
			'max' => false,

			'user_id' => false, // Pass a user ID to limit to groups this user has joined
			'slug' => false, // Pass a group slug to only return that group
			'search_terms' => false // Pass search terms to return only matching groups
		);

		$r = wp_parse_args( $args, $defaults );
		return bp_has_groups( $r );
	}

	function bp_site_groups_pagination_count() {
		bp_groups_pagination_count();
	}

	function bp_site_groups_pagination_links() {
		bp_groups_pagination_links();
	}

	function bp_site_groups() {
		return bp_groups();
	}

	function bp_the_site_group() {
		return bp_the_group();
	}

	function bp_the_site_group_link( $group_id = '' ) {
		bp_group_permalink( $group_id );
	}
		function bp_get_the_site_group_link( $group_id = '' ) {
			return bp_get_group_permalink( $group_id );
		}

	function bp_the_site_group_avatar( $args = '' ) {
		bp_group_avatar( $args );
	}
		function bp_get_the_site_group_avatar( $args = '') {
			return bp_get_group_avatar( $args );
		}

	function bp_the_site_group_avatar_thumb( $args = '' ) {
		bp_group_avatar( 'type=thumb' );
	}
		function bp_get_the_site_group_avatar_thumb( $args = '') {
			return bp_get_group_avatar( $args );
		}

	function bp_the_site_group_forum_topic_count( $args = '' ) {
		echo bp_get_group_forum_topic_count( $args );
	}
		function bp_get_the_site_group_forum_topic_count( $args = '' ) {
			return bp_get_group_forum_topic_count( $args );
		}

	function bp_the_site_group_forum_post_count( $args = '' ) {
		echo bp_get_group_forum_post_count( $args );
	}
		function bp_get_the_site_group_forum_post_count( $args = '' ) {
			return bp_get_group_forum_post_count( $args );
		}

	function bp_the_site_group_name() {
		bp_group_name();
	}
		function bp_get_the_site_group_name() {
			return bp_get_group_name();
		}

	function bp_the_site_group_last_active() {
		bp_group_last_active();
	}
		function bp_get_the_site_group_last_active() {
			return bp_get_group_last_active();
		}

	function bp_the_site_group_type() {
		bp_group_type();
	}
		function bp_get_the_site_group_type() {
			return bp_get_group_type();
		}

	function bp_the_site_group_member_count() {
		bp_group_member_count();
	}
		function bp_get_the_site_group_member_count() {
			return bp_get_group_member_count();
		}

	function bp_the_site_group_description() {
		bp_group_description_excerpt();
	}
		function bp_get_the_site_group_description() {
			return bp_get_group_description_excerpt();
		}

	function bp_the_site_group_hidden_fields() {
		if ( isset( $_REQUEST['s'] ) )
			echo '<input type="hidden" id="search_terms" value="' . attribute_escape( $_REQUEST['s'] ) . '" name="search_terms" />';

		if ( isset( $_REQUEST['letter'] ) )
			echo '<input type="hidden" id="selected_letter" value="' . attribute_escape( $_REQUEST['letter'] ) . '" name="selected_letter" />';

		if ( isset( $_REQUEST['groups_search'] ) )
			echo '<input type="hidden" id="search_terms" value="' . attribute_escape( $_REQUEST['groups_search'] ) . '" name="search_terms" />';
	}

	function bp_the_site_group_description_excerpt() {
		echo bp_get_the_site_group_description_excerpt();
	}
		function bp_get_the_site_group_description_excerpt() {
			global $groups_template;

			return apply_filters( 'bp_get_the_site_group_description_excerpt', bp_create_excerpt( bp_get_group_description( $groups_template->group, false ), 25 ) );
		}

	function bp_the_site_group_date_created() {
		echo bp_get_the_site_group_date_created();
	}
		function bp_get_the_site_group_date_created() {
			global $groups_template;

			return apply_filters( 'bp_get_the_site_group_date_created', bp_core_time_since( $groups_template->group->date_created ) );
		}
}

if ( !function_exists( 'bp_new_group_news' ) ) {
	function bp_new_group_news() {
		return false;
	}
}

if ( !function_exists( 'bp_group_has_news' ) ) {
	function bp_group_has_news() {
		return false;
	}
}

if ( !function_exists( 'bp_group_news_editable' ) ) {
	function bp_group_news_editable() {
		return false;
	}
}

if ( !function_exists( 'bp_the_site_group_join_button' ) ) {
	function bp_the_site_group_join_button() {
		global $groups_template;
		echo bp_group_join_button();
	}
}

if ( !function_exists( 'bp_group_show_wire_setting' ) ) {
	function bp_group_show_wire_setting( $group = false ) {
		global $groups_template, $bp, $wpdb;

		if ( !$group )
			$group =& $groups_template->group;

		if ( empty( $group->enable_wire ) )
			$group->enable_wire = $wpdb->get_var( $wpdb->prepare( "SELECT enable_wire FROM {$bp->groups->table_name} WHERE id = %d", $group->id ) );

		if ( $group->enable_wire )
			echo ' checked="checked"';
	}
}

if ( !function_exists( 'bp_group_is_wire_enabled' ) ) {
	function bp_group_is_wire_enabled( $group = false ) {
		global $groups_template, $bp, $wpdb;

		if ( !$group )
			$group =& $groups_template->group;

		if ( empty( $group->enable_wire ) )
			$group->enable_wire = $wpdb->get_var( $wpdb->prepare( "SELECT enable_wire FROM {$bp->groups->table_name} WHERE id = %d", $group->id ) );

		if ( $group->enable_wire )
			return true;

		return false;
	}
}

if ( !function_exists( 'bp_new_group_enable_wire' ) ) {
	function bp_new_group_enable_wire() {
		echo bp_get_new_group_enable_wire();
	}
}

if ( !function_exists( 'bp_get_new_group_enable_wire' ) ) {
	function bp_get_new_group_enable_wire() {
		global $bp;
		return (int) apply_filters( 'bp_get_new_group_enable_wire', $bp->groups->current_group->enable_wire );
	}
}

if ( !function_exists( 'bp_groups_random_selection' ) ) {
	function bp_groups_random_selection() {
		global $bp;

		if ( !$group_ids = wp_cache_get( 'groups_random_groups', 'bp' ) ) {
			$group_ids = BP_Groups_Group::get_random( $total_groups, 1 );
			wp_cache_set( 'groups_random_groups', $group_ids, 'bp' );
		}
	?>
		<?php if ( $group_ids['groups'] ) { ?>
			<ul class="item-list" id="random-groups-list">
			<?php
				for ( $i = 0; $i < count( $group_ids['groups'] ); $i++ ) {
					if ( !$group = wp_cache_get( 'groups_group_nouserdata_' . $group_ids['groups'][$i]->group_id, 'bp' ) ) {
						$group = new BP_Groups_Group( $group_ids['groups'][$i]->group_id, false, false );
						wp_cache_set( 'groups_group_nouserdata_' . $group_ids['groups'][$i]->group_id, $group, 'bp' );
					}
				?>
				<li>
					<div class="item-avatar">
						<a href="<?php echo bp_get_group_permalink( $group ) ?>" title="<?php echo bp_get_group_name( $group ) ?>"><?php echo bp_get_group_avatar_thumb( $group ) ?></a>
					</div>

					<div class="item">
						<div class="item-title"><a href="<?php echo bp_get_group_permalink( $group ) ?>" title="<?php echo bp_get_group_name( $group ) ?>"><?php echo bp_get_group_name( $group ) ?></a></div>
						<div class="item-meta"><span class="activity"><?php echo bp_core_get_last_activity( groups_get_groupmeta( $group->id, 'last_activity' ), __( 'active %s ago', 'buddypress' ) ) ?></span></div>
						<div class="item-meta desc"><?php echo bp_create_excerpt( $group->description ) ?></div>
					</div>

					<div class="action">
						<?php bp_group_join_button( $group ) ?>
						<div class="meta">
							<?php $member_count = groups_get_groupmeta( $group->id, 'total_member_count' ) ?>
							<?php echo ucwords($group->status) ?> <?php _e( 'Group', 'buddypress' ) ?> /
							<?php if ( 1 == $member_count ) : ?>
								<?php printf( __( '%d member', 'buddypress' ), $member_count ) ?>
							<?php else : ?>
								<?php printf( __( '%d members', 'buddypress' ), $member_count ) ?>
							<?php endif; ?>
						</div>
					</div>

					<div class="clear"></div>
				</li>
			<?php } ?>
			</ul>
		<?php } else { ?>
			<div id="message" class="info">
				<p><?php _e( "There aren't enough groups to show a random sample just yet.", 'buddypress' ) ?></p>
			</div>
		<?php } ?>
<?php
	}
}

?>