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
    private Provider $provider;

    public function index(Request $request, int $providerId): LayoutContract
    {
        $this->provider($providerId);

        $query = Service::query()->where('provider_id', $providerId);
        $grid = $this->gridFactory()->data($query);

        return Layout::title('Услуги поставщика')
            ->style('default/grid')
            ->view('default.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'paginator' => $grid->getPaginator(),
                'grid' => $grid,
                'editAllowed' => $this->isUpdateAllowed(),
                'deleteAllowed' => $this->isUpdateAllowed(),
                'createUrl' => $this->isUpdateAllowed() ? route('service-provider.services.create', $providerId) : null,
            ]);
    }

    public function create(Request $request, int $providerId): LayoutContract
    {
        $this->provider($providerId);

        Breadcrumb::add('Новый сотрудник');

        $form = $this->formFactory($providerId)
            ->method('post')
            ->action(route('service-provider.services.store', $providerId));

        return Layout::title('Новая услуга')
            ->style('default/form')
            ->view('default.form', [
                'form' => $form,
                'cancelUrl' => route('service-provider.services.index', $providerId)
            ]);
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, int $providerId): RedirectResponse
    {
        $form = $this->formFactory($providerId)
            ->method('post');

        $form->trySubmit(route('service-provider.services.create', $providerId));

        $data = $form->getData();
        Service::create($data);

        return redirect(route('service-provider.services.index', $providerId));
    }

    public function edit(Request $request, Provider $hotel, Service $service)
    {
        $this->provider($hotel);

        Breadcrumb::add($service->fullname);

        $form = $this->formFactory($hotel->id)
            ->method('post')
            ->action(route('service-provider.services.update', ['hotel' => $hotel, 'services' => $service]))
            ->data($service);

        return Layout::title($service->fullname)
            ->style('default/form')
            ->script('hotel/services/main')
            ->view('hotel.services.edit', [
                'form' => $form,
                'cancelUrl' => route('service-provider.services.index', $hotel),
                'contacts' => $service->contacts,
                'contactsUrl' => $this->isUpdateAllowed()
                    ? route(
                        'service-provider.services.contacts.index',
                        ['hotel' => $hotel, 'services' => $service]
                    )
                    : null,
                'createUrl' => $this->isUpdateAllowed()
                    ? route(
                        'service-provider.services.contacts.create',
                        ['hotel' => $hotel, 'services' => $service]
                    )
                    : null,
                'deleteUrl' => $this->isUpdateAllowed()
                    ? route(
                        'service-provider.services.destroy',
                        ['hotel' => $hotel, 'services' => $service]
                    )
                    : null,
            ]);
    }

    public function destroy(Provider $hotel, Service $service): AjaxResponseInterface
    {
        try {
            $service->delete();
        } catch (\Throwable $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxRedirectResponse(route('service-provider.services.index', $hotel));
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

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(16)
            ->enableQuicksearch()
            ->text('name', ['text' => 'Название', 'order' => true]);
    }

    private function provider(int $providerId): void
    {
        $this->provider = Provider::find($providerId);
        if (!$this->provider) {
            abort(404);
        }

        Breadcrumb::prototype('service-provider')
            ->addUrl(
                route('service-provider.show', $this->provider),
                (string)$this->provider
            )//->addUrl(route('service-provider.services.index', $provider), 'Сотрудники')
        ;

        Sidebar::submenu(new ServiceProviderMenu($this->provider, 'services'));
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('service-provider');
    }
}
