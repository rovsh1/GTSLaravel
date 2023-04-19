<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Enums\ServiceProvider\ServiceTypeEnum;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\ServiceProvider\Provider;
use App\Admin\Models\ServiceProvider\Service;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\Http\Actions\DefaultFormEditAction;
use App\Admin\Support\Http\Actions\DefaultFormStoreAction;
use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\ServiceProviderMenu;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('service-provider');
    }

    public function index(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Service::where('provider_id', $provider->id);
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Услуги поставщика')
            ->style('default/grid')
            ->view('default.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('services.create', $provider)
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
            ->handle(Service::class);
    }

    public function edit(Request $request, Provider $provider, Service $service): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider->id)))
            ->deletable()
            ->handle($service);
    }

    public function update(Provider $provider, Service $service): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider->id)))
            ->handle($service);
    }

    public function destroy(Provider $provider, Service $service): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($service);
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
                'enum' => ServiceTypeEnum::class
            ]);
    }

    protected function gridFactory(Provider $provider): GridContract
    {
        return Grid::paginator(16)
            ->enableQuicksearch()
            ->edit(fn($r) => $this->prototype->route('services.edit', [$provider, $r->id]))
            ->text('name', ['text' => 'Название', 'order' => true])
            ->enum('type', ['text' => 'Тип', 'enum' => ServiceTypeEnum::class]);
    }

    private function provider(Provider $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('services.index', $provider), 'Услуги');

        Sidebar::submenu(new ServiceProviderMenu($provider, 'services'));
    }
}
