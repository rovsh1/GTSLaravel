<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('mail-log')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_LOG)
    ->route('mail-log')
//    ->model(\App\Admin\Models\System\Constant::class)
    ->controller(\App\Admin\Http\Controllers\Administration\MailQueueController::class, ['only' => ['index']])
    ->titles([
        "index" => "История писем"
    ])
    ->readOnly();