<?php

$request = elgg_extract('request', $vars);
/* @var $request \Elgg\Request */

$username = $request->getParam('username');
if (!$username) {
	throw new \Elgg\EntityNotFoundException();
}

if ($username) {
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user instanceof ElggUser || !$user->canEdit()) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

// set the correct context and page owner
elgg_push_context('friends');

// breadcrumb
elgg_push_breadcrumb(elgg_echo('friends'), "friends/{$user->username}");
elgg_push_breadcrumb(elgg_echo('friend_request:menu'));

// Get page elements
$title = elgg_echo('friend_request:title', [$user->getDisplayName()]);

$content = elgg_view('friend_request/received');
$content .= elgg_view('friend_request/sent');

// Build page
$body = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'filter' => false,
]);

// Draw page
echo elgg_view_page($title, $body);
	