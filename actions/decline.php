<?php
	
$friend_guid = (int) get_input('guid');

$friend = get_user($friend_guid);
if (empty($friend)) {
	register_error(elgg_echo('error:missing_data'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

if (!remove_entity_relationship($friend->getGUID(), 'friendrequest', $user->getGUID())) {
	register_error(elgg_echo('friend_request:decline:fail'));
	forward(REFERER);
}

$subject = elgg_echo('friend_request:decline:subject', [$user->name]);
$message = elgg_echo('friend_request:decline:message', [$friend->name, $user->name]);

$params = [
	'action' => 'friend_request_decline',
	'object' => $user,
];

notify_user($friend->getGUID(), $user->getGUID(), $subject, $message, $params);

system_message(elgg_echo('friend_request:decline:success'));

forward(REFERER);
