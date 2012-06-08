<?php

	function friend_request_user_menu_handler($hook, $type, $returnvalue, $params){
		$result = $returnvalue;
		
		if(!empty($params) && is_array($params) && ($user = elgg_get_logged_in_user_entity())){
			$entity = elgg_extract("entity", $params);
			
			if(elgg_instanceof($entity, "user") && ($entity->getGUID() != $user->getGUID())){
				if(check_entity_relationship($user->getGUID(), "friendrequest", $entity->getGUID())){
					foreach($result as &$item){
						// change the text of the button to tell you already requested a friendship
						if($item->getName() == "add_friend"){
							$item->setText(elgg_echo("friend_request:friend:add:pending"));
							$item->setHref("friend_request/" . $user->username . "#friend_request_sent_listing");
							
							break;
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	function friend_request_entity_menu_handler($hook, $type, $returnvalue, $params){
		$result = $returnvalue;
		
		if(!empty($params) && is_array($params) && ($user = elgg_get_logged_in_user_entity())){
			$entity = elgg_extract("entity", $params);
			
			if(elgg_instanceof($entity, "user") && ($entity->getGUID() != $user->getGUID())){
				if(!empty($result) && !is_array($result)){
					$result = array($result);
				} elseif(empty($result)){
					$result = array();
				}
				
				// are we friends
				if(!$entity->isFriendOf($user->getGUID())){
					// no, check if pending request
					if(check_entity_relationship($user->getGUID(), "friendrequest", $entity->getGUID())){
						// pending request
						$result[] = ElggMenuItem::factory(array(
							 "name" => "friend_request",
							 "text" => elgg_echo("friend_request:friend:add:pending"),
							 "href" => "friend_request/" . $user->username . "#friend_request_sent_listing",
							 "priority" => 500
						));
					} else {
						// add as friend
						$result[] = ElggMenuItem::factory(array(
							 "name" => "add_friend",
							 "text" => elgg_echo("friend:add"),
							 "href" => "action/friends/add?friend=" . $entity->getGUID(),
							 "is_action" => true,
							 "priority" => 500
						));
					}
				} else {
					// is friend, se remove friend link
					$result[] = ElggMenuItem::factory(array(
						"name" => "remove_friend",
						"text" => elgg_echo("friend:remove"),
						"href" => "action/friends/remove?friend=" . $entity->getGUID(),
						"is_action" => true,
						"priority" => 500
					));
				}
			}
		}
		
		return $result;
	}