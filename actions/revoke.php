<?php
	/**
	 * Friend request plugin
	 * Revoke a friend request
	 * 
	 * @package friend_request
	 * @author ColdTrick IT Solutions
	 * @copyright Coldtrick IT Solutions 2009
	 * @link http://www.coldtrick.com/
	 * @version 2.0
	 */

	gatekeeper();
	
	$friend_guid = (int) get_input("guid");
	$friend = get_user($friend_guid);
	
	if(!empty($friend)){
		$user = get_loggedin_user();
		
		if(remove_entity_relationship($user->guid, 'friendrequest', $friend->guid)) {
			system_message(elgg_echo("friend_request:revoke:success"));
		} else {
			system_message(elgg_echo("friend_request:revoke:fail"));
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
?>