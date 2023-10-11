<?php

namespace App\Admin\Http\Controllers\Supplier\Service;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\ServiceProvider\DeprecatedSearchServicesRequest;
use App\Admin\Http\Resources\DeprecatedService as ServiceResource;
use App\Admin\Models\Supplier\AirportService;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\Http\Actions\DefaultFormEditAction;
use App\Admin\Support\Http\Actions\DefaultFormStoreAction;
use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\SupplierMenu;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Shared\Enum\Booking\AirportServiceTypeEnum;

class AirportServicesController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        $query = AirportService::where('supplier_id', $provider->id);
        if ($request->has('quicksearch')) {
            $query->quicksearch($request->get('quicksearch'));
        }
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Услуги аэропорт')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('services-airport.create', $provider)
                    : null,
            ]);
    }

    public function create(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider->id)))
            ->handle('Новая услуга');
    }

    public function store(Request $request, Supplier $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider->id)))
            ->handle(AirportService::class);
    }

    public function edit(Request $request, Supplier $provider, AirportService $servicesAirport): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider->id)))
            ->deletable()
            ->handle($servicesAirport);
    }

    public function update(Supplier $provider, AirportService $servicesAirport): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider->id)))
            ->handle($servicesAirport);
    }

    public function destroy(Supplier $provider, AirportService $servicesAirport): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($servicesAirport);
    }

    public function search(DeprecatedSearchServicesRequest $request): JsonResponse
    {
        $services = AirportService::whereCity($request->getCityId())->get();

        return response()->json(
            ServiceResource::collection($services)
        );
    }

    public function list(Supplier $supplier): JsonResponse
    {
        $services = AirportService::whereSupplierId($supplier->id)->get();

        return response()->json(
            ServiceResource::collection($services)
        );
    }

    protected function formFactory(int $supplierId): FormContract
    {
        return Form::name('data')
            ->hidden('supplier_id', ['value' => $supplierId])
            ->text('name', ['label' => 'Название', 'required' => true])
            ->enum('type', [
                'label' => 'Тип услуги',
                'emptyItem' => '',
                'required' => true,
                'enum' => AirportServiceTypeEnum::class
            ]);
    }

    protected function gridFactory(Supplier $provider): GridContract
    {
        return Grid::paginator(16)
            ->enableQuicksearch()
            ->edit(fn($r) => $this->prototype->route('services-airport.edit', [$provider, $r->id]))
            ->text('name', ['text' => 'Название', 'order' => true])
            ->enum('type', ['text' => 'Тип', 'enum' => AirportServiceTypeEnum::class]);
    }

    private function provider(Supplier $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('services-airport.index', $provider), 'Услуги');

        Sidebar::submenu(new SupplierMenu($provider, 'services-airport'));
    }
}
