<?php

namespace App\Admin\Http\Actions\City;

use App\Admin\Http\Forms\City\EditForm;

class CreateAction
{
    public function __construct(
        // private CurrencyFacadeInterface $currencyFacade
    ) {}

    public function handle()
    {
        $form = new EditForm('data');

        //$form->error('User not found!');
        if ($this->submit($form)) {
            dd('submitted');
            redirect();
        }
        //dump($form->getData());

        //$form->setTestData();

        return app('layout')
            ->title('Добавление города')
            ->view('city.create', [
                'form' => $form
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
