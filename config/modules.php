<?php

return [
    'Hotel' => [
        'path' => modules_path('Hotel')
    ],
    'Client' => [
        'path' => modules_path('Client')
    ],
    'Booking' => [
        'path' => modules_path('Booking'),
        'templates_path' => storage_path('app/booking/templates'),
    ],
    'CurrencyRate' => [
        'path' => modules_path('Pricing/CurrencyRate'),
    ],
    'FileStorage' => [
        'path' => modules_path('Services/FileStorage'),
        'alias' => 'files',
        'disk' => 'files',
        'url' => env('APP_URL'),
        'nesting_level' => 2,
        'path_name_length' => 2
    ],
    'MailManager' => [
        'path' => modules_path('Services/MailManager'),
        'alias' => 'mail',
    ],
    'Scheduler' => [
        'path' => modules_path('Services/Scheduler')
    ],
    'Traveline' => [
        'enabled' => env('TRAVELINE_ENABLED', true),
        'path' => modules_path('Integration/Traveline'),
        'auth' => [
            'username' => env('TRAVELINE_USERNAME'),
            'password' => env('TRAVELINE_PASSWORD'),
        ],
        'notifications_url' => env('TRAVELINE_NOTIFICATIONS_URL'),
        'is_prices_for_residents' => env('TRAVELINE_IS_PRICES_FOR_RESIDENTS', false),
        'supported_currencies' => explode(',', env('TRAVELINE_SUPPORTED_CURRENCIES', 'UZS')),
    ]
];
