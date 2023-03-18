<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sitemap;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;

class AccessGroupController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'access-group';
    }

    public function create(): LayoutContract
    {
        return parent::create()
            ->ss('administration/access-group-form')
            ->data([
                'permissions' => $this->getPermissionsArray(fn() => []),
                'categories' => Sitemap::getCategories(),
                'default' => 'reservation',
            ]);
    }

    public function edit(int $id): LayoutContract
    {
        $rules = $this->repository->getGroupPermissions($id);
        $allowed = function ($resource) use ($rules) {
            return $rules
                ->filter(fn($r) => $r->resource === $resource)
                ->filter(fn($r) => $r->flag === true)
                ->map(fn($r) => $r->permission)
                ->all();
        };

        return parent::edit($id)
            ->ss('administration/access-group-form')
            ->data([
                'categories' => Sitemap::getCategories(),
                'permissions' => $this->getPermissionsArray($allowed),
                'default' => 'reservation',
            ]);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->addElement('rules', 'hidden', ['render' => false])
            ->addElement('name', 'text', ['label' => 'Наименование', 'required' => true])
            //->addElement('role', 'enum', ['label' => 'Роль', 'emptyItem' => '', 'enum' => AccessRole::class])
            ->addElement('members', 'select', [
                'label' => 'Администраторы',
                'textIndex' => 'presentation',
                'items' => Administrator::get(),
                'multiple' => true
            ])
            ->addElement('description', 'textarea', ['label' => 'Описание', 'required' => true])//->hidden('name1', ['label1' => 'Наименование', 'required' => true])
            ;
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->addColumn('name', 'text', ['text' => 'Наименование'])
            //->addColumn('role', 'enum', ['text' => 'Роль', 'enum' => AccessRole::class])
            ->addColumn('description', 'text', ['text' => 'Расшифровка']);
    }

    private function getPermissionsArray(\Closure $allowed): array
    {
        $permissions = [];
        foreach (Prototypes::all() as $prototype) {
            $permissions[$prototype->routeName('index')] = (object)[
                'id' => $prototype->key,
                'allowed' => $allowed($prototype->key),
                'available' => $prototype->permissions()
            ];
        }
        return $permissions;
    }
}
