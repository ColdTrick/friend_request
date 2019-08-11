<?php
/**
 * Friend Request
**/
return [
	'bootstrap' => \ColdTrick\FriendRequest\Bootstrap::class,
	'actions' => [
		'friends/add' => [
			'controller' => \ColdTrick\FriendRequest\ActionFriendsAdd::class,
		],
		'friends/remove' => [
			'controller' => \ColdTrick\FriendRequest\ActionFriendsRemove::class,
		],
		'friend_request/approve' => [
			'controller' => \ColdTrick\FriendRequest\ActionFriendsApprove::class,
		],
		'friend_request/decline' => [
			'controller' => \ColdTrick\FriendRequest\ActionFriendsDecline::class,
		],
		'friend_request/revoke' => [
			'controller' => \ColdTrick\FriendRequest\ActionFriendsRevoke::class,
		],
	],
	'routes' => [
		'view:friend_request' => [
			'path' => '/friend_request/{username}',
			'resource' => 'friend_request',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
