<?php

$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

elgg_make_sticky_form('subscriptions/plans/edit');

$guid = get_input('guid', null);
$container_guid = get_input('container_guid', null);
$title = get_input('title');
$product_id = get_input('product_id');
$description = get_input('description');
$access_id = get_input('access_id', ACCESS_PUBLIC);
$role = get_input('role', 0);
$plan_type = get_input('plan_type');

if (!$title) {
	return elgg_error_response(elgg_echo('subscriptions:plans:edit:error_required_field_empty'));
}

// $entity = new SiteSubscriptionPlan($guid);
$entity = get_entity($guid);

$entity->access_id = $access_id;
$entity->title = $title;
$entity->product_id = $product_id;
$entity->description = $description;
// $entity->setRole($role);
// $entity->setPlanType($plan_type);

if (!$guid) {
	$entity = new SiteSubscriptionPlan;

	$amount = (int) get_input('amount');
	$currency = get_input('currency');
	$cycle = get_input('cycle');
	$trial_period_days = get_input('trial_period_days');

	if ($amount <= 0 || !$currency) {
		return elgg_error_response(elgg_echo('subscriptions:plans:edit:error_required_field_empty'));
	}

	$intervals = StripeBillingCycle::getCycles();
	$interval_options = elgg_extract($cycle, $intervals);
	$interval = $interval_options['interval'];
	$interval_count = $interval_options['interval_count'];

	if (!$interval || !$interval_count) {
		return elgg_error_response(elgg_echo('subscriptions:plans:edit:error_undefined_cycle', array($cycle)));
	}

	$entity->setAmount($amount);
	$entity->setCurrency($currency);
	$entity->setCycle($cycle);
	$entity->setTrialPeriodDays($trial_period_days);

	$plan_id = implode('_', array_filter(array($entity->getPlanType(), $entity->getRole(), $entity->getCycle()->getCycleName())));
	$entity->setPlanId($plan_id);
}
else {
	$entity = get_entity($guid);
}

if ($entity->save()) {
	$entity->setRole($role);
	$entity->setPlanType($plan_type);

	$plan_id = $entity->getPlanId();
	$data = $entity->exportAsStripeArray();

	$stripe = new StripeClient();
	$stripe_plan = $stripe->getPlan($plan_id);

	if (!$stripe_plan) {
		if ($stripe->createPlan($data)) {
			// return elgg_ok_response('', elgg_echo('subscriptions:plans:export:success', array($plan_id)), $entity->getURL());
			system_message(elgg_echo('subscriptions:plans:export:success', array($plan_id)));
		} else {
			$stripe->showErrors();
			return elgg_error_response(elgg_echo('subscriptions:plans:export:error', array($plan_id)));
		}
	} else {
		if (!$stripe->updatePlan($plan_id, $data)) {
			$stripe->showErrors();
		}
	}

	access_show_hidden_entities($ha);

	elgg_clear_sticky_form('subscriptions/plans/edit');
	$forward_url = 'admin/stripe_subscriptions/manage';
	return elgg_ok_response('', elgg_echo('lalala'), $forward_url);	
} else {
	access_show_hidden_entities($ha);
	return elgg_error_response(elgg_echo('subscriptions:plans:edit:error_generic'));
}

forward($forward_url);

