<?php

$entities = elgg_extract('entities', $vars, false);

$content = '';

if (!empty($entities)) {
	
	$lis = [];
	
	foreach ($entities as $entity) {
		$icon = elgg_view_entity_icon($entity, 'small');
		
		$info = elgg_view('output/url', [
			'href' => $entity->getURL(),
			'text' => $entity->name,
			'is_trusted' => true,
		]);
		$info .= '<br />';
		$info .= elgg_view('output/url', [
			'href' => "action/friend_request/revoke?guid={$entity->getGUID()}",
			'text' => elgg_echo('friend_request:revoke'),
			'is_action' => true,
		]);
		
		$lis[] = elgg_format_element('li', ['class' => 'elgg-item elgg-item-user'], elgg_view_image_block($icon, $info));
	}
	
	$content = elgg_format_element('ul', ['class' => 'elgg-list elgg-list-entity'], implode('', $lis));
} else {
	$content = elgg_echo('friend_request:sent:none');
}

echo elgg_view_module('info', elgg_echo('friend_request:sent:title'), $content, ['class' => 'mbm']);
