<?php

require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\stripe_subscriptions_init', 700);
elgg_register_event_handler('pagesetup', 'system', __NAMESPACE__ . '\\stripe_subscriptions_pagesetup');

function stripe_subscriptions_init() {

	// elgg_register_page_handler('subscriptions', 'stripe_subscriptions_page_handler');	// OBS

	if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
		elgg_register_menu_item('page', array(
			'name' => 'stripe_subscriptions',
			'href' => '#',
			'text' => elgg_echo('admin:stripe_subscriptions'),
			'context' => 'admin',
			'section' => 'stripe'
		));

		elgg_register_menu_item('page', array(
			'name' => 'stripe_subscriptions:settings',
			'parent_name' => 'stripe_subscriptions',
			'href' => 'admin/plugin_settings/stripe_subscriptions',
			'text' => elgg_echo('admin:stripe_subscriptions:settings'),
			'context' => 'admin',
			'section' => 'stripe',
		));

		elgg_register_menu_item('page', array(
			'name' => 'stripe_subscriptions:create',
			'parent_name' => 'stripe_subscriptions',
			'href' => 'admin/stripe_subscriptions/create',
			'text' => elgg_echo('admin:stripe_subscriptions:create'),
			'context' => 'admin',
			'section' => 'stripe',
		));

		elgg_register_menu_item('page', array(
			'name' => 'stripe_subscriptions:manage',
			'parent_name' => 'stripe_subscriptions',
			'href' => 'admin/stripe_subscriptions/manage',
			'text' => elgg_echo('admin:stripe_subscriptions:manage'),
			'context' => 'admin',
			'section' => 'stripe',
		));

		elgg_register_menu_item('page', array(
			'name' => 'stripe_subscriptions:membership',
			'href' => elgg_generate_url('default:stripe_subscriptions/subscriptions'),
			'text' => elgg_echo('subscriptions:membership:plan'),
			'selected' => (substr_count(current_page_url(), 'subscriptions/membership')),
			'context' => 'settings',
			'section' => 'stripe',
		));
	}
	
	// OBS on Elgg 3 - Registering actions
	// elgg_register_action('subscriptions/plans/delete', __DIR__ . '/actions/subscriptions/plans/delete.php', 'admin');
	// elgg_register_action('subscriptions/plans/sync', __DIR__ . '/actions/subscriptions/plans/sync.php', 'admin');
	// elgg_register_action('subscriptions/plans/edit', __DIR__ . '/actions/subscriptions/plans/edit.php', 'admin');
	// elgg_register_action('subscriptions/plans/manage', __DIR__ . '/actions/subscriptions/plans/manage.php', 'admin');
	// elgg_register_action('subscriptions/membership/plan', __DIR__ . '/actions/subscriptions/membership/plan.php');
	// elgg_register_action('subscriptions/cancel', __DIR__ . '/actions/subscriptions/cancel.php');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'stripe_subscriptions_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:stripe-actions', 'stripe_subscriptions_actions_menu');

	// Exempt pages from routing
	elgg_register_plugin_hook_handler('allowed_pages', 'stripe.subscriptions', 'stripe_subscriptions_allowed_pages');

	// Route users to subscriptions management page
	elgg_register_plugin_hook_handler('route', 'all', 'stripe_subscriptions_router', 5);

	// Exempt admins from subscription requirements
	elgg_register_plugin_hook_handler('require_subscriptions.exempt', 'stripe.subscriptions', 'stripe_subscriptions_exempt_from_subscriptions_requirement');

	// Handle Stripe webhooks
	elgg_register_plugin_hook_handler('customer.subscription.created', 'stripe.events', 'stripe_subscriptions_event_susbscription_updated');
	elgg_register_plugin_hook_handler('customer.subscription.updated', 'stripe.events', 'stripe_subscriptions_event_susbscription_updated');
	elgg_register_plugin_hook_handler('customer.subscription.deleted', 'stripe.events', 'stripe_subscriptions_event_susbscription_deleted');
	elgg_register_plugin_hook_handler('customer.subscription.trial_will_end', 'stripe.events', 'stripe_subscriptions_event_susbscription_trial_ending');

	//elgg_register_event_handler('login', 'user', 'stripe_subscriptions_login_user');
}
