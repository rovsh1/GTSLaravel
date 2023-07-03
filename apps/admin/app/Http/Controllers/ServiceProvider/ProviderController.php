<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\TransportCar;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\ActionsMenu;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\View\Menus\ServiceProviderMenu;
use Illuminate\Database\Eloquent\Model;

class ProviderController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'service-provider';
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

        Sidebar::submenu(new ServiceProviderMenu($model, 'info'));
    }

    protected function prepareEditMenu(Model $model)
    {
        Sidebar::submenu(new ServiceProviderMenu($model, 'info'));
    }

    protected function getShowViewData(): array
    {
        return [
            'contactsUrl' => $this->prototype->route('show', $this->model->id) . '/contacts',
            'contactsEditable' => Acl::isUpdateAllowed($this->getPrototypeKey()),
            'contacts' => $this->model->contacts
        ];
    }
}
