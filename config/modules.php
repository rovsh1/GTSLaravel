<?php

return [
    'Administrator' => [
        'path' => app_path('Administrator')
    ],
    'Hotel' => [
        'path' => app_path('Hotel')
    ],
    'Administrator' => [
        'path' => app_path('Administrator')
    ],
    'Traveline' => [
        'enabled' => env('TRAVELINE_ENABLED', true),
        'path' => app_path('Services/Traveline'),
        'auth' => [
            'username' => env('TRAVELINE_USERNAME'),
            'password' => env('TRAVELINE_PASSWORD'),
        ]
    ],
];
