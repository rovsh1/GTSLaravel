<?php

namespace App\Admin\Http\Forms\Currency;

use App\Admin\Http\View\Form\Form;

class EditForm extends Form
{
    protected function boot()
    {
        $this->view('default.form.edit')
            ->csrf()
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->number('code_num', ['label' => 'Код (цифровой)', 'required' => true])
            ->text('code_char', ['label' => 'Код (символьный)', 'required' => true])
            ->text('sign', ['label' => 'Символ', 'required' => true]);
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
