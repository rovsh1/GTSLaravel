<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Enums\Contract\StatusEnum;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Supplier\Contract;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
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
use Module\Shared\Enum\ServiceTypeEnum;

class ContractController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('supplier');
    }

    public function index(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        $query = Contract::whereSupplierId($provider->id);
        $grid = $this->gridFactory($provider)->data($query);

        return Layout::title('Договора')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('contracts.create', $provider)
                    : null,
            ]);
    }

    public function create(Request $request, Supplier $provider): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormCreateAction($this->formFactory($provider->id)))
            ->handle('Новый договор', 'supplier.contract.form.form');
    }

    public function store(Request $request, Supplier $provider): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($provider->id)))
            ->handle(Contract::class);
    }

    public function edit(Request $request, Supplier $provider, Contract $contract): LayoutContract
    {
        $this->provider($provider);

        return (new DefaultFormEditAction($this->formFactory($provider->id)))
            ->deletable()
            ->handle($contract, 'supplier.contract.form.form');
    }

    public function update(Supplier $provider, Contract $contract): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($provider->id)))
            ->handle($contract);
    }

    public function destroy(Supplier $provider, Contract $contract): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($contract);
    }

    protected function formFactory(int $supplierId): FormContract
    {
        return Form::name('data')
            ->setOption('enctype', 'multipart/form-data')
            ->hidden('supplier_id', ['value' => $supplierId])
            ->dateRange('period', ['label' => 'Период', 'required' => true])
            ->enum('status', ['label' => 'Статус', 'emptyItem' => '', 'enum' => StatusEnum::class, 'required' => true])
            ->bookingServiceType('service_type', [
                'label' => 'Тип услуги',
                'required' => true,
                'emptyItem' => '',
                'withoutHotel' => true
            ])
            ->hiddenMultiSelect('service_ids', ['label' => 'Услуги', 'required' => true])
            ->file('documents', ['label' => 'Документы', 'multiple' => true]);
    }

    protected function gridFactory(Supplier $provider): GridContract
    {
        return Grid::paginator(16)
            ->edit(fn($r) => $this->prototype->route('contracts.edit', [$provider, $r->id]))
            ->text(
                'contract_number',
                ['text' => 'Номер', 'order' => true, 'renderer' => fn($r, $t) => (string)$r]
            )
            ->text('period', ['text' => 'Период', 'renderer' => fn($r, $t) => Format::period($t)])
            ->enum('status', ['text' => 'Статус', 'enum' => StatusEnum::class, 'order' => true])
            ->text('service_names', ['text' => 'Услуги', 'renderer' => fn($r, $val) => implode(', ', $val)])
            ->file('documents', ['text' => 'Документы']);
    }

    private function provider(Supplier $provider): void
    {
        Breadcrumb::prototype($this->prototype)
            ->addUrl(
                $this->prototype->route('show', $provider),
                (string)$provider
            )
            ->addUrl($this->prototype->route('contracts.index', $provider), 'Договора');

        Sidebar::submenu(new SupplierMenu($provider, 'contracts'));
    }
}
