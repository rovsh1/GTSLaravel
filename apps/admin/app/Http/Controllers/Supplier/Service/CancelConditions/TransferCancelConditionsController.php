<?php

namespace App\Admin\Http\Controllers\Supplier\Service\CancelConditions;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Supplier\GetTransferCancelConditionsRequest;
use App\Admin\Http\Requests\Supplier\UpdateTransferCancelConditionsRequest;
use App\Admin\Models\Supplier\Service;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Facades\Supplier\CarsAdapter;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\SupplierMenu;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransferCancelConditionsController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return Layout::title('Условия отмены транспорт')
            ->view('supplier.service.cancel-conditions.transfer.index', [
                'provider' => $provider,
                'cars' => $provider->cars()->get(),
                'seasons' => $provider->seasons,
                'services' => $provider->transferServices,
                'quicksearch' => Grid::enableQuicksearch()->getQuicksearch()
            ]);
    }

    public function get(GetTransferCancelConditionsRequest $request, Supplier $provider, Service $service): JsonResponse
    {
        $cancelConditions = CarsAdapter::getCancelConditions(
            $provider->id,
            $request->getSeasonId(),
            $service->id,
            $request->getCarId()
        );

        return response()->json($cancelConditions);
    }

    public function update(
        UpdateTransferCancelConditionsRequest $request,
        Supplier $provider,
        Service $service
    ): AjaxResponseInterface {
        CarsAdapter::updateCancelConditions(
            $provider->id,
            $request->getSeasonId(),
            $service->id,
            $request->getCarId(),
            $request->getCancelConditions()
        );

        return new AjaxSuccessResponse();
    }

    protected function provider(Supplier $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            );
        Breadcrumb::add('Условия отмены транспорт');

        Sidebar::submenu(new SupplierMenu($provider, 'cancel_conditions'));
    }
}
