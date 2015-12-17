<?php

elgg_gatekeeper();

$user = elgg_get_page_owner_entity();
if (!($user instanceof ElggUser)) {
	$user = elgg_get_logged_in_user_entity();
	elgg_set_page_owner_guid($user->getGUID());
}

if (!$user->canEdit()) {
	forward(REFERER);
}

// set the correct context and page owner
elgg_push_context('friends');

// breadcrumb
elgg_push_breadcrumb(elgg_echo('friends'), "friends/{$user->username}");
elgg_push_breadcrumb(elgg_echo('friend_request:menu'));

$options = [
	'type' => 'user',
	'limit' => false,
	'relationship' => 'friendrequest',
	'relationship_guid' => $user->getGUID(),
	'inverse_relationship' => true,
];

// Get all received requests
$received_requests = elgg_get_entities_from_relationship($options);

// Get all received requests
$options['inverse_relationship'] = false;
$sent_requests = elgg_get_entities_from_relationship($options);

// Get page elements
$title_text = elgg_echo('friend_request:title', [$user->name]);

$content = elgg_view('friend_request/received', [
	'entities' => $received_requests,
]);
$content .= elgg_view('friend_request/sent', [
	'entities' => $sent_requests,
]);

// Build page
$body = elgg_view_layout('content', [
	'title' => $title_text,
	'content' => $content,
	'filter' => false,
]);

// Draw page
echo elgg_view_page($title_text, $body);
	