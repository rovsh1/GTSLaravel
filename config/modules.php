<?php

return [
    'Booking' => [
        'templates_path' => base_path('resources/booking/pdf-templates'),
    ],
    'FileStorage' => [
        'alias' => 'files',
        'disk' => 'files',
        'url' => env('APP_URL'),
        'nesting_level' => 2,
        'path_name_length' => 2
    ],
    'MailManager' => [
        'alias' => 'mail',
    ],
    'IntegrationEventBus' => [
        'alias' => 'events',
    ],
    'Traveline' => [
        'enabled' => env('TRAVELINE_ENABLED', true),
        'auth' => [
            'username' => env('TRAVELINE_USERNAME'),
            'password' => env('TRAVELINE_PASSWORD'),
        ],
        'notifications_url' => env('TRAVELINE_NOTIFICATIONS_URL'),
        'is_prices_for_residents' => env('TRAVELINE_IS_PRICES_FOR_RESIDENTS', false),
        'supported_currencies' => explode(',', env('TRAVELINE_SUPPORTED_CURRENCIES', 'UZS')),
    ]
];
