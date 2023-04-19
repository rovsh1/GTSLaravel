<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Enums\ServiceProvider\ServiceTypeEnum;
use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\ServiceProvider\Provider;
use App\Admin\Models\ServiceProvider\Service;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\ServiceProviderMenu;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxRedirectResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Service::where('provider_id', $provider->id);
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Услуги поставщика')
            ->style('default/grid')
            ->view('default.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'paginator' => $grid->getPaginator(),
                'grid' => $grid,
                'createUrl' => $this->isUpdateAllowed() ? route('service-provider.services.create', $provider) : null,
            ]);
    }

    public function create(Request $request, Provider $provider): LayoutContract
    {
        $this->provider($provider);

        Breadcrumb::add('Новая услуга');

        $form = $this->formFactory($provider->id)
            ->method('post')
            ->action(route('service-provider.services.store', $provider));

        return Layout::title('Новая услуга')
            ->view('default.form', [
                'form' => $form,
                'cancelUrl' => route('service-provider.services.index', $provider)
            ]);
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Provider $provider): RedirectResponse
    {
        $form = $this->formFactory($provider->id)
            ->method('post');

        $form->trySubmit(route('service-provider.services.create', $provider));

        Service::create($form->getData());

        return redirect(route('service-provider.services.index', $provider));
    }

    public function edit(Request $request, Provider $provider, Service $service)
    {
        $this->provider($provider);

        Breadcrumb::add((string)$service);

        $form = $this->formFactory($provider->id)
            ->method('put')
            ->action(route('service-provider.services.update', [$provider, $service]))
            ->data($service);

        return Layout::title((string)$service)
            ->view('default.form', [
                'form' => $form,
                'cancelUrl' => route('service-provider.services.index', $provider),
            ]);
    }

    public function update(Provider $provider, Service $service): RedirectResponse
    {
        $form = $this->formFactory($provider->id)
            ->method('put');

        $form->trySubmit(route('service-provider.services.edit', [$provider, $service]));

        $service->update($form->getData());

        return redirect(route('service-provider.services.index', $provider));
    }

    public function destroy(Provider $provider, Service $service): AjaxResponseInterface
    {
        try {
            $service->delete();
        } catch (\Throwable $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxRedirectResponse(route('service-provider.services.index', $provider));
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
            ->edit([
                'route' => fn($r) => route('service-provider.services.edit', [$provider, $r->id])
            ])
            ->enum('type', ['text' => 'Тип', 'enum' => ServiceTypeEnum::class])
            ->text('name', ['text' => 'Название', 'order' => true]);
    }

    private function provider(Provider $provider): void
    {
        Breadcrumb::prototype('service-provider')
            ->addUrl(
                route('service-provider.show', $provider),
                (string)$provider
            )
            ->addUrl(route('service-provider.services.index', $provider), 'Услуги');

        Sidebar::submenu(new ServiceProviderMenu($provider, 'services'));
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('service-provider');
    }
}
