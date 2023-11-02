<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('mail-recipient')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_NOTIFICATION)
    ->route('mail-recipients')
    ->controller(\App\Admin\Http\Controllers\Administration\MailRecipientController::class, [
        'only' => ['index', 'update']
    ])
    ->titles([
        "index" => "Получатели писем"
    ]);