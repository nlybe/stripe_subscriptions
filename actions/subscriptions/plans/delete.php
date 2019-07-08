<?php

access_show_hidden_entities(true);

$guid = get_input('guid');
$entity = get_entity($guid);

if ($entity instanceof SiteSubscriptionPlan) {
	$plan_id = $entity->getPlanId();

	if ($entity->delete()) {
		// When the plan is deleted, also remove it from Stripe 
		$stripe = new StripeClient();
		$stripe->deletePlan($plan_id);

		return elgg_ok_response('', elgg_echo('subscriptions:plans:delete:success'), REFERER);
	}
} else {
	return elgg_error_response(elgg_echo('subscriptions:plans:delete:error'));
}

forward(REFERER);