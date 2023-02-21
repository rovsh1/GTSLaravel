<?php

namespace App\Admin\Http\Actions\Country;

use App\Admin\Http\Grids\CountriesGrid;

class SearchAction
{
    public function __construct() {}

    public function handle(array $params = [])
    {
        $dto = new \stdClass();
        //$dto->default = false;
        //$dto->flag = 'ru';

        return app('layout')->view('country.index', [
            'grid' => new CountriesGrid()
        ]);
    }
}
