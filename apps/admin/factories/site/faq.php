<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('site-faq')
    ->category(Factory::CATEGORY_SITE)
    ->group(Factory::GROUP_CONTENT)
    ->model(\App\Admin\Models\Site\Faq::class)
    ->controller(\App\Admin\Http\Controllers\Site\FaqController::class, ['except' => ['show']])
    ->titles([
        "index" => "FAQ",
        "create" => "Новая страница"
    ])
    ->priority(140);