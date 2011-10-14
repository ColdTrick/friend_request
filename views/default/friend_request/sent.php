<?php 
	
	$entities = $vars["entities"];
	
	$content = "";
	
	if(!empty($entities)){
		foreach($entities as $entity){
			$icon = elgg_view_entity_icon($entity, "small");
			
			$info = elgg_view("output/url", array("href" => $entity->getURL(), "text" => $entity->name));
			$info .= "<br />";
			$info .= elgg_view("output/url", array("href" => $vars["url"] . "action/friend_request/revoke?guid=" . $entity->getGUID(), 
													"text" => elgg_echo("friend_request:revoke"), 
													"is_action" => true));
			
			$content .= elgg_view_image_block($icon, $info);
		}
	} else {
		$content = elgg_echo("friend_request:sent:none");
	}
	
	echo elgg_view_module("aside", elgg_echo("friend_request:sent:title"), $content, array("id" => "friend_request_sent_listing"));
	