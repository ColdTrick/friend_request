<?php

namespace ColdTrick\FriendRequest;

use ElggUser;
use Elgg\BadRequestException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Exception;

class ActionFriendsAdd {

	public function __invoke(Request $request) {

		$friend_guid = (int) $request->getParam('friend');
		$friend = get_user($friend_guid);
		
		$user = elgg_get_logged_in_user_entity();
		
		if (!$friend instanceof ElggUser || !$user instanceof ElggUser) {
			return elgg_error_response(elgg_echo('friend_request:add:failure'));
		}

		if (check_entity_relationship($friend->guid, 'friend', $user->guid)) {
			try {
				$user->addFriend($friend->guid);
				return elgg_ok_response('', elgg_echo('friends:add:successful', [$friend->getDisplayName()]));
			} catch (Exception $e) {
				return elgg_error_response(
					elgg_echo('friends:add:failure', [$friend->getDisplayName()]),
					REFERER,
					$e->getCode() ? : ELGG_HTTP_INTERNAL_SERVER_ERROR
				);
			}
		}
		
		else if (check_entity_relationship($friend->guid, 'friendrequest', $user->guid)) {
			// Check if your potential friend already invited you, if so make friends
			if (remove_entity_relationship($friend->guid, 'friendrequest', $user->guid)) {
				
				// Friends mean reciprical...
				$user->addFriend($friend->guid);
				$friend->addFriend($user->guid);
				
				// add to river
				friend_request_create_river_events($user->guid, $friend->guid);
				
				return elgg_ok_response('', elgg_echo('friend_request:approve:successful', [$friend->getDisplayName()]));
			} else {
				return elgg_error_response(elgg_echo('friend_request:approve:fail', [$friend->getDisplayName()]));
			}
		}
		
		else {
			if(!add_entity_relationship($user->guid, 'friendrequest', $friend->guid)) {
				return elgg_ok_response('', elgg_echo('friend_request:add:exists', [$friend->getDisplayName()]));
			}
			else {
				return elgg_ok_response('', elgg_echo('friend_request:add:successful', [$friend->getDisplayName()]));
			}
		}
	}
}
