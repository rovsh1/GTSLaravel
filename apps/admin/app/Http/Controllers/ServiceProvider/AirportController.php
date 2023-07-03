<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Reference\Airport as AirportReference;
use App\Admin\Models\ServiceProvider\Airport;
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('service-provider');
    }

    public function index(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Airport::where('provider_id', $provider->id);
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Аэропорты')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('airports.create', $provider)
                    : null,
            ]);
    }

    public function create(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider)))
            ->handle('Добавить аэропорт');
    }

    public function store(Request $request, Provider $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider)))
            ->handle(Airport::class);
    }

    public function edit(Request $request, Provider $provider, Airport $airport): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider)))
            ->deletable()
            ->handle($airport);
    }

    public function update(Provider $provider, Airport $airport): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider)))
            ->handle($airport);
    }

    public function destroy(Provider $provider, Airport $airport): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($airport);
    }

    protected function formFactory(Provider $provider): FormContract
    {
        return Form::name('data')
            ->hidden('provider_id', ['value' => $provider->id])
            ->select('airport_id', [
                'label' => 'Аэропорт',
                'required' => true,
                'emptyItem' => '',
                'items' => AirportReference::whereIn('r_airports.id', $provider->cities)
                    ->get()
                    ->map(fn($r) => [
                        'value' => $r->id,
                        'text' => (string)$r
                    ])
            ]);
    }

    protected function gridFactory(Provider $provider): GridContract
    {
        return Grid::paginator(16)
            ->edit(fn($r) => $this->prototype->route('airports.edit', [$provider, $r->id]))
            ->text('airport_name', ['text' => 'Аэропорт'])
            ->text('city_name', ['text' => 'Город']);
    }

    private function provider(Provider $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('airports.index', $provider), 'Аэропорты');

        Sidebar::submenu(new ServiceProviderMenu($provider, 'airports'));
    }
}
