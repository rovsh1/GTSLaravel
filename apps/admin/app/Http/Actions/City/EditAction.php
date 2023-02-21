<?php

namespace App\Admin\Http\Actions\City;

use App\Admin\Http\Forms\City\EditForm;

class EditAction
{
    public function __construct(
        // private CurrencyFacadeInterface $currencyFacade
    ) {}

    public function handle(array $data = [])
    {
        $form = new EditForm('data');

        //$form->error('User not found!');
//        if ($this->submit($form)) {
//            dd('submitted');
//            redirect();
//        }
        //dump($form->getData());

        $form->data($data);

        return app('layout')
            ->title('Редактирование города')
            ->view('city.edit', [
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
