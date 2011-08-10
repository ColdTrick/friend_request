<?php
	/**
	 * Friend request plugin
	 * Approve a friend request and make connection both ways
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
			global $CONFIG;
			
			if(isset($CONFIG->events['create']['friend'])) {
				$oldEventHander = $CONFIG->events['create']['friend'];
				$CONFIG->events['create']['friend'] = array();			//Removes any event handlers
			}
			
			$user->addFriend($friend->guid);
			$friend->addFriend($user->guid);			//Friends mean reciprical...
			
			if(isset($CONFIG->events['create']['friend'])) {
				$CONFIG->events['create']['friend'] = $oldEventHander;
			}
			
			system_message(sprintf(elgg_echo('friend_request:approve:successful'), $friend->name));
			// add to river
			add_to_river('friends/river/create', 'friend', $user->guid, $friend->guid);
		} else {
			register_error(sprintf(elgg_echo('friend_request:approve:fail'), $friend->name));
		}
	}
	
	forward($_SERVER['HTTP_REFERER']);
?>