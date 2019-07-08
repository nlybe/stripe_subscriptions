<?php

$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

$guid = elgg_extract('guid', $vars);
// $entity = elgg_extract('entity', $vars);
$entity = get_entity($guid);
$container = elgg_extract('container', $vars);

$required = (!$entity) ? elgg_format_attributes(array(
			'title' => elgg_echo('required'),
			'class' => 'required'
		)) : '';

if ($entity instanceof SiteSubscriptionPlan) {
	$title = $entity->title;
	$product_id = $entity->product_id;
	$description = $entity->description;
	$plan_id = $entity->getPlanId();
	$plan_type = $entity->getPlanType();
	$cycle = $entity->getCycle()->getCycleName();
	$amount = $entity->getPricing()->getAmount();
	$currency = $entity->getPricing()->getCurrency();
	$trial_period_days = $entity->getTrialPeriodDays();
	$role_name = $entity->getRole();
}
?>

<div>
	<label <?php echo $required ?>><?php echo elgg_echo('subscriptions:plans:plan_type') ?></label>
	<div><?php echo elgg_echo('subscriptions:plans:plan_type:help') ?></div>
	<?php
	echo elgg_view_field([
		'#type' => 'dropdown',
		'name' => 'plan_type',
		'value' => elgg_extract('plan_type', $vars, $plan_type),
		'options_values' => array(
			SiteSubscriptionPlan::PLAN_TYPE_MEMBERSHIP => elgg_echo('subscriptions:plans:plan_type:membership'),
			SiteSubscriptionPlan::PLAN_TYPE_SERVICE => elgg_echo('subscriptions:plans:plan_type:service'),
		),
		'required' => true,
	]);
	?>
</div>


<?php
echo elgg_view_field([
	'#type' => 'text',
	'name' => 'product_id',
	'value' => elgg_extract('product_id', $vars, $product_id),
	'required' => true,
	'#label' => elgg_echo('subscriptions:plans:product_id'),
	'#help' => elgg_echo('subscriptions:plans:product_id:help'),
	'parsley-trigger' => 'keyup focusout',
	'parsley-validation-minlength' => 1,
	'parsley-minlength' => 1,
]);

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'title',
	'value' => elgg_extract('title', $vars, $title),
	'required' => true,
	'#label' => elgg_echo('subscriptions:plans:title'),
	'parsley-trigger' => 'keyup focusout',
	'parsley-validation-minlength' => 1,
	'parsley-minlength' => 1,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'name' => 'description',
	'value' => elgg_extract('description', $vars, $description),
	'#label' => elgg_echo('subscriptions:plans:description'),
]);

echo elgg_view('input/stripe/price', array(
	'name' => 'amount',
	'value' => elgg_extract('amount', $vars, $amount),
	'required' => true,
	'disabled' => ($entity->guid),
	'#label' => elgg_echo('subscriptions:plans:amount'),
	'#help' => elgg_echo('subscriptions:plans:amount:help'),
));

echo elgg_view('input/stripe/currency', array(
	'name' => 'currency',
	'value' => elgg_extract('currency', $vars, $currency),
	'required' => true,
	'disabled' => ($entity->guid),
	'#label' => elgg_echo('subscriptions:plans:currency'),
	'#help' => elgg_echo('subscriptions:plans:currency:help'),
));

echo elgg_view('input/stripe/cycle', array(
	'name' => 'cycle',
	'value' => elgg_extract('cycle', $vars, $cycle),
	'required' => true,
	'disabled' => ($entity->guid),
	'#label' => elgg_echo('subscriptions:plans:cycle'),
	'#help' => elgg_echo('subscriptions:plans:cycle:help'),
));

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'trial_period_days',
	'value' => elgg_extract('trial_period_days', $vars, $trial_period_days),
	'required' => true,
	'disabled' => ($entity->guid),
	'#label' => elgg_echo('subscriptions:plans:trial_period_days'),
	'#help' => elgg_echo('subscriptions:plans:trial_period_days:help'),
]);

$roles_dropdown_options = array(0 => elgg_echo('subscriptions:plans:roles:select'));
if (elgg_is_active_plugin('roles')) {
	$roles = roles_get_all_selectable_roles();

	foreach ($roles as $role) {
		$roles_dropdown_options[$role->name] = $role->getDisplayName();
	}

	echo elgg_view_field([
		'#type' => 'dropdown',
		'name' => 'role',
		'value' => elgg_extract('role', $vars, $role_name),
		'options_values' => $roles_dropdown_options,
		'#label' => elgg_echo('subscriptions:plans:roles:provide'),
		'#help' => elgg_echo('subscriptions:plans:roles:provide:help'),
	]);
	
} else {
	echo elgg_view_field([
		'#type' => 'text',
		'name' => 'role',
		'value' => elgg_extract('role', $vars, $role_name),
		'#label' => elgg_echo('subscriptions:plans:tier'),
		'#help' => elgg_echo('subscriptions:plans:tier:help'),
	]);
}
?>

<div class="elgg-foot columns text-right">
	<?php
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'access_id',
		'value' => ($entity->guid) ? $entity->access_id : ACCESS_PUBLIC,
		'required' => true,
	]);
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => elgg_extract('guid', $vars, $entity->guid),
	]);
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'container_guid',
		'value' => elgg_extract('container_guid', $vars, $container->guid),
	]);
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'scope',
		'value' => elgg_extract('scope', $vars, $entity->scope),
	]);

	echo elgg_view_field([
		'#type' => 'submit',
		'value' => elgg_echo('save')
	]);
	?>
</div>

<?php
access_show_hidden_entities($ha);