<?php

return [
    'enabled' => env('TRAVELINE_ENABLED', true),
    'auth' => [
        'username' => env('TRAVELINE_USERNAME'),
        'password' => env('TRAVELINE_PASSWORD'),
    ],
    'notifications_url' => env('TRAVELINE_NOTIFICATIONS_URL'),
    'is_prices_for_residents' => env('TRAVELINE_IS_PRICES_FOR_RESIDENTS', false),
    'supported_currencies' => explode(',', env('TRAVELINE_SUPPORTED_CURRENCIES', 'UZS')),
    'timezone' => env('TRAVELINE_TIMEZONE', 'Asia/Tashkent'),
];

