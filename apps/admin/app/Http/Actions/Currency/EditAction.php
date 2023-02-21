<?php

namespace App\Admin\Http\Actions\Currency;

use App\Admin\Http\Forms\Currency\EditForm;
use GTS\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;

class EditAction
{
    public function __construct(
        private CurrencyFacadeInterface $currencyFacade
    ) {}

    public function handle(int $id)
    {
        $currency = $this->currencyFacade->findById($id);

        return app('layout')
            ->title('Редактирование валюты')
            ->view('currency.edit', [
                'form' => (new EditForm('data'))->data($currency->toArray())
                    ->method('put')
                    ->route(route('currency.update', $currency))
            ]);
    }
}
