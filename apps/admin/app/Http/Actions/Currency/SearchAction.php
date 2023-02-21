<?php

namespace App\Admin\Http\Actions\Currency;

use App\Admin\Http\View\Grid\GridBuilder;
use Module\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;

class SearchAction
{
    public function __construct(
        private readonly CurrencyFacadeInterface $facade
    ) {}

    public function handle(array $params = [])
    {
        $dto = new \stdClass();

        return app('layout')
            ->title('Валюты')
            ->view('currency.index', [
                'grid' => (new GridBuilder())
                    ->paginator($this->facade->count($dto), 20)
                    // ->id('id', ['text' => 'ID'])
                    ->text('name', ['text' => 'Наименование'])
                    ->text('code_num', ['text' => 'Код (цифровой)'])
                    ->text('code_char', ['text' => 'Код (символьный)'])
                    ->text('sign', ['text' => 'Символ'])
                    ->text('rate', ['text' => 'Курс'])
                    ->actions('actions', ['route' => route('currency.index')])
                    ->orderBy('id', 'asc')
                    ->callFacadeSearch($this->facade, $dto)
                    ->getGrid()
            ]);
    }
}
