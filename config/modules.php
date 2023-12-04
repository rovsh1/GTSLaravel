<?php

return [
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
];
