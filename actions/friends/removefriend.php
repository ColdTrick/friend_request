<?php
	
// Get the GUID of the user to friend
$friend_guid = (int) get_input('friend');

$friend = get_user($friend_guid);
$user = elgg_get_logged_in_user_entity();

if (!$friend instanceof ElggUser || !$user instanceof ElggUser) {
	return;
}

// remove friend from user
try {
	$user->removeFriend($friend->guid);
	
	// remove river items
	elgg_delete_river([
		'view' => 'river/relationship/friend/create',
		'subject_guid' => $user->guid,
		'object_guid' => $friend->guid,
	]);

} catch (Exception $e) {
	return elgg_error_response(
		elgg_echo('friends:remove:failure', [$friend->getDisplayName()]),
		REFERER,
		$e->getCode() ? : ELGG_HTTP_INTERNAL_SERVER_ERROR
	);
}

// remove user from friend
try {
	// V1.1 - Old relationships might not have the 2 as friends...
	$friend->removeFriend($user->guid);
	
	// remove river items
	elgg_delete_river([
		'view' => 'river/relationship/friend/create',
		'subject_guid' => $friend->guid,
		'object_guid' => $user->guid,
	]);
	
	return elgg_ok_response('', elgg_echo('friends:remove:successful', [$friend->getDisplayName()]));
} catch (Exception $e) {
	// do nothing
}
