<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Currency;

use GTS\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;
use GTS\Administrator\UI\Admin\Http\Forms\Currency\EditForm;

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

    public function submit($form)
    {
        if (!$form->submit()) {
            return false;
        }

        $data = $form->getData();
        //dd($data);

        return false;
    }
}
