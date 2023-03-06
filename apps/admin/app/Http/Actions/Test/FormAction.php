<?php

namespace App\Admin\Http\Actions\Test;

use App\Admin\Http\Forms\TestForm;

class FormAction
{
    public function __construct() {}

    public function handle()
    {
        $form = new TestForm('data');

        //$form->error('User not found!');
        if ($this->submit($form)) {
            dd('submitted');
            redirect();
        }
        dump($form->getData());

        $form->setTestData();

        app('breadcrumbs')
            ->add('Test form');

        return app('layout')
            ->title('adasd')
            //->style('login.js')
            //->script('login.css')
            ->view('test.form', [
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
