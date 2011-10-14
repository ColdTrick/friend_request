<?php

	function friend_request_user_menu_handler($hook, $type, $returnvalue, $params){
		$result = $returnvalue;
		
		if(!empty($params) && is_array($params) && ($user = elgg_get_logged_in_user_entity())){
			$entity = $params["entity"];
			
			if(elgg_instanceof($entity, "user") && ($entity->getGUID() != $user->getGUID())){
				if(check_entity_relationship($user->getGUID(), "friendrequest", $entity->getGUID())){
					foreach($result as &$item){
						if($item->getName() == "add_friend"){
							$item->setText(elgg_echo("friend_request:friend:add:pending"));
							$item->setHref("friend_request/" . $user->username . "#friend_request_sent_listing");
						}
					}
				}
			}
		}
		
		return $result;
	}