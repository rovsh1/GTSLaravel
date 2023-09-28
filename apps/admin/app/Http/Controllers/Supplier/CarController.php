<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\TransportCar;
use App\Admin\Models\Supplier\Car;
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Car::where('supplier_id', $provider->id);
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

    public function create(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider)))
            ->handle('Новый автомобиль');
    }

    public function store(Request $request, Supplier $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider)))
            ->handle(Car::class);
    }

    public function edit(Request $request, Supplier $provider, Car $car): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider)))
            ->deletable()
            ->handle($car);
    }

    public function update(Supplier $provider, Car $car): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider)))
            ->handle($car);
    }

    public function destroy(Supplier $provider, Car $car): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($car);
    }

    protected function formFactory(Supplier $provider): FormContract
    {
        return Form::name('data')
            ->hidden('supplier_id', ['value' => $provider->id])
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

    protected function gridFactory(Supplier $provider): GridContract
    {
        return Grid::paginator(16)
            ->edit(fn($r) => $this->prototype->route('cars.edit', [$provider, $r->id]))
            ->text('car', ['text' => 'Автомобиль', 'renderer' => fn($v) => (string)$v])
            ->text('cities', [
                'text' => 'Город',
                'renderer' => fn($v) => $v->cities()->get()->map->name->implode(', ')
            ]);
    }

    private function provider(Supplier $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('cars.index', $provider), 'Автомобили');

        Sidebar::submenu(new SupplierMenu($provider, 'cars'));
    }
}