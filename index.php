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
	
	// Get all the data
	$received_count = get_entities_from_relationship("friendrequest", $user->guid, true, "user", "", 0, "", 0, 0, true);
	$received_requests = get_entities_from_relationship("friendrequest", $user->guid, true, "user", "", 0, "", $received_count);
	
	$sent_count = get_entities_from_relationship("friendrequest", $user->guid, false, "user", "", 0, "", 0, 0, true);
	$sent_requests = get_entities_from_relationship("friendrequest", $user->guid, false, "user", "", 0, "", $sent_count);
	
	// Get page elements
	$title = elgg_view_title(elgg_echo('friend_request:title'));
	
	$received = elgg_view("friend_request/received", array("entities" => $received_requests, "request_count" => $received_count));
	$sent = elgg_view("friend_request/sent", array("entities" => $sent_requests, "request_count" => $sent_count));
	
	// Build page
	$page_body =  $title . $received . $sent;
	
	// Draw page
	page_draw(elgg_echo("friend_request:title"), elgg_view_layout('two_column_left_sidebar', '', $page_body));
	
?>