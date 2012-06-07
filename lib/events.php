<?php

	//Allow us to send an notification email:
	function friend_request_event_create_friendrequest($event, $object_type, $object) {
		
		if (($object instanceof ElggRelationship)) {
			$user_one = get_user($object->guid_one);
			$user_two = get_user($object->guid_two);
				
			$view_friends_url = elgg_get_site_url() . "friend_request/" . $user_two->username;
				
			// Notify target user
			$subject = elgg_echo('friend_request:newfriend:subject', array($user_one->name));
			$message = elgg_echo("friend_request:newfriend:body", array($user_one->name, $view_friends_url));
				
			return notify_user($object->guid_two, $object->guid_one, $subject, $message);
		}
	}