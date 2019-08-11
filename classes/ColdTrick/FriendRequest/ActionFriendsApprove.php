<?php

namespace ColdTrick\FriendRequest;

use ElggUser;
use Elgg\BadRequestException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Exception;
use NotificationException;

class ActionFriendsApprove {

	public function __invoke(Request $request) {

		$friend_guid = (int) $request->getParam('friend_guid');
		$user_guid = (int) $request->getParam('user_guid');
		
		$friend = get_user($friend_guid);
		$user = get_user($user_guid);
		
		if (!$friend instanceof ElggUser || !$user instanceof ElggUser) {
			return;
		}
		
		// remove friend from user
		try {
			remove_entity_relationship($friend->guid, 'friendrequest', $user->guid);
			
			$user->addFriend($friend->guid);
			$friend->addFriend($user->guid); //Friends mean reciprocal...

			// notify the user about the acceptance
			$subject = elgg_echo('friend_request:approve:subject', [$user->getDisplayName()], $friend->language);
			$message = elgg_echo('friend_request:approve:message', [$friend->getDisplayName(), $user->getDisplayName()], $friend->language);

			$params = [
				'action' => 'add_friend',
				'object' => $user,
			];
			notify_user($friend->guid, $user->guid, $subject, $message, $params);

			// add to river
			friend_request_create_river_events($user->guid, $friend->guid);
			
			return elgg_ok_response('', elgg_echo('friend_request:approve:successful', [$friend->getDisplayName()]));
			
		} catch (Exception $e) {
			return elgg_error_response(
				elgg_echo('friend_request:approve:fail', [$friend->getDisplayName()]),
				REFERER,
				$e->getCode() ? : ELGG_HTTP_INTERNAL_SERVER_ERROR
			);
		}

	}
}
