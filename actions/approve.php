<?php

$friend_guid = (int) get_input('friend_guid');
$user_guid = (int) get_input('user_guid');

$friend = get_user($friend_guid);
if (empty($friend)) {
	register_error(elgg_echo('error:missing_data'));
	forward(REFERER);
}

$user = get_user($user_guid);
if (!($user instanceof \ElggUser) || !$user->canEdit()) {
	register_error(elgg_echo('action:unauthorized'));
	forward(REFERER);
}

if (!remove_entity_relationship($friend->getGUID(), 'friendrequest', $user->getGUID())) {
	register_error(elgg_echo('friend_request:approve:fail', [$friend->name]));
	forward(REFERER);
}

$user->addFriend($friend->getGUID());
$friend->addFriend($user->getGUID()); //Friends mean reciprocal...

// notify the user about the acceptance
$subject = elgg_echo('friend_request:approve:subject', [$user->name], $friend->language);
$message = elgg_echo('friend_request:approve:message', [$friend->name, $user->name], $friend->language);

$params = [
	'action' => 'add_friend',
	'object' => $user,
];
notify_user($friend->getGUID(), $user->getGUID(), $subject, $message, $params);

// add to river
friend_request_create_river_events($user->getGUID(), $friend->getGUID());

system_message(elgg_echo('friend_request:approve:successful', [$friend->name]));

forward(REFERER);
