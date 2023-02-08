<?php

namespace GTS\Administrator\UI\Admin\Http\Forms;

use GTS\Shared\UI\Admin\View\Form\Form;

class TestForm extends Form
{
    protected function boot()
    {
        $this
            ->view('default.form.edit')
            ->csrf()
            ->hidden('id', ['label' => '', 'cast' => 'int', 'required' => true])
            ->text('name', ['label' => 'Name', 'required' => true])
            ->tel('phone', ['label' => 'Phone', 'nullable' => true])
            ->color('color', ['label' => 'color'])
            ->file('file', ['label' => 'file'])
            ->month('month', ['label' => 'month', 'required' => true])
            ->range('range', ['label' => 'range', 'min' => 1, 'max' => 10])
            ->date('date', ['label' => 'date'])
            ->time('time', ['label' => 'time'])
            ->number('number', ['label' => 'Number']);
    }

    public function setTestData()
    {
        $this->data([
            'id' => 'dfdf123asds',
            'name' => 'Ivan',
            'phone' => '916428',
            'color' => '#ccddee',
            'number' => 45,
            'range' => 9,
        ]);
    }
}
