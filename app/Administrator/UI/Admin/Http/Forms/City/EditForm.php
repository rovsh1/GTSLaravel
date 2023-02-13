<?php

namespace GTS\Administrator\UI\Admin\Http\Forms\City;

use GTS\Shared\UI\Admin\View\Form\Form;

class EditForm extends Form
{
    protected function boot()
    {
        $this->view('default.form.edit')
            ->csrf()
            ->country('country_id', ['label' => 'Страна', 'required' => true])
            ->text('name', ['label' => 'Наименование', 'required' => true]);
    }

//    public function setTestData()
//    {
//        $this->data([
//            'id' => 'dfdf123asds',
//            'name' => 'Узбекский сум',
//            'code_num' => '860',
//            'code_char' => 'UZS',
//            'sign' => 'сўм'
//        ]);
//    }
}
