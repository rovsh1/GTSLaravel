<?php

return [
    'Administrator' => [
        'path' => 'Administrator'
    ],
    'Catalog' => [
        'path' => 'Catalog'
    ],
    'Client' => [
        'path' => 'Client'
    ],
    'Booking' => [
        'path' => 'Booking',
        'templates_path' => base_path('resources/booking/pdf-templates'),
    ],
    'Supplier' => [
        'path' => 'Supplier',
    ],
    'Pricing' => [
        'path' => 'Pricing',
    ],
    'Logging' => [
        'path' => 'Generic/Logging',
    ],
    'CurrencyRate' => [
        'path' => 'Generic/CurrencyRate',
    ],
    'FileStorage' => [
        'path' => 'Support/FileStorage',
        'alias' => 'files',
        'disk' => 'files',
        'url' => env('APP_URL'),
        'nesting_level' => 2,
        'path_name_length' => 2
    ],
    'MailManager' => [
        'path' => 'Support/MailManager',
        'alias' => 'mail',
    ],
    'IntegrationEventBus' => [
        'path' => 'Support/IntegrationEventBus',
        'alias' => 'events',
    ],
    'Scheduler' => [
        'path' => 'Support/Scheduler'
    ],
    'Traveline' => [
        'enabled' => env('TRAVELINE_ENABLED', true),
        'path' => 'Integration/Traveline',
        'auth' => [
            'username' => env('TRAVELINE_USERNAME'),
            'password' => env('TRAVELINE_PASSWORD'),
        ],
        'notifications_url' => env('TRAVELINE_NOTIFICATIONS_URL'),
        'is_prices_for_residents' => env('TRAVELINE_IS_PRICES_FOR_RESIDENTS', false),
        'supported_currencies' => explode(',', env('TRAVELINE_SUPPORTED_CURRENCIES', 'UZS')),
    ]
];
