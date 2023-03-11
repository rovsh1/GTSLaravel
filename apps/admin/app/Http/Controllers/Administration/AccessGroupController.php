<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

class AccessGroupController extends AbstractPrototypeController
{
    protected $prototype = 'access-group';

    protected function formFactory()
    {
        return (new Form('data'))
            ->addElement('name', 'text', ['label' => 'Наименование', 'required' => true])
            //->addElement('role', 'enum', ['label' => 'Роль', 'emptyItem' => '', 'enum' => AccessRole::class])
            ->addElement('members', 'select', [
                'label' => 'Администраторы',
                'textIndex' => 'presentation',
                'items' => Administrator::get(),
                'multiple' => true
            ])
            ->addElement('description', 'textarea', ['label' => 'Описание', 'required' => true])
            ->addElement('rules', 'hidden', ['render' => false]);
    }

    protected function gridFactory()
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit(['route' => $this->prototype->routeName('edit')])
            ->addColumn('name', 'text', ['text' => 'Наименование'])
            //->addColumn('role', 'enum', ['text' => 'Роль', 'enum' => AccessRole::class])
            ->addColumn('description', 'text', ['text' => 'Расшифровка']);
    }
}
