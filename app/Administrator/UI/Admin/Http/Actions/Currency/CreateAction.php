<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Currency;

use GTS\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;
use GTS\Administrator\UI\Admin\Http\Forms\Currency\EditForm;

class CreateAction
{
    public function __construct(
        // private CurrencyFacadeInterface $currencyFacade
    ) {}

    public function handle()
    {
        return app('layout')
            ->title('Добавление валюты')
            ->view('currency.create', [
                'form' => (new EditForm('data'))->route(route('currency.store'))
            ]);
    }
}
