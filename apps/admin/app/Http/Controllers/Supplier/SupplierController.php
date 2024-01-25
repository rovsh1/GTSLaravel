<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Http\Requests\Supplier\SearchRequest;
use App\Admin\Http\Resources\Supplier as Resource;
use App\Admin\Models\Reference\City;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\View\Menus\SupplierMenu;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Sdk\Shared\Enum\CurrencyEnum;

class SupplierController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'supplier';
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $supplierQuery = Supplier::query();
        if ($request->getServiceType() !== null) {
            $supplierQuery->whereExists(function (Builder $query) use ($request) {
                $query->selectRaw(1)
                    ->from('supplier_services')
                    ->whereColumn('suppliers.id', 'supplier_services.supplier_id')
                    ->where('supplier_services.type', $request->getServiceType());
            });
        }

        return response()->json(
            Resource::collection($supplierQuery->get())
        );
    }

    protected function supplierParams(Supplier $model): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->custom(
                'cities',
                'Города',
                fn($v, $o) => $o->cities()->get()->map(fn(City $city) => $city->name)->join(', ')
            )
            ->currency('currency_name', 'Валюта')
            ->data($model);
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->currency('currency', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
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
            ->enum('currency', ['text' => 'Валюта', 'enum' => CurrencyEnum::class])
            ->orderBy('name', 'asc');
    }

    protected function prepareShowMenu(Model $model)
    {
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
