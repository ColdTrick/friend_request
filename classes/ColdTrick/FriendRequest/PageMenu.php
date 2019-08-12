<?php

namespace ColdTrick\FriendRequest;

use Elgg\Hook;
use ElggMenuItem;

class PageMenu {
	
	/**
	 * Remove menu items from the page menu
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value the current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerCleanup(Hook $hook) {
		
		$return_value = $hook->getValue();

		$remove_items = [
			'friends:of',
		];
		foreach ($return_value as $index => $menu_item) {
			if (!in_array($menu_item->getName(), $remove_items)) {
				continue;
			}
			
			unset($return_value[$index]);
		}
		
		return $return_value;

	}
	
	/**
	 * Add menu items to the page menu
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
		
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser) {
			return;
		}
		
		if (!$page_owner->canEdit()) {
			return;
		}
		
		// count pending requests
		$options = [
			'type' => 'user',
			'count' => true,
			'relationship' => 'friendrequest',
			'relationship_guid' => $page_owner->guid,
			'inverse_relationship' => true,
		];
		
		$count = elgg_get_entities($options);
		
		// add menu item
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'friend_request',
			'text' => elgg_echo('friend_request:menu'),
			'href' => "friend_request/{$page_owner->username}",
			'badge' => $count ? $count : null,
			'contexts' => ['friends', 'friendsof', 'collections', 'messages'],
		]);
		
		return $return_value;
	}
		
}
