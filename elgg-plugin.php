<?php
/**
 * Friend Request
**/
return [
	'bootstrap' => \ColdTrick\FriendRequest\Bootstrap::class,
	'actions' => [
		'friends/add' => [],
		'friends/remove' => [
			'filename' => __DIR__ . '/actions/friends/removefriend.php',
		],
		'friend_request/approve' => [],
		'friend_request/decline' => [],
		'friend_request/revoke' => [],
	],
	'routes' => [
		'collection:user:user:friend_request' => [
			'path' => '/friend_request/{username}',
			'resource' => 'friend_request',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
