<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Models\Reference\City;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\ActionsMenu;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\View\Menus\SupplierMenu;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Database\Eloquent\Model;

class SupplierController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'supplier';
    }

    protected function supplierParams(Supplier $model): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->custom('cities', 'Города', fn($v, $o) => $o->cities()->get()->map(fn(City $city) => $city->name)->join(', '))
            ->data($model);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->select('cities', [
                'label' => 'Города',
                'multiple' => true,
                'items' => City::get()
            ]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->text('name', ['text' => 'Наименование', 'route' => $this->prototype->routeName('show'), 'order' => true])
            ->orderBy('name', 'asc');
    }

    protected function prepareShowMenu(Model $model)
    {
        $menu = ActionsMenu::getFacadeRoot();
        if (Acl::isUpdateAllowed($this->getPrototypeKey())) {
            $menu->addUrl($this->prototype->route('edit', $model), [
                'icon' => 'edit',
                'cls' => 'btn-edit',
                'text' => 'Редактировать'
            ]);
        }

        if (Acl::isUpdateAllowed($this->getPrototypeKey())) {
            $menu->addUrl($this->prototype->route('destroy', $model), [
                'icon' => 'delete',
                'cls' => 'btn-delete',
                'text' => 'Удалить'
            ]);
        }

        Sidebar::submenu(new SupplierMenu($model, 'info'));
    }

    protected function prepareEditMenu(Model $model)
    {
        Sidebar::submenu(new SupplierMenu($model, 'info'));
    }

    protected function getShowViewData(): array
    {
        return [
            'params' => $this->supplierParams($this->model),
            'contactsUrl' => $this->prototype->route('show', $this->model->id) . '/contacts',
            'contactsEditable' => Acl::isUpdateAllowed($this->getPrototypeKey()),
            'contacts' => $this->model->contacts
        ];
    }
}
