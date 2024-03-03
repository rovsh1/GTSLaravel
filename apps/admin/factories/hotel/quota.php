<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-quota')
    ->category(Factory::CATEGORY_HOTEL)
    ->controller(\App\Admin\Http\Controllers\Hotel\QuotaAvailabilityController::class)
    ->titles([
        'index' => 'Доступность',
    ])
    ->priority(90);
