<?php

namespace App\Admin\Http\Actions\Country;

use App\Admin\Models\Country;
use App\Admin\Support\Http\AbstractSearchAction;

class SearchAction extends AbstractSearchAction
{
    protected $model = Country::class;

    protected $title = 'Страны';

    protected function boot()
    {
        $this->enableQuicksearch();
    }

    protected function prepareSelectQuery($query)
    {
        $query->joinTranslations();
    }

    protected function applySearch($query, array $filters) {}

    protected function gridFactory()
    {
        return parent::gridFactory()
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->orderBy('name', 'asc');
    }
}
