<?php

function bp_friends_is_filtered() {
	if ( isset( $_POST['friend-search-box'] ) )
		return true;

	return false;
}

function bp_friend_last_active() {
	echo bp_get_friend_last_active();
}
	function bp_get_friend_last_active() {
		return bp_get_member_last_active();
	}

function bp_friend_total_for_member() {
	echo bp_get_friend_total_for_member();
}
	function bp_get_friend_total_for_member() {
		return apply_filters( 'bp_get_friend_total_for_member', BP_Friends_Friendship::total_friend_count() );
	}

?>
