<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\City;

use GTS\Shared\UI\Admin\View\Grid\GridBuilder;
use GTS\Administrator\Infrastructure\Facade\Reference\CityFacadeInterface;

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
