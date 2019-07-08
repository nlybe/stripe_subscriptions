<?php

$guid = get_input('guid', false);
$user = get_entity($guid);

$plan_guid = get_input('plan_guid');
$plan = get_entity($plan_guid);

if (!$user) {
	return elgg_error_response(elgg_echo('subscriptions:membership:plan:error:no_user'));
}

if (!$plan instanceof SiteSubscriptionPlan || !$plan->isMembershipPlan()) {
	return elgg_error_response(elgg_echo('subscriptions:membership:plan:error:no_plan'));
}

$stripe_token = get_input('stripe-token');
stripe_create_card($user->guid, $stripe_token);

if (stripe_subscriptions_subscribe_to_plan($user->guid, $plan->guid)) {
	return elgg_ok_response('', elgg_echo('subscriptions:membership:plan:success'), 'subscriptions');
} else {
	return elgg_error_response(elgg_echo('subscriptions:membership:plan:error'));
}

forward('subscriptions');

