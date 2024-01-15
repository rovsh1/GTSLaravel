<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('company-requisite')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_SERVICE)
    ->route('company-requisites')
    ->model(\Shared\Models\CompanyRequisite::class)
    ->controller(\App\Admin\Http\Controllers\Data\CompanyRequisiteController::class, ['except' => ['show']])
    ->titles([
        "index" => "Реквизиты компании"
    ])
    ->permissions(['read', 'update']);