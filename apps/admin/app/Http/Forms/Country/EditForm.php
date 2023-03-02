<?php

namespace App\Admin\Http\Forms\Country;

use App\Admin\View\Form\Form;

class EditForm extends Form
{
    protected function boot()
    {
        $this->view('default.form')
            ->csrf()
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->language('language', ['label' => 'Язык', 'emptyItem' => '-Не выбрано-'])
            ->text('flag', ['label' => 'Код флага', 'required' => true])
            ->text('phone_code', ['label' => 'Код телефона', 'required' => true])
            ->currency('currency_id', ['label' => 'Валюта'])
            ->checkbox('default', ['label' => 'По умолчанию']);
    }
}
