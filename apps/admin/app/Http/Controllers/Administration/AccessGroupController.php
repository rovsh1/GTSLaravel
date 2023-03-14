<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;
use App\Admin\Support\View\Layout as LayoutContract;

class AccessGroupController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'access-group';
    }

    public function edit(int $id): LayoutContract
    {
        $categories = [
            'reservation',
            'hotel',
            'finance',
            'client',
            'site',
            'reports',
            'administration'
        ];

        return parent::edit($id)
            ->ss('administration/access-group-form')
            ->data([
                'categories' => $categories,
                'default' => 'reservation',
                //'prototypes' =>
            ])
            ->addMetaVariable(
                'prototypes',
                array_map(function ($prototype) {
                    return [
                        'key' => $prototype->key,
                        'name' => $prototype->title(),
                        'category' => $prototype->category,
                        'permissions' => $prototype->permissions()
                    ];
                }, Prototypes::all())
            );
    }

    protected function formFactory(): Form
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
            ->hidden('name1', ['label1' => 'Наименование', 'required' => true])
            ->addElement('rules', 'hidden', ['render' => false]);
    }

    protected function gridFactory(): Grid
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
