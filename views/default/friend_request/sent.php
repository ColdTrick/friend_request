<?php 
	/**
	 * Friend request plugin
	 * List all sent requests
	 * 
	 * @package friend_request
	 * @author ColdTrick IT Solutions
	 * @copyright Coldtrick IT Solutions 2009
	 * @link http://www.coldtrick.com/
	 * @version 2.0
	 */

	$count = $vars["request_count"];
	$entities = $vars["entities"];
	
	$content = "";
	
	if($count > 0){
		foreach($entities as $entity){
			$icon = elgg_view("profile/icon", array("entity" => $entity, "size" => "small"));
			
			$info = elgg_view("output/url", array("href" => $entity->getURL(), "text" => $entity->name));
			$info .= "<br />";
			$info .= elgg_view("output/url", array("href" => $vars["url"] . "action/friend_request/revoke?guid=" . $entity->getGUID(), "text" => elgg_echo("friend_request:revoke"), "is_action" => true));
			
			$content .= elgg_view_listing($icon, $info);
		}
	} else {
		$content = elgg_echo("friend_request:sent:none");
	}
	
?>
<div class="contentWrapper" id="friend_request_sent_listing">
	<h3 class="settings"><?php echo elgg_echo("friend_request:sent:title"); ?></h3>
	<?php echo $content; ?>
</div>