<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Country;

use GTS\Administrator\Infrastructure\Facade\Reference\CountryFacadeInterface;
use GTS\Services\FileStorage\Infrastructure\Facade\ReaderFacade;
use GTS\Services\FileStorage\Infrastructure\Facade\ReaderFacadeInterface;
use GTS\Shared\UI\Admin\View\Grid\GridBuilder;

class SearchAction
{
    public function __construct(public readonly CountryFacadeInterface $facade) {}

    public function handle(array $params = [])
    {
        $dto = new \stdClass();
        //$dto->default = false;
        //$dto->flag = 'ru';

        app(ReaderFacadeInterface::class);

        return app('layout')
            ->view('country.index', [
                'grid' => $this->gridFactory($dto)
            ]);
    }

    private function gridFactory($dto)
    {
        return (new GridBuilder())
            ->paginator($this->facade->count($dto), 20)
            ->id('id', ['text' => 'ID'])
            ->text('name', ['text' => 'Name'])
            ->orderBy('id', 'asc')
            ->callFacadeSearch($this->facade, $dto)
            ->getGrid();
    }
}
