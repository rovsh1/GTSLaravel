<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Enums\Client\TypeEnum;
use App\Admin\Http\Requests\Client\SearchRequest;
use App\Admin\Http\Resources\Client as ClientResource;
use App\Admin\Models\Client\Client;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\ActionsMenu;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\ClientMenu;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class ClientController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'client';
    }

    public function edit(int $id): LayoutContract
    {
        $layout = parent::edit($id);
        Sidebar::submenu(new ClientMenu($this->model, 'info'));

        return $layout;
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $clientsQuery = Client::orderBy('name');
        if ($request->getOrderId() !== null) {
            $order = OrderAdapter::findOrder($request->getOrderId());
            if (!empty($order)) {
                $clientsQuery->whereId($order->clientId);
            }
        }

        return response()->json(
            ClientResource::collection($clientsQuery->get())
        );
    }

    protected function getShowViewData(): array
    {
        $showUrl = $this->prototype->route('show', $this->model->id);
        $isUpdateAllowed = Acl::isUpdateAllowed($this->getPrototypeKey());

        return [
            'params' => $this->clientParams(),
            'contactsUrl' => $showUrl . '/contacts',
            'contactsEditable' => $isUpdateAllowed,
            'contacts' => collect()
        ];
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

        Sidebar::submenu(new ClientMenu($model, 'info'));
    }

    private function clientParams(): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->enum('type', 'Тип', TypeEnum::class)
            ->text('city_name', 'Город')
            ->data($this->model);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->text('name', ['text' => 'ФИО', 'route' => $this->prototype->routeName('show')])
            ->enum('type', ['text' => 'Тип', 'enum' => TypeEnum::class]);
    }

    protected function formFactory(): FormContract
    {
        return Form::text('name', ['label' => 'ФИО или название компании'])
            ->enum('type', ['label' => 'Тип', 'enum' => TypeEnum::class])
            ->city('city_id', ['label' => 'Город']);
    }
}
