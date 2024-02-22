<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Supplier\Requisite;
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RequisiteController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Requisite::where('supplier_id', $provider->id);
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Реквизиты')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('requisites.create', $provider)
                    : null,
            ]);
    }

    public function create(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider->id)))
            ->handle('Новые реквизиты');
    }

    public function store(Request $request, Supplier $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider->id)))
            ->handle(Requisite::class);
    }

    public function edit(Request $request, Supplier $provider, Requisite $requisite): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider->id)))
            ->deletable()
            ->handle($requisite);
    }

    public function update(Supplier $provider, Requisite $requisite): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider->id)))
            ->handle($requisite);
    }

    public function destroy(Supplier $provider, Requisite $requisite): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($requisite);
    }

    protected function formFactory(int $supplierId): FormContract
    {
        return Form::name('data')
            ->hidden('supplier_id', ['value' => $supplierId])
            ->text('inn', ['label' => 'ИНН', 'required' => true])
            ->text('director_full_name', ['label' => 'Ф.И.О. директора', 'required' => true]);
    }

    protected function gridFactory(Supplier $provider): GridContract
    {
        return Grid::paginator(16)
            ->edit(fn($r) => $this->prototype->route('requisites.edit', [$provider, $r->id]))
            ->text('inn', ['text' => 'ИНН'])
            ->text('director_full_name', ['text' => 'Ф.И.О. директора']);
    }

    private function provider(Supplier $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('requisites.index', $provider), 'Реквизиты');

        Sidebar::submenu(new SupplierMenu($provider, 'requisites'));
    }
}
