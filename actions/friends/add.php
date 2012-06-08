<?php
	
	//Get our data
	$friend_guid = (int) get_input("friend");
	$friend = get_user($friend_guid);
	
	$user = elgg_get_logged_in_user_entity();
	
	$errors = false;
	
	//Now we need to attempt to create the relationship
	if(empty($user) || empty($friend)) {
		$errors = true;
		register_error(elgg_echo("friend_request:add:failure"));
	} else {
		//New for v1.1 - If the other user is already a friend (fan) of this user we should auto-approve the friend request...
		if(check_entity_relationship($friend->getGUID(), "friend", $user->getGUID())) {
			try {
				if(isset($CONFIG->events["create"]["friend"])) {
					$oldEventHander = $CONFIG->events["create"]["friend"];
					$CONFIG->events["create"]["friend"] = array();			//Removes any event handlers
				}
				
				$user->addFriend($friend->getGUID());
				system_message(elgg_echo("friends:add:successful", array($friend->name)));
				
				if(isset($CONFIG->events["create"]["friend"])) {
					$CONFIG->events["create"]["friend"] = $oldEventHander;
				}
				
				forward(REFERER);
			} catch (Exception $e) {
				register_error(elgg_echo("friends:add:failure", array($friend->name)));
				$errors = true;
			}
		} elseif(check_entity_relationship($friend->getGUID(), "friendrequest", $user->getGUID())){
			// Check if your potential friend already invited you, if so make friends
			if(remove_entity_relationship($friend->getGUID(), "friendrequest", $user->getGUID())){
				if(isset($CONFIG->events["create"]["friend"])) {
					$oldEventHander = $CONFIG->events["create"]["friend"];
					$CONFIG->events["create"]["friend"] = array();			//Removes any event handlers
				}
				
				$user->addFriend($friend->getGUID());
				$friend->addFriend($user->getGUID());			//Friends mean reciprical...
				
				if(isset($CONFIG->events["create"]["friend"])) {
					$CONFIG->events["create"]["friend"] = $oldEventHander;
				}
				
				system_message(elgg_echo("friend_request:approve:successful", array($friend->name)));
				// add to river
				add_to_river("river/relationship/friend/create", "friend", $user->getGUID(), $friend->getGUID());
				add_to_river("river/relationship/friend/create", "friend", $friend->getGUID(), $user->getGUID());
				
				forward(REFERER);
			} else {
				register_error(elgg_echo("friend_request:approve:fail", array($friend->name)));
			}
		} else {
			try {
				$result = add_entity_relationship($user->getGUID(), "friendrequest", $friend->getGUID());
				if($result == false) {
					$errors = true;
					register_error(elgg_echo("friend_request:add:exists", array($friend->name)));
				}
			} catch(Exception $e) {	//register_error calls insert_data which CAN raise Exceptions.
				$errors = true;
				register_error(elgg_echo("friend_request:add:exists", array($friend->name)));
			}
		}
	}
	
	if(!$errors) {
		system_message(elgg_echo("friend_request:add:successful", array($friend->name)));
	}
	
	forward(REFERER);
