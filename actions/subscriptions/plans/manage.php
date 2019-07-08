<?php

$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

$plans = get_input('plans', array());

foreach ($plans as $guid => $options) {

	// $entity = new SiteSubscriptionPlan($guid);
	$entity = get_entity($guid);

	$entity->setPlanType(elgg_extract('plan_type', $options, $entity->getPlanType()));
	$entity->setRole(elgg_extract('role', $options, $entity->role));

	if (!$entity->title) {
		$entity->delete();
		continue;
	}
	
	$enabled = elgg_extract('enabled', $options, 'enabled');

	if ($enabled == 'disabled') {
		$entity->disable("inactive plan");
	} else {
		$entity->enable();
	}

	if (!$entity->save()) {
		$entity->showErrors();
		elgg_error_response(elgg_echo('subscriptions:plans:edit:error_manage', array($entity->title)));
	}
}

access_show_hidden_entities($ha);
forward(REFERER);