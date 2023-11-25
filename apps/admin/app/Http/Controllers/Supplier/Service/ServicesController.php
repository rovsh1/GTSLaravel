<?php

namespace App\Admin\Http\Controllers\Supplier\Service;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Supplier\SearchServicesRequest;
use App\Admin\Http\Resources\Airport;
use App\Admin\Http\Resources\City;
use App\Admin\Http\Resources\Service as ServiceResource;
use App\Admin\Http\Resources\Supplier as SupplierResource;
use App\Admin\Models\Supplier\Service;
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
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Sdk\Shared\Enum\ServiceTypeEnum;

class ServicesController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Service::where('supplier_id', $provider->id);
        if ($request->has('quicksearch')) {
            $query->quicksearch($request->get('quicksearch'));
        }
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Услуги')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('services.create', $provider)
                    : null,
            ]);
    }

    public function create(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider->id)))
            ->handle('Новая услуга')
            ->view('supplier.service.form.form', [
                'cancelUrl' => $this->prototype->route('services.index', $provider),
                'formActionUrl' => $this->prototype->route('services.store', $provider),
                'supplier' => SupplierResource::make($provider),
                'airports' => Airport::collection($provider->airports()->get()),
                'cities' => City::collection($provider->cities()->get())
            ]);
    }

    public function store(Request $request, Supplier $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider->id)))
            ->handle(Service::class);
    }

    public function edit(Request $request, Supplier $provider, Service $service): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider->id)))
            ->deletable()
            ->handle($service)
            ->view('supplier.service.form.form', [
                'formActionUrl' => $this->prototype->route('services.update', [$provider, $service]),
                'service' => $service,
                'supplier' => SupplierResource::make($provider),
                'airports' => Airport::collection($provider->airports()->get()),
                'cities' => City::collection($provider->cities()->get())
            ]);
    }

    public function update(Supplier $provider, Service $service): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider->id)))
            ->handle($service);
    }

    public function destroy(Supplier $provider, Service $service): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($service);
    }

    public function search(Supplier $supplier, SearchServicesRequest $request): JsonResponse
    {
        $servicesQuery = Service::whereType($request->getType())->whereSupplierId($supplier->id);
        if ($request->onlyWithContract()) {
            $servicesQuery->whereHasActiveContract();
        }

        return response()->json(
            ServiceResource::collection($servicesQuery->get())
        );
    }

    public function list(int $serviceType): JsonResponse
    {
        Validator::make(
            ['service_type' => $serviceType],
            ['service_type' => new Enum(ServiceTypeEnum::class)]
        )->validate();

        $services = Service::whereType($serviceType)->get();

        return response()->json(
            ServiceResource::collection($services)
        );
    }

    protected function formFactory(int $supplierId): FormContract
    {
        return Form::name('data')
            ->hidden('supplier_id', ['value' => $supplierId])
            ->text('title', ['label' => 'Название', 'required' => true])
            ->bookingServiceType('type', [
                'label' => 'Тип услуги',
                'emptyItem' => '',
                'required' => true,
                'withoutHotel' => true
            ])
            ->hidden('data');
    }

    protected function gridFactory(Supplier $provider): GridContract
    {
        return Grid::paginator(16)
            ->enableQuicksearch()
            ->edit(fn($r) => $this->prototype->route('services.edit', [$provider, $r->id]))
            ->text('title', ['text' => 'Название', 'order' => true])
            ->enum('type', ['text' => 'Тип', 'enum' => ServiceTypeEnum::class]);
    }

    private function provider(Supplier $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('services.index', $provider), 'Услуги');

        Sidebar::submenu(new SupplierMenu($provider, 'services'));
    }
}
