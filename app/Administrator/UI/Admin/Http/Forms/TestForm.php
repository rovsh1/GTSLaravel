<?php

namespace GTS\Administrator\UI\Admin\Http\Forms;

use GTS\Shared\UI\Admin\View\Form\Form;

enum TestEnum: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
}

class TestForm extends Form
{
    protected function boot()
    {
        //dd(TestEnum::cases(), TestEnum::ONE, enum_exists(TestEnum::ONE::class));
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
                'default' => '12',
                'groups' => [3, 5, 12, 65],
                'items' => [['id' => 56, 'parent_id' => 5], 3, 5, 12, 65]//,
            ])
            ->select('enum', [
                'label' => 'Enum',
                'emptyItem' => '-Not selected-',
                'enum' => TestEnum::class
            ])
            ->radio('gender', [
                'label' => 'Gender',
                'value' => 'female',
                'items' => ['male', 'female']//,
            ])
            ->radio('items', [
                'label' => 'Gender',
                'value' => [2, 5],
                'multiple' => true,
                'items' => [2, 6, 7, 8, 5]
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
