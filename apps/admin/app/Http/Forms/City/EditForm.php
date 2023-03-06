<?php

namespace App\Admin\Http\Forms\City;

use App\Admin\Support\View\Form\Form;

class EditForm extends Form
{
    protected function boot()
    {
        $this->view('default.form')
            ->csrf()
            ->country('country_id', ['label' => 'Страна', 'required' => true])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->textarea('text', ['label' => 'Описание']);
    }
}
