<?php

$entities = elgg_extract('entities', $vars);

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
			'href' => "action/friend_request/approve?guid={$entity->getGUID()}",
			'text' => elgg_echo('friend_request:approve'),
			'is_action' => true,
		]);
		$info .= '&nbsp;|&nbsp;';
		$info .= elgg_view('output/url', [
			'href' => "action/friend_request/decline?guid={$entity->getGUID()}",
			'text' => elgg_echo('friend_request:decline'),
			'is_action' => true,
		]);
		
		$lis[] = elgg_format_element('li', ['class' => 'elgg-item elgg-item-user'], elgg_view_image_block($icon, $info));
	}
	
	$content = elgg_format_element('ul', ['class' => 'elgg-list elgg-list-entity'], implode('', $lis));
	
} else {
	$content = elgg_echo('friend_request:received:none');
}

echo elgg_view_module('info', elgg_echo('friend_request:received:title'), $content, ['class' => 'mbm']);
