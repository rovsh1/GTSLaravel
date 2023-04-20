<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
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
            ->view('administration.access-group-form.access-group-form', [
                'permissions' => $this->getPermissionsArray(fn() => []),
                'categories' => Sitemap::getCategories(),
                'default' => 'booking',
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
            ->view('administration.access-group-form.access-group-form', [
                'categories' => Sitemap::getCategories(),
                'permissions' => $this->getPermissionsArray($allowed),
                'default' => 'booking',
            ]);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->hidden('rules', ['render' => false])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            //->addElement('role', 'enum', ['label' => 'Роль', 'emptyItem' => '', 'enum' => AccessRole::class])
            ->select('members', [
                'label' => 'Администраторы',
                'textIndex' => 'presentation',
                'items' => Administrator::get(),
                'multiple' => true
            ])
            ->textarea('description', ['label' => 'Описание', 'required' => true])//->hidden('name1', ['label1' => 'Наименование', 'required' => true])
            ;
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            //->addColumn('role', 'enum', ['text' => 'Роль', 'enum' => AccessRole::class])
            ->text('description', ['text' => 'Расшифровка'])
            ->number('administrators_count', ['text' => 'Администраторы', 'format' => 'NFD=0']);
    }

    protected function prepareGridQuery($query)
    {
        $query->addSelect('administrator_access_groups.*')
            ->withAdministratorsCount();
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
