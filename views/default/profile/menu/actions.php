<?php

	/**
	 * Elgg profile icon hover over: actions
	 * 
	 * @package ElggProfile
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

	if ($loggedin_user = get_loggedin_user()) {
		$user = $vars["entity"];
		
		if ($loggedin_user->getGUID() != $user->getGUID()) {
			
			if (friend_request_check_friend_relationship($user->getGUID())) {
				echo "<p class='user_menu_removefriend'>";
				echo elgg_view("output/url", array("href" => $vars["url"] . "action/friends/remove?friend=" . $user->getGUID(), "text" => elgg_echo("friend:remove"), "is_action" => true));
				echo "</p>";
			} else {
				echo "<p class='user_menu_addfriend'>";
				
				if(friend_request_check_friend_request_relationship($user->getGUID())){
					echo elgg_view("output/url", array("href" => $vars["url"] . "pg/friend_request#friend_request_sent_listing", "text" => elgg_echo("friend_request:friend:add:pending")));
				} else {
					echo elgg_view("output/url", array("href" => $vars["url"] . "action/friends/add?friend=" . $user->getGUID(), "text" => elgg_echo("friend:add"), "is_action" => true));
				}
				
				echo "</p>";
			}
		}
	}
