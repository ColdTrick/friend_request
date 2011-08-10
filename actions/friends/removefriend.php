<?php
	/**
	 * Friend request plugin
	 * Remove friend connection on both sides
	 * 
	 * @package friend_request
	 * @author ColdTrick IT Solutions
	 * @copyright Coldtrick IT Solutions 2009
	 * @link http://www.coldtrick.com/
	 * @version 2.0
	 */

	// Ensure we are logged in
	gatekeeper();
		
	// Get the GUID of the user to friend
	$friend_guid = (int) get_input('friend');
	$friend = get_user($friend_guid);
	$errors = false;

	// Get the user
	if (!empty($friend)) {
		$user = get_loggedin_user();
		
		try{
			$user->removeFriend($friend_guid);
			
			try {	
				//V1.1 - Old relationships might not have the 2 as friends...
				$friend->removeFriend($user->guid);
			}catch(Exception $e) {
				// do nothing
			}
		} catch (Exception $e) {
			register_error(sprintf(elgg_echo("friends:remove:failure"),$friend->name));
			$errors = true;
		}
	} else {
		register_error(sprintf(elgg_echo("friends:remove:failure"),$friend_guid));
		$errors = true;
	}
	
	if(!$errors) {
		system_message(sprintf(elgg_echo("friends:remove:successful"),$friend->name));
	}			
		
	forward($_SERVER["HTTP_REFERER"]);	
?>