<?php
	
	// Ensure we are logged in
	gatekeeper();
		
	// Get the GUID of the user to friend
	$friend_guid = (int) get_input('friend');
	
	$errors = false;

	// Get the user
	if ($friend = get_user($friend_guid)) {
		$user = elgg_get_logged_in_user_entity();
		
		try{
			$user->removeFriend($friend->getGUID());
			
			try {	
				//V1.1 - Old relationships might not have the 2 as friends...
				$friend->removeFriend($user->getGUID());
			}catch(Exception $e) {
				// do nothing
			}
		} catch (Exception $e) {
			register_error(elgg_echo("friends:remove:failure", array($friend->name)));
			$errors = true;
		}
	} else {
		register_error(elgg_echo("friends:remove:failure", array($friend_guid)));
		$errors = true;
	}
	
	if(!$errors) {
		system_message(elgg_echo("friends:remove:successful", array($friend->name)));
	}			
		
	forward(REFERER);
	