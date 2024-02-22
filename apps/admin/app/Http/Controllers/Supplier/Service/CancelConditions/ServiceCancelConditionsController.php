<?php

namespace App\Admin\Http\Controllers\Supplier\Service\CancelConditions;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Supplier\GetServiceCancelConditionsRequest;
use App\Admin\Http\Requests\Supplier\UpdateServiceCancelConditionsRequest;
use App\Admin\Http\Resources\Supplier\TransferCancelConditions as Resource;
use App\Admin\Models\Supplier\Service;
use App\Admin\Models\Supplier\ServiceCancelConditions;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Facades\Supplier\ServiceAdapter;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\SupplierMenu;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceCancelConditionsController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Supplier $provider, string $type): LayoutContract
    {
        $this->checkServiceType($type);
        $this->provider($provider, $type);

        $servicesGetter = "{$type}Services";
        $header = $type === 'airport' ? 'Аэропорт' : 'Прочие';

        return Layout::title($header)
            ->view('supplier.service.cancel-conditions.service.index', [
                'provider' => $provider,
                'cars' => $provider->cars()->get(),
                'seasons' => $provider->seasons,
                'services' => $provider->$servicesGetter,
                'quicksearch' => Grid::enableQuicksearch()->getQuicksearch()
            ]);
    }

    public function getAll(Supplier $provider, string $type): JsonResponse
    {
        $this->checkServiceType($type);
        $cancelConditions = ServiceCancelConditions::whereSupplierId($provider->id)->get();

        return response()->json(
            Resource::collection($cancelConditions)
        );
    }

    public function get(
        GetServiceCancelConditionsRequest $request,
        Supplier $provider,
        string $type,
        Service $service
    ): JsonResponse {
        $this->checkServiceType($type);
        $cancelConditions = ServiceAdapter::getCancelConditions(
            $request->getSeasonId(),
            $service->id,
        );

        return response()->json($cancelConditions);
    }

    public function update(
        UpdateServiceCancelConditionsRequest $request,
        Supplier $provider,
        string $type,
        Service $service
    ): AjaxResponseInterface {
        $this->checkServiceType($type);
        ServiceAdapter::updateCancelConditions(
            $request->getSeasonId(),
            $service->id,
            $request->getCancelConditions()
        );

        return new AjaxSuccessResponse();
    }

    private function provider(Supplier $provider, string $type): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            );
        Breadcrumb::add('Условия отмены');

        Sidebar::submenu(new SupplierMenu($provider, $type));
    }

    private function checkServiceType(string $type): void
    {
        if (!in_array($type, ['airport', 'other'])) {
            throw new NotFoundHttpException('Unknown service type');
        }
    }
}
