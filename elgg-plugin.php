<?php
/**
 * Elgg Stripe Subscriptions plugin
 * @package stripe_subscriptions
 */

return [
    'entities' => [
        [
            'type' => 'object',
            'subtype' => 'site_subscription_plan',
            'class' => 'SiteSubscriptionPlan',
            'searchable' => false,
        ],
    ],
    'actions' => [
        'subscriptions/plans/delete' => ['access' => 'admin'],
        'subscriptions/plans/sync' => ['access' => 'admin'],
        'subscriptions/plans/edit' => ['access' => 'admin'],
        'subscriptions/plans/manage' => ['access' => 'admin'],
        'subscriptions/membership/plan' => [],
        'subscriptions/cancel' => [],
    ],
    'routes' => [
        'default:stripe_subscriptions/subscriptions' => [
            'path' => '/subscriptions/{username?}',
            'resource' => 'subscriptions/membership',
        ],
    ],
    'widgets' => [],
    'views' => [],
    'upgrades' => [],
    'settings' => [],
	
];
