<?php
	/**
	 * Friend request plugin
	 * List all the requests (sent and received)
	 * 
	 * @package friend_request
	 * @author ColdTrick IT Solutions
	 * @copyright Coldtrick IT Solutions 2009
	 * @link http://www.coldtrick.com/
	 * @version 2.1
	 */

	gatekeeper();
	
	$user = get_loggedin_user();
	
	// set the correct context and page owner
	set_context("friends");
	set_page_owner($user->guid);
	
	// fix to show collections links
	collections_submenu_items();
	
	$options = array(
		"type" => "user",
		"limit" => false,
		"relationship" => "friendrequest",
		"relationship_guid" => $user->getGUID(),
		"inverse_relationship" => true
	);
	
	// Get all received requests
	$received_requests = elgg_get_entities_from_relationship($options);
	
	// Get all received requests
	$options["inverse_relationship"] = false;
	$sent_requests = elgg_get_entities_from_relationship($options);
	
	// Get page elements
	$title = elgg_view_title(elgg_echo('friend_request:title'));
	
	$received = elgg_view("friend_request/received", array("entities" => $received_requests));
	$sent = elgg_view("friend_request/sent", array("entities" => $sent_requests));
	
	// Build page
	$page_body =  $title . $received . $sent;
	
	// Draw page
	page_draw(elgg_echo("friend_request:title"), elgg_view_layout('two_column_left_sidebar', '', $page_body));
	