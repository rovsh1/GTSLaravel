<?php

namespace App\Admin\Http\Actions\City;

use App\Admin\Http\View\Grid\GridBuilder;
use Module\Administrator\Infrastructure\Facade\Reference\CityFacadeInterface;

class SearchAction
{
    public function __construct(
        private readonly CityFacadeInterface $facade
    ) {}

    public function handle(array $params = [])
    {
        $dto = new \stdClass();

        return app('layout')
            ->title('Города')
            ->view('city.index', [
                'grid' => (new GridBuilder())
                    ->paginator($this->facade->count($dto), 20)
                    // ->id('id', ['text' => 'ID'])
                    ->text('name', ['text' => 'Наименование'])
                    ->actions('actions', ['route' => route('city.index')])
                    ->orderBy('id', 'asc')
                    ->callFacadeSearch($this->facade, $dto)
                    ->getGrid()
            ]);
    }
}
