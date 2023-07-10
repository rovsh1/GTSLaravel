<?php

namespace App\Admin\Http\Controllers\ServiceProvider\Service;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\ServiceProvider\SearchServicesRequest;
use App\Admin\Http\Resources\Service as ServiceResource;
use App\Admin\Models\ServiceProvider\AirportService;
use App\Admin\Models\ServiceProvider\Provider;
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
use App\Admin\View\Menus\ServiceProviderMenu;
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
        $this->prototype = Prototypes::get('service-provider');
    }

    public function index(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        $query = AirportService::where('provider_id', $provider->id);
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

    public function create(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider->id)))
            ->handle('Новая услуга');
    }

    public function store(Request $request, Provider $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider->id)))
            ->handle(AirportService::class);
    }

    public function edit(Request $request, Provider $provider, AirportService $servicesAirport): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider->id)))
            ->deletable()
            ->handle($servicesAirport);
    }

    public function update(Provider $provider, AirportService $servicesAirport): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider->id)))
            ->handle($servicesAirport);
    }

    public function destroy(Provider $provider, AirportService $servicesAirport): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($servicesAirport);
    }

    public function search(SearchServicesRequest $request): JsonResponse
    {
        $services = AirportService::whereCity($request->getCityId())->get();

        return response()->json(
            ServiceResource::collection($services)
        );
    }

    protected function formFactory(int $providerId): FormContract
    {
        return Form::name('data')
            ->hidden('provider_id', ['value' => $providerId])
            ->text('name', ['label' => 'Название', 'required' => true])
            ->enum('type', [
                'label' => 'Тип услуги',
                'emptyItem' => '',
                'required' => true,
                'enum' => AirportServiceTypeEnum::class
            ]);
    }

    protected function gridFactory(Provider $provider): GridContract
    {
        return Grid::paginator(16)
            ->enableQuicksearch()
            ->edit(fn($r) => $this->prototype->route('services-airport.edit', [$provider, $r->id]))
            ->text('name', ['text' => 'Название', 'order' => true])
            ->enum('type', ['text' => 'Тип', 'enum' => AirportServiceTypeEnum::class]);
    }

    private function provider(Provider $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('services-airport.index', $provider), 'Услуги');

        Sidebar::submenu(new ServiceProviderMenu($provider, 'services-airport'));
    }
}
