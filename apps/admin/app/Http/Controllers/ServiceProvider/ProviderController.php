<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;

class ProviderController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'service-provider';
    }

    public function show(int $id): LayoutContract
    {
        dd(\Format::price(1233));
//        parent::show($id);
//
//        $contacts = $this->model->contacts;
//        dd($contacts[0]->type);

        return parent::show($id)
            ->ss('service-provider/show')
            ->data([
                'contactsUrl' => $this->prototype->route('show', $id) . '/contacts',
                'contactsEditable' => Acl::isAllowed($this->getPrototypeKey(), 'update'),
                'contacts' => $this->model->contacts
            ]);
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
}
