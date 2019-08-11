<?php

namespace ColdTrick\FriendRequest;

use Elgg\Hook;
use ElggMenuItem;

class Users {
	
	/**
	 * Add menu items to the entity menu
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value the current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerEntityMenu(Hook $hook) {
		$return_value = $hook->getValue();
		$params = $hook->getParams();
		
		if (empty($params) || !is_array($params)) {
			return;
		}
		
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		if (!$entity instanceof \ElggUser) {
			return;
		}
		
		if ($entity->guid === $user->guid) {
			// looking at yourself
			return;
		}

		
		// are we friends
		if (!$entity->isFriendOf($user->guid)) {
			// no, check if pending request
			if (check_entity_relationship($user->guid, 'friendrequest', $entity->guid)) {
				// pending request
				$return_value[] = \ElggMenuItem::factory([
					'name' => 'friend_request',
					'text' => elgg_echo('friend_request:friend:add:pending'),
					'href' => "friend_request/{$user->username}#friend_request_sent_listing",
					'icon' => 'user',
					'priority' => 500,
				]);
			} else {
				// add as friend
				$return_value[] = \ElggMenuItem::factory([
					'name' => 'add_friend',
					'href' => elgg_generate_action_url('friends/add', [
						'friend' => $entity->guid,
					]),
					'text' => elgg_echo('friend:add'),
					'icon' => 'user-plus',
					'priority' => 500,
				]);
			}
		} else {
			// is friend, se remove friend link
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'remove_friend',
				'href' => elgg_generate_action_url('friends/remove', [
					'friend' => $entity->guid,
				]),
				'text' => elgg_echo('friend:remove'),
				'icon' => 'user-times',
				'priority' => 500,
			]);
		}
		
		return $return_value;
	}
	
	/**
	 * Add menu items to the hover menu
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value the current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerUserHoverMenu(Hook $hook) {
		$return_value = $hook->getValue();
		$params = $hook->getParams();
		
		if (empty($params) || !is_array($params)) {
			return;
		}
	
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		if (!$entity instanceof \ElggUser) {
			return;
		}
		
		if ($entity->guid === $user->guid) {
			// looking at yourself
			return;
		}
		
		$requested = check_entity_relationship($user->guid, 'friendrequest', $entity->guid);
		$is_friend = $entity->isFriend($user->guid);
		
		foreach ($return_value as $index => $item) {
			// change the text of the button to tell you already requested a friendship
			switch ($item->getName()) {
				case 'add_friend':
					if ($requested) {
						$item->setItemClass('hidden');
					}
					
					break;
				case 'remove_friend':
					if (!$requested && !$is_friend) {
						unset($return_value[$index]);
					}
					break;
			}
		}
		
		if ($requested) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'friend_request',
				'text' => elgg_echo('friend_request:friend:add:pending'),
				'icon' => 'user',
				'href' => "friend_request/{$user->username}#friend_request_sent_listing",
				'section' => 'action',
			]);
		}
		
		return $return_value;
	}
}
