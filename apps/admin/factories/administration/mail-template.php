<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('mail-template')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_NOTIFICATION)
    ->route('mail-templates')
    ->model(\App\Admin\Models\System\MailTemplate::class)
    ->controller(\App\Admin\Http\Controllers\Administration\MailTemplateController::class, ['except' => ['show']])
    ->views([
        'form' => 'mail.template.form'
    ])
    ->titles([
        "index" => "Шаблоны писем",
        "create" => "Новый шаблон"
    ]);