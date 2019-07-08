<?php

$subscription_id = get_input('subscription_id');
$customer_id = get_input('customer_id');

$user = stripe_get_user_from_customer_id($customer_id);

if (!elgg_instanceof($user) || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('stripe:access_error'));
}

if (stripe_subscriptions_cancel_subscription($user->guid, $subscription_id)) {
	return elgg_ok_response('', elgg_echo('subscriptions:cancel:success'), REFERER);
} else {
	return elgg_error_response(elgg_echo('subscriptions:cancel:error'));
}

forward(REFERER);

