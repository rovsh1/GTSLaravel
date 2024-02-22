<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Actions\Hotel\UpdateContractAction;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Contract;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\Http\Actions\DefaultFormEditAction;
use App\Admin\Support\Http\Actions\DefaultFormStoreAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sdk\Shared\Enum\Contract\StatusEnum;

class ContractController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $query = Contract::whereHotelId($hotel->id);
        $grid = $this->gridFactory($hotel->id)->data($query);

        return Layout::title('Договора')
            ->view('default.grid.grid', [
                'grid' => $grid,
                'hotel' => $hotel,
                'editAllowed' => $this->isUpdateAllowed(),
                'deleteAllowed' => $this->isUpdateAllowed(),
                'createUrl' => $this->isUpdateAllowed() ? route('hotels.contracts.create', $hotel) : null,
            ]);
    }

    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return (new DefaultFormCreateAction($this->formFactory($hotel->id)))
            ->handle('Новый договор');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($hotel->id)))
            ->handle(Contract::class);
    }

    public function edit(Request $request, Hotel $hotel, Contract $contract)
    {
        $this->hotel($hotel);

        return (new DefaultFormEditAction($this->formFactory($hotel->id)))
            ->deletable()
            ->handle($contract);
    }

    public function update(Hotel $hotel, Contract $contract): RedirectResponse
    {
        return (new UpdateContractAction($this->formFactory($hotel->id)))
            ->handle($contract);
    }

    public function destroy(Hotel $hotel, Contract $contract): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($contract);
    }

    public function get(Hotel $hotel, Contract $contract): JsonResponse
    {
        return response()->json($contract->load(['seasons']));
    }

    protected function formFactory(int $hotelId): FormContract
    {
        return Form::name('data')
            ->setOption('enctype', 'multipart/form-data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->dateRange('period', ['label' => 'Период', 'required' => true])
            ->enum('status', ['label' => 'Статус', 'emptyItem' => '', 'enum' => StatusEnum::class, 'required' => true])
            ->file('files', ['label' => 'Документы', 'multiple' => true]);
    }

    protected function gridFactory(int $hotelId): GridContract
    {
        return Grid::paginator(16)
            ->edit(
                fn(Contract $contract) => route(
                    'hotels.contracts.edit',
                    ['hotel' => $hotelId, 'contract' => $contract->id]
                )
            )
            ->text(
                'contract_number',
                ['text' => 'Номер', 'order' => true, 'renderer' => fn($r, $t) => (string)$r]
            )
            ->text('period', ['text' => 'Период', 'renderer' => fn($r, $t) => Format::period($t)])
            ->enum('status', ['text' => 'Статус', 'enum' => StatusEnum::class, 'order' => true])
            ->file('files', ['text' => 'Документы']);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.contracts.index', $hotel), 'Договора');

        Sidebar::submenu(new HotelMenu($hotel, 'contracts'));
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('hotel');
    }
}
