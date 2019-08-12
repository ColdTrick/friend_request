<?php
	
$friend_guid = (int) get_input('friend_guid');
$user_guid = (int) get_input('user_guid');

$friend = get_user($friend_guid);
$user = get_user($user_guid);

if (!$friend instanceof ElggUser || !$user instanceof ElggUser) {
	return;
}

// remove friend from user
try {
	remove_entity_relationship($user->guid, 'friendrequest', $friend->guid);
	return elgg_ok_response('', elgg_echo('friend_request:revoke:success'));
} catch (Exception $e) {
	return elgg_error_response(
		elgg_echo('friend_request:revoke:fail', [$friend->getDisplayName()]),
		REFERER,
		$e->getCode() ? : ELGG_HTTP_INTERNAL_SERVER_ERROR
	);
}
