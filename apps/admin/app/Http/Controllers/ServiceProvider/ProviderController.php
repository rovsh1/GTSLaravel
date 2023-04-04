<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\ActionsMenu;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Database\Eloquent\Model;

class ProviderController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'service-provider';
    }

    public function show(int $id): LayoutContract
    {
        return parent::show($id)
                     ->ss('service-provider/show');
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
                   ->text('name', ['label' => 'Наименование', 'required' => true]);
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
                'cls'  => 'btn-edit',
                'text' => 'Редактировать'
            ]);
        }

        if (Acl::isUpdateAllowed($this->getPrototypeKey())) {
            $menu->addUrl($this->prototype->route('destroy', $model), [
                'icon' => 'delete',
                'cls'  => 'btn-delete',
                'text' => 'Удалить'
            ]);
        }
    }

    protected function getShowViewData(): array
    {
        return [
            'contactsUrl'      => $this->prototype->route('show', $this->model->id) . '/contacts',
            'contactsEditable' => Acl::isUpdateAllowed($this->getPrototypeKey()),
            'contacts'         => $this->model->contacts
        ];
    }
}
