<?php

namespace App\Admin\Http\Forms\Currency;

use App\Admin\Support\View\Form\Form;

class EditForm extends Form
{
    protected function boot()
    {
        $this->view('default.form')
            ->csrf()
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->number('code_num', ['label' => 'Код (цифровой)', 'required' => true])
            ->text('code_char', ['label' => 'Код (символьный)', 'required' => true])
            ->text('sign', ['label' => 'Символ', 'required' => true]);
    }
}
