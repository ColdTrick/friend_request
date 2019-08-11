<?php

namespace ColdTrick\FriendRequest;

use Elgg\Hook;
use ElggMenuItem;

class TopbarMenu {
	
	/**
	 * Add menu items to the topbar
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value the current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(Hook $hook) {
		$return_value = $hook->getValue();
		
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$options = [
			'type' => 'user',
			'count' => true,
			'relationship' => 'friendrequest',
			'relationship_guid' => $user->guid,
			'inverse_relationship' => true,
		];
	
		$count = elgg_get_entities($options);
		if (empty($count)) {
			return;
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'friend_request',
			'href' => "friend_request/{$user->username}",
			'text' => elgg_echo('friend_request:menu'),
			'icon' => 'user',
			'badge' => $count ? $count : null,
			'title' => elgg_echo('friend_request:menu'),
			'priority' => 301
		]);
		
		return $return_value;
	}
}
