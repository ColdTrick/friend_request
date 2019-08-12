<?php

namespace ColdTrick\FriendRequest;

use Elgg\Includer;
use Elgg\PluginBootstrap;

class Bootstrap extends PluginBootstrap {
	
	/**
	 * Get plugin root
	 * @return string
	 */
	protected function getRoot() {
		return $this->plugin->getPath();
	}
	
	public function load() {
		Includer::requireFileOnce($this->getRoot() . '/lib/functions.php');
	}

	/**
	 * {@inheritdoc}
	 */
	public function boot() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		//Menus
		$hooks = $this->elgg()->hooks;
		$hooks->registerHandler('register', 'menu:topbar', '\ColdTrick\FriendRequest\TopbarMenu::register');
		$hooks->registerHandler('register', 'menu:page', '\ColdTrick\FriendRequest\PageMenu::registerCleanup', 900);
		$hooks->registerHandler('register', 'menu:page', '\ColdTrick\FriendRequest\PageMenu::register');
		$hooks->registerHandler('register', 'menu:entity', '\ColdTrick\FriendRequest\Users::registerEntityMenu');
		
		// Events
		$this->elgg()->events->unregisterHandler('create', 'relationship', '_elgg_send_friend_notification');
		$this->elgg()->events->registerHandler('create', 'relationship', '\ColdTrick\FriendRequest\Relationships::createFriendRequest');
	}

	/**
	 * {@inheritdoc}
	 */
	public function ready() {
		elgg_unregister_plugin_hook_handler('register', 'menu:user_hover', '_elgg_friends_setup_user_hover_menu');
		elgg_register_plugin_hook_handler('register', 'menu:user_hover', '\ColdTrick\FriendRequest\Users::registerUserHoverMenu');
	}

	/**
	 * {@inheritdoc}
	 */
	public function shutdown() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function activate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function deactivate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function upgrade() {

	}
}