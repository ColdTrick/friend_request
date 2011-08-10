<?php
	/**
	 * Friend request plugin
	 * This action code is based on the regular friend/add action but with a different relationship type
	 * 
	 * @package friend_request
	 * @author ColdTrick IT Solutions
	 * @copyright Coldtrick IT Solutions 2009
	 * @link http://www.coldtrick.com/
	 * @version 2.0
	 */
	
	//Ensure we're logged in
	gatekeeper();
	
	//Get our data
	$friend_guid = (int) get_input('friend');
	$friend = get_user($friend_guid);
	$user_guid = get_loggedin_userid();
	$user = get_loggedin_user();
	
	$errors = false;
	
	//Now we need to attempt to create the relationship
	if(empty($user) || empty($friend)) {
		$errors = true;
		register_error(elgg_echo("friend_request:add:failure"));
	} else {
		//New for v1.1 - If the other user is already a friend (fan) of this user we should auto-approve the friend request...
		if(check_entity_relationship($friend_guid, "friend", $user_guid)) {
			try {
				if(isset($CONFIG->events['create']['friend'])) {
					$oldEventHander = $CONFIG->events['create']['friend'];
					$CONFIG->events['create']['friend'] = array();			//Removes any event handlers
				}
				
				$user->addFriend($friend_guid);
				system_message(sprintf(elgg_echo("friends:add:successful"),$friend->name));
				
				if(isset($CONFIG->events['create']['friend'])) {
					$CONFIG->events['create']['friend'] = $oldEventHander;
				}
				
				forward($_SERVER['HTTP_REFERER']);
			} catch (Exception $e) {
				register_error(sprintf(elgg_echo("friends:add:failure"),$friend->name));
				$errors = true;
			}
		} elseif(check_entity_relationship($friend_guid, "friendrequest", $user_guid)){
			// Check if your potential friend already invited you, if so make friends
			if(remove_entity_relationship($friend_guid, 'friendrequest', $user_guid)){
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
				
				forward($_SERVER['HTTP_REFERER']);
			} else {
				register_error(sprintf(elgg_echo('friend_request:approve:fail'), $friend->name));
			}
		} else {
			try {
				$result = add_entity_relationship($user_guid, "friendrequest", $friend_guid);
				if($result == false) {
					$errors = true;
					register_error(sprintf(elgg_echo("friend_request:add:exists"),$friend->name));
				}
			} catch(Exception $e) {	//register_error calls insert_data which CAN raise Exceptions.
				$errors = true;
				register_error(sprintf(elgg_echo("friend_request:add:exists"),$friend->name));
			}
		}
	}
	
	if(!$errors) {
		system_message(sprintf(elgg_echo("friend_request:add:successful"),$friend->name));
	}
	
	forward($_SERVER['HTTP_REFERER']);

?>