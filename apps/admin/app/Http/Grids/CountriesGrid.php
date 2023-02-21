<?php

namespace App\Admin\Http\Grids;

use App\Admin\Http\View\Grid\ModelGrid;
use App\Admin\Models\Country;

class CountriesGrid extends ModelGrid
{
    protected $model = Country::class;

    protected function build(): void
    {
        $this
            ->enableQuicksearch()
            ->text('name', ['text' => 'Наименование'])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->orderBy('id', 'asc');
    }

    protected function prepareSelectQuery($query)
    {
        $query->joinTranslations();
    }

    protected function applySearch($query, array $filters) {}
}
