<?php

return [
	'friend_request' => "Friend Request",
	'friend_request:menu' => "Friend Requests",
	'friend_request:title' => "Friend Requests for: %s",

	'friend_request:new' => "New friend request",
	
	'friend_request:friend:add:pending' => "Friend request pending",
	
	// plugins settings
	'friend_request:settings:add_river' => "Create river entries when a friend request is accepted",
	
	// notifications
	'friend_request:newfriend:subject' => "%s wants to be your friend!",
	'friend_request:newfriend:body' => "%s wants to be your friend! But they are waiting for you to approve the request...so login now so you can approve the request!

You can view your pending friend requests at:
%s

Make sure you are logged into the website before clicking on the following link otherwise you will be redirected to the login page.

(You cannot reply to this email.)",
		
	// Actions
	// Add request
	'friend_request:add:failure' => "Sorry, because of a system error we were unable to complete your request. Please try again.",
	'friend_request:add:successful' => "You have requested to be friends with %s. They must approve your request before they will show on your friends list.",
	'friend_request:add:exists' => "You've already requested to be friends with %s.",
	
	// Approve request
	'friend_request:approve' => "Approve",
	'friend_request:approve:subject' => "%s has accepted your friend request",
	'friend_request:approve:message' => "Dear %s,

%s has accepted your request to become a friend.",
	'friend_request:approve:successful' => "%s is now a friend",
	'friend_request:approve:fail' => "Error while creating friend relation with %s",

	// Decline request
	'friend_request:decline' => "Decline",
	'friend_request:decline:subject' => "%s has declined your friend request",
	'friend_request:decline:message' => "Dear %s,

%s has declined your request to become a friend.",
	'friend_request:decline:success' => "Friend request successfully declined",
	'friend_request:decline:fail' => "Error while declining Friend request, please try again",
	
	// Revoke request
	'friend_request:revoke' => "Revoke",
	'friend_request:revoke:success' => "Friend request successfully revoked",
	'friend_request:revoke:fail' => "Error while revoking Friend request, please try again",

	// Views
	// Received
	'friend_request:received:title' => "Received Friend requests",
	'friend_request:received:none' => "No requests pending your approval",

	// Sent
	'friend_request:sent:title' => "Sent Friend requests",
	'friend_request:sent:none' => "No sent requests pending approval",
];
