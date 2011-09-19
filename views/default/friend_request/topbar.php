<?php
	/**
	 * Friend request plugin
	 * Extend the Elgg topbar with link to Friends request, if any
	 * 
	 * @package friend_request
	 * @author ColdTrick IT Solutions
	 * @copyright Coldtrick IT Solutions 2009
	 * @link http://www.coldtrick.com/
	 * @version 2.0
	 */

	if($user = get_loggedin_user()){
		
		$options = array(
			"type" => "user",
			"relationship" => "friendrequest",
			"relationship_guid" => $user->getGUID(),
			"inverse_relationship" => true,
			"count" => true
		);
		
		if($count = elgg_get_entities_from_relationship($options)){
			echo elgg_view("output/url", array("href" => $vars["url"] . "pg/friend_request", "text" => "[" . $count . "]", "class" => "new_friendrequests"));
		}
	}
?>