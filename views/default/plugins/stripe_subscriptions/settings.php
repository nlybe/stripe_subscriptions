<?php
/**
 * Elgg Stripe Subscriptions plugin
 * @package stripe_subscriptions
 */

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[require_subscriptions]',
    'value' => $entity->require_subscriptions,
    '#label' => elgg_echo('subscriptions:settings:require_subscriptions'),
    '#help' => elgg_echo('subscriptions:settings:require_subscriptions:help'),
    'options_values' => array(
		false => elgg_echo('option:no'),
		true => elgg_echo('option:yes'),
	),
]);

echo elgg_view_field([
    '#type' => 'dropdown',
    'value' => $entity->require_cards,
	'name' => 'params[require_cards]',
	'#label' => elgg_echo('subscriptions:settings:require_cards'),
    '#help' => elgg_echo('subscriptions:settings:require_cards:help'),
    'options_values' => array(
		false => elgg_echo('option:no'),
		true => elgg_echo('option:yes'),
	)
]);

