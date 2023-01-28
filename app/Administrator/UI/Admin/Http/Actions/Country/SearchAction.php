<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Country;

use GTS\Administrator\Infrastructure\Facade\Reference\CountryFacadeInterface;

use Gsdk\Grid\Grid;
use Gsdk\Navigation\Paginator;

class SearchAction
{
    public function __construct(private CountryFacadeInterface $facade) {}

    public function handle()
    {
        $dto = new \stdClass();
        //$dto->default = false;
        //$dto->flag = 'ru';

        $paginator = new Paginator();
        $paginator->setStep(20);
        $paginator->setCount($this->facade->count($dto));

        $dto->orderBy = 'id';
        $dto->sortOrder = 'desc';
        $dto->limit = $paginator->step;

        $result = $this->facade->search($dto);
        //dd($result, $paginator->getCount());

        $grid = (new Grid())
            ->paginator($paginator)
            ->text('id', ['text' => 'ID'])
            ->text('name', ['text' => 'Name'])
            ->data($result);

        return app('layout')
            ->view('country.index', [
                'grid' => $grid
            ]);
    }
}
