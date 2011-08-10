<?php
	/**
	 * Friend request plugin
	 * Requires a friend to confirm your request to become a connection
	 * 
	 * Original design by Bosssumon and Zac Hopkinson
	 * 
	 * @package friend_request
	 * @author ColdTrick IT Solutions
	 * @copyright Coldtrick IT Solutions 2009
	 * @link http://www.coldtrick.com/
	 * @version 2.1
	 */

	function friend_request_init() {
		//Extend CSS
		elgg_extend_view('css', 'friend_request/css');
		
		if(isloggedin()){
			//Extend topbar to add our link if needed
			elgg_extend_view('elgg_topbar/extend', 'friend_request/topbar');
		}
		
		//This overwrites the original friend requesting stuff.
		register_action("friends/add", false, dirname(__FILE__) . "/actions/friends/add.php");
		
		//We need to override the friend remove action to remove the relationship we created
	   	register_action("friends/remove", false, dirname(__FILE__) . "/actions/friends/removefriend.php");
		
		//This will let uesrs view their friend requests
		register_page_handler('friend_request', 'friend_request_page_handler');
	}
	
	function friend_request_event_create_friend($event, $object_type, $object) {
		global $CONFIG;
			
		if (($object instanceof ElggRelationship) && ($event == 'create') && ($object_type == 'friend') ) {
			//We don't want anything happening here... (no email/etc)
			
			//Returning false will interrupt the rest of the chain.
			//The normal handler for the create friend event has a priority of 500 so it will never be called.	
			return false;
		}
		return true; //Shouldn't get here...
	}
	
	//Allow us to send an notification email:
	function friend_request_event_create_friendrequest($event, $object_type, $object) {
		global $CONFIG;
			
		if (($object instanceof ElggRelationship) && ($event == 'create') && ($object_type == 'friendrequest')) {
			$user_one = get_entity($object->guid_one);
			$user_two = get_entity($object->guid_two);
			
			$view_friends_url = $CONFIG->wwwroot . "pg/friend_request";
			
			// Notify target user
			$subject = sprintf(elgg_echo('friend_request:newfriend:subject'), $user_one->name);
			$message = sprintf(elgg_echo("friend_request:newfriend:body"), $user_one->name, $view_friends_url);
			
			return notify_user($object->guid_two, $object->guid_one, $subject, $message); 
		}
	}
	
	function friend_request_page_handler($page) {
		global $CONFIG;
		
		include($CONFIG->pluginspath . "friend_request/index.php"); 
	}
	
	function friend_request_pagesetup(){
		global $CONFIG;
		
		// Remove link to friendsof
		if(!empty($CONFIG->submenu["a"])){
			$submenu_links = &$CONFIG->submenu["a"];
			$lang_friendsof = elgg_echo("friends:of");
			
			foreach($submenu_links as $index => $object){
				if($object->name == $lang_friendsof){
					unset($submenu_links[$index]);
					break;
				}
			}
		}
		
		// Show menu link in the correct context
		if(isloggedin() && 
			(get_context() == "friends" ||
			get_context() == "friendsof" ||
			get_context() == "collections" ||
			get_context() == "messages")
		){
			$options = array(
				"type" => "user",
				"count" => true,
				"relationship" => "friendrequest",
				"relationship_guid" => get_loggedin_userid(),
				"inverse_relationship" => true
			);
			
			if($count = elgg_get_entities_from_relationship($options)){
				$extra = " [" . $count . "]";
			} else {
				$extra = "";
			}
			
			add_submenu_item(elgg_echo("friend_request:menu") . $extra, $CONFIG->wwwroot . "pg/friend_request/", "b");
		}
		
	}
	
	// Default event handlers
	register_elgg_event_handler('init', 'system', 'friend_request_init', 100);
	register_elgg_event_handler('pagesetup', 'system', 'friend_request_pagesetup');
	
	//Our friendrequest handlers...
	register_action("friend_request/approve", false, $CONFIG->pluginspath . "friend_request/actions/approve.php");
   	register_action("friend_request/decline", false, $CONFIG->pluginspath . "friend_request/actions/decline.php");
   	register_action("friend_request/revoke", false, $CONFIG->pluginspath . "friend_request/actions/revoke.php");
   	
   	//Regular Elgg engine sends out an email via an event. The 400 priority will let us run first.
	//Then we return false to stop the event chain. The normal event handler will never get to run.
	register_elgg_event_handler('create', 'friend', 'friend_request_event_create_friend', 400);
	
	//Handle our add action event:
	register_elgg_event_handler('create', 'friendrequest', 'friend_request_event_create_friendrequest');
	
?>