<?php
	/**
	 * Friend request plugin
	 * Decline a friend request and notify requester
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
		
		if(remove_entity_relationship($friend->guid, 'friendrequest', $user->guid)) {
			$subject = sprintf(elgg_echo("friend_request:decline:subject"), $user->name);
			$message = sprintf(elgg_echo("friend_request:decline:message"), $friend->name, $user->name);
			
			notify_user($friend->guid, $user->guid, $subject, $message);
			
			system_message(elgg_echo("friend_request:decline:success"));
		} else {
			system_message(elgg_echo("friend_request:decline:fail"));
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
?>