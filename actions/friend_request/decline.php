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
	remove_entity_relationship($friend->guid, 'friendrequest', $user->guid);

	$subject = elgg_echo('friend_request:decline:subject', [$user->getDisplayName()], $friend->language);
	$message = elgg_echo('friend_request:decline:message', [$friend->getDisplayName(), $user->getDisplayName()], $friend->language);

	$params = [
		'action' => 'friend_request_decline',
		'object' => $user,
	];

	notify_user($friend->guid, $user->guid, $subject, $message, $params);

	return elgg_ok_response('', elgg_echo('friend_request:decline:success'));

} catch (Exception $e) {
	return elgg_error_response(
		elgg_echo('friend_request:decline:success'),
		REFERER,
		$e->getCode() ? : ELGG_HTTP_INTERNAL_SERVER_ERROR
	);
}
