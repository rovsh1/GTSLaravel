<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Test;

use GTS\Administrator\UI\Admin\Http\Forms\TestForm;

class FormAction
{
    public function __construct() {}

    public function handle()
    {
        $form = new TestForm('data');

        $form->error('User not found!');
        if ($this->submit($form)) {
            dd('submitted');
            redirect();
        }

        $form->setTestData();

        return app('layout')
            ->layout('layouts.test')
            //->style('login.js')
            //->script('login.css')
            ->title('adasd')
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
