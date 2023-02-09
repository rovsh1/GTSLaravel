<?php

namespace GTS\Administrator\UI\Admin\Http\Forms;

use GTS\Shared\UI\Admin\View\Form\Form;

class TestForm extends Form
{
    protected function boot()
    {
        $this
            ->view('default.form.edit')
            ->messages([
                'id.required' => 'ID undefined!',
                'pass.regex' => 'Password need to match regex',
                'number.in' => 'Number out of range'
            ])
            ->csrf()
            ->select('currency', [
                'label' => 'Currency',
                'emptyItem' => '-Not selected-',
                'groups' => [3, 5, 12, 65],
                'items' => [['id' => 56, 'parent_id' => 5], 3, 5, 12, 65]//,
            ])
            ->checkbox('flag', ['label' => 'Checked', 'checkedValue' => 'asdasd', 'uncheckedValue' => 66, 'required' => true])
            ->hidden('id', ['label' => '', 'cast' => 'int', 'required' => true])
            ->text('name', ['label' => 'Name', 'required' => true])
            ->tel('phone', ['label' => 'Phone', 'nullable' => true])
            ->color('color', ['label' => 'color', 'render' => false])
            ->file('file', ['label' => 'file'])
            ->password('pass', ['label' => 'Пароль', 'rules' => 'regex:/^\d+$/'])
            ->month('month', ['label' => 'month', 'required' => true])
            ->range('range', ['label' => 'range', 'min' => 1, 'max' => 10])
            ->date('date', ['label' => 'date'])
            ->time('time', ['label' => 'time'])
            ->textarea('notes', ['label' => 'ds', 'placeholder' => 'Notes placeholder'])
            ->number('number', ['label' => 'Number', 'rules' => 'min:4|in:5,6']);
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
//, 'rules' => [
//    ['regex' => '/^\d+$/']
//]
