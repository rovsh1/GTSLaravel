<?php

return [
    'Hotel' => [
        'path' => app_path('Hotel')
    ],
    'Administrator' => [
        'deferred' => true,
        //'required' => ['Traveline'],
        'path' => app_path('Administrator')
    ],
    'FileStorage' => [
        'path' => app_path('Services/FileStorage'),
        'disk' => 'files',
        'url' => env('APP_URL'),
        'nesting_level' => 2,
        'path_name_length' => 2
    ],
    'Traveline' => [
        'enabled' => env('TRAVELINE_ENABLED', true),
        'deferred' => true,
        'path' => app_path('Services/Traveline'),
        'auth' => [
            'username' => env('TRAVELINE_USERNAME'),
            'password' => env('TRAVELINE_PASSWORD'),
        ],
        'notifications_url' => env('TRAVELINE_NOTIFICATIONS_URL'),
    ],
];
