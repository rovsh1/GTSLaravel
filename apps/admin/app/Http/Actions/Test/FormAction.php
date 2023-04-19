<?php

namespace App\Admin\Http\Actions\Test;

use App\Admin\Http\Forms\TestForm;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;

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

        Breadcrumb::add('Test form');

        return Layout::title('adasd')
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
