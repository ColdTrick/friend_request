<?php
	
	require_once(dirname(__FILE__) . "/lib/events.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");

	// Default event handlers
	elgg_register_event_handler("init", "system", "friend_request_init");
	elgg_register_event_handler("pagesetup", "system", "friend_request_pagesetup");
	
	function friend_request_init() {
		// extend css
		elgg_extend_view("css/elgg", "css/friend_request/site");
		
		// Page handlers
		// unregister friendsof
		elgg_unregister_page_handler("friendsof");
		//This will let users view their friend requests
		elgg_register_page_handler('friend_request', 'friend_request_page_handler');
		
		// Events
		// unregister default elgg friend handler
		elgg_unregister_event_handler("create", "friend", "relationship_notification_hook");
		// Handle our add action event
		elgg_register_event_handler("create", "friendrequest", "friend_request_event_create_friendrequest");
		
		// Plugin hooks
		elgg_register_plugin_hook_handler("register", "menu:user_hover", "friend_request_user_menu_handler");
		elgg_register_plugin_hook_handler("register", "menu:entity", "friend_request_entity_menu_handler");
		
		// Actions
		// This overwrites the original friend requesting stuff.
		elgg_register_action("friends/add", dirname(__FILE__) . "/actions/friends/add.php");
		// We need to override the friend remove action to remove the relationship we created
	   	elgg_register_action("friends/remove", dirname(__FILE__) . "/actions/friends/removefriend.php");
		// friend request actions
	   	elgg_register_action("friend_request/approve", dirname(__FILE__) . "/actions/approve.php");
	   	elgg_register_action("friend_request/decline", dirname(__FILE__) . "/actions/decline.php");
	   	elgg_register_action("friend_request/revoke", dirname(__FILE__) . "/actions/revoke.php");
	   	
	}
	
	function friend_request_page_handler($page) {
		
		if(isset($page[0])){
			set_input("username", $page[0]);
		}
		
		include(dirname(__FILE__) . "/pages/index.php"); 
		return true;
	}
	
	function friend_request_pagesetup(){
		
		$context = elgg_get_context();
		$page_owner = elgg_get_page_owner_entity();
		
		// Remove link to friendsof
		elgg_unregister_menu_item("page", "friends:of");
		
		if($user = elgg_get_logged_in_user_entity()){
			$options = array(
				"type" => "user",
				"count" => true,
				"relationship" => "friendrequest",
				"relationship_guid" => $user->getGUID(),
				"inverse_relationship" => true
			);
			
			if($count = elgg_get_entities_from_relationship($options)){
				$params = array(
					"name" => "friend_request",
					"href" => "friend_request/" . $user->username,
					"text" => elgg_view_icon("user") . "<span class='friend-request-new'>" . $count . "</span>",
					"title" => elgg_echo("friend_request:menu"),
					"priority" => 301
				);
				
				elgg_register_menu_item("topbar", $params);
			}
		}
		
		// Show menu link in the correct context
		if(in_array($context, array("friends", "friendsof", "collections", "messages")) && !empty($page_owner) && $page_owner->canEdit()){
			$options = array(
				"type" => "user",
				"count" => true,
				"relationship" => "friendrequest",
				"relationship_guid" => $page_owner->getGUID(),
				"inverse_relationship" => true
			);
			
			if($count = elgg_get_entities_from_relationship($options)){
				$extra = " [" . $count . "]";
			} else {
				$extra = "";
			}
			
			// add menu item
			$menu_item = array(
				"name" => "friend_request",
				"text" => elgg_echo("friend_request:menu") . $extra,
				"href" => "friend_request/" . $page_owner->username,
				"contexts" => array("friends", "friendsof", "collections", "messages"),
				"section" => "friend_request"
			);
			
			elgg_register_menu_item("page", $menu_item);
		}
	}
	