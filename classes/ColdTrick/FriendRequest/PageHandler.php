<?php

namespace ColdTrick\FriendRequest;

class PageHandler {
	
	/**
	 * Handle /friend_request pages
	 *
	 * @param array $page the url segments
	 *
	 * @return bool
	 */
	public static function friendRequest($page) {
		
		$include_file = false;
		$pages_path = elgg_get_plugins_path() . 'friend_request/pages/';
		
		if (isset($page[0])) {
			set_input('username', $page[0]);
			
			$include_file = "{$pages_path}index.php";
		}
		
		if (!empty($include_file)) {
			include($include_file);
			return true;
		}
		
		return false;
	}
}
