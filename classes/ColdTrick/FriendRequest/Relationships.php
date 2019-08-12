<?php

namespace ColdTrick\FriendRequest;

use Elgg\Event;
use NotificationException;

class Relationships {
	
	/**
	 * Send a notification on a friend request
	 *
	 * @param string            $event        the name of the event
	 * @param string            $type         the type of the event
	 * @param \ElggRelationship $relationship supplied param
	 *
	 * @return void
	 */
	public static function createFriendRequest(Event $event) {
		
		$relationship = $event->getObject();
		
		if (!$relationship instanceof \ElggRelationship) {
			return;
		}
		
		if ($relationship->relationship !== 'friendrequest') {
			return;
		}
		
		$requester = get_user($relationship->guid_one);
		$new_friend = get_user($relationship->guid_two);
		
		if (empty($requester) || empty($new_friend)) {
			return;
		}
		
		// Notify target user
		$subject = elgg_echo('friend_request:newfriend:subject', [$requester->getDisplayName()], $new_friend->language);
		$message = elgg_echo('friend_request:newfriend:body', [
			$requester->getDisplayName(),
			elgg_generate_url('collection:user:user:friend_request', ['username' => $new_friend->username]),
		], $new_friend->language);
		
		$params = [
			'action' => 'friend_request',
			'object' => $requester,
		];
		notify_user($new_friend->guid, $requester->guid, $subject, $message, $params);
	}
}
