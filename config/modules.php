<?php

return [
    'Hotel' => [
        'path' => modules_path('Hotel')
    ],
    /**
     * @deprecated start
     * @todo удалить после переноса модуля Traveline на новую логику
     */
    'HotelOld' => [
        'path' => modules_path('HotelOld')
    ],
    /**
     * @deprecated end
     */
    'HotelReservation' => [
        'path' => modules_path('Reservation/HotelReservation')
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
    ]
];
