<?php

namespace App\Admin\Http\Forms\Country;

use App\Admin\Support\Enums\LanguageEnum;
use App\Admin\View\Form\Form;

class EditForm extends Form
{
    protected function boot()
    {
        $this->view('default.form')
            ->csrf()
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->select('language', ['label' => 'Язык', 'emptyItem' => '-Не выбрано-', 'enum' => LanguageEnum::class])
            ->text('flag', ['label' => 'Код флага', 'required' => true])
            ->text('phone_code', ['label' => 'Код телефона', 'required' => true])
            ->currency('currency_id', ['label' => 'Валюта'])
            ->checkbox('default', ['label' => 'По умолчанию']);
    }
}
