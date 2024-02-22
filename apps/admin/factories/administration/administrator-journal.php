<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('administrator-journal')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_LOG)
    ->route('administrator-journal')
    ->controller(\App\Admin\Http\Controllers\Administration\JournalController::class, ['only' => ['index']])
    ->titles([
        "index" => "Журнал изменений"
    ])
    ->readOnly();