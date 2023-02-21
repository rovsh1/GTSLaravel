<?php

namespace App\Admin\Http\Actions\Country;

use App\Admin\Http\View\Grid\GridBuilder;
use App\Admin\Models\Country;

class SearchAction
{
    public function __construct() {}

    public function handle(array $params = [])
    {
        $dto = new \stdClass();
        //$dto->default = false;
        //$dto->flag = 'ru';

        return app('layout')->view('country.index', [
            'grid' => $this->gridFactory($dto)
        ]);
    }

    private function gridFactory($dto)
    {
        return (new GridBuilder())
            ->paginator(Country::count(), 20)
            // ->id('id', ['text' => 'ID'])
            ->text('name', ['text' => 'Наименование'])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->orderBy('id', 'asc')
            ->data(Country::joinTranslations())
            ->getGrid();
    }
}
