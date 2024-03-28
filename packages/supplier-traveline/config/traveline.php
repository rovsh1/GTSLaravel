<?php

return [
    'notifications_url' => env('TRAVELINE_NOTIFICATIONS_URL'),
    'is_prices_for_residents' => env('TRAVELINE_IS_PRICES_FOR_RESIDENTS', false),
    'supported_currencies' => explode(',', env('TRAVELINE_SUPPORTED_CURRENCIES', 'UZS')),
    'timezone' => env('TRAVELINE_TIMEZONE', 'Asia/Tashkent'),
];

