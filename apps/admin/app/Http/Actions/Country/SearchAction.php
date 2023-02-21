<?php

namespace App\Admin\Http\Actions\Country;

use App\Admin\Http\View\Grid\GridBuilder;
use GTS\Administrator\Infrastructure\Facade\Reference\CountryFacadeInterface;
use Module\Services\FileStorage\Infrastructure\Facade\ReaderFacadeInterface;

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
            //->paginator($this->facade->count($dto), 20)
            // ->id('id', ['text' => 'ID'])
            ->text('name', ['text' => 'Наименование'])
            ->text('phone_code', ['text' => 'Код телефона'])
            ->orderBy('id', 'asc')
            //->callFacadeSearch($this->facade, $dto)
            ->getGrid();
    }
}
