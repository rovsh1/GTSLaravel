<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;

class ConstantController extends AbstractPrototypeController
{
    protected $prototype = 'constant';

    protected function formFactory()
    {
        return (new Form('data'))
            ->addElement('value', 'text', ['label' => 'Значение'])
            ->addElement('enabled', 'checkbox', ['label' => 'Enabled']);
    }

    protected function gridFactory()
    {
        return (new Grid())
            ->enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit(['route' => $this->prototype->routeName('edit')])
            ->text('name', ['text' => 'Расшифровка', 'order' => true])
            ->text('key', ['text' => 'Ключ', 'order' => true])
            ->text('value', ['text' => 'Значение'])
            ->boolean('enabled', ['text' => 'Enabled', 'order' => true]);
    }
}
