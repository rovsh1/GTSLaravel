<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\TransportCar;
use App\Admin\Models\ServiceProvider\Car;
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

class CarController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Car::where('provider_id', $provider->id);
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Автомобили')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('cars.create', $provider)
                    : null,
            ]);
    }

    public function create(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider)))
            ->handle('Новый автомобиль');
    }

    public function store(Request $request, Provider $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider)))
            ->handle(Car::class);
    }

    public function edit(Request $request, Provider $provider, Car $car): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider)))
            ->deletable()
            ->handle($car);
    }

    public function update(Provider $provider, Car $car): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider)))
            ->handle($car);
    }

    public function destroy(Provider $provider, Car $car): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($car);
    }

    protected function formFactory(Provider $provider): FormContract
    {
        return Form::name('data')
            ->hidden('provider_id', ['value' => $provider->id])
            ->select('car_id', [
                'label' => 'Автомобиль',
                'required' => true,
                'emptyItem' => '',
                'items' => TransportCar::get()->map(fn($r) => [
                    'value' => $r->id,
                    'text' => (string)$r
                ])
            ])
            ->select('city_ids', [
                'label' => 'Город',
                'multiple' => true,
                'items' => $provider->cities()->get()
            ]);
    }

    protected function gridFactory(Provider $provider): GridContract
    {
        return Grid::paginator(16)
            ->edit(fn($r) => $this->prototype->route('cars.edit', [$provider, $r->id]))
            ->text('car', ['text' => 'Автомобиль', 'renderer' => fn($v) => (string)$v])
            ->text('cities', [
                'text' => 'Город',
                'renderer' => fn($v) => $v->cities()->get()->map->name->implode(', ')
            ]);
    }

    private function provider(Provider $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('cars.index', $provider), 'Автомобили');

        Sidebar::submenu(new ServiceProviderMenu($provider, 'cars'));
    }
}
