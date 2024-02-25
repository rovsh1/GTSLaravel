<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Http\Requests\Supplier\SearchRequest;
use App\Admin\Http\Resources\Supplier as Resource;
use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\SupplierType;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\View\Menus\SupplierMenu;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;

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

    public function currencies(Supplier $supplier): JsonResponse
    {
        $currency = $supplier->currency;

        return response()->json([
            ['id' => $currency->value, 'name' => $currency->name]
        ]);
    }

    protected function supplierParams(Supplier $model): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->text('type_name', 'Тип')
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
            ->select('type_id', [
                'label' => 'Тип',
                'required' => true,
                'emptyItem' => '',
                'items' => SupplierType::get()
            ])
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
            ->setSearchForm($this->searchForm())
            ->text('name', ['text' => 'Наименование', 'route' => $this->prototype->routeName('show'), 'order' => true])
            ->text('type_name', ['text' => 'Тип'])
            ->text('currency_name', ['text' => 'Валюта'])
            ->orderBy('name', 'asc');
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->select('type_id', ['label' => 'Тип', 'emptyItem' => '', 'items' => SupplierType::get()])
            ->currency('currency', ['label' => 'Валюта', 'emptyItem' => '']);
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
