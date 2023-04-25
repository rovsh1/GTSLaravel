<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Enums\Hotel\Contract\StatusEnum;
use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Contract;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Season;
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
use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $query = Season::whereHotelId($hotel->id);
        $grid = $this->gridFactory($hotel->id)->data($query);

        return Layout::title('Сезоны')
            ->view('default.grid.grid', [
                'grid' => $grid,
                'hotel' => $hotel,
                'editAllowed' => $this->isUpdateAllowed(),
                'deleteAllowed' => $this->isUpdateAllowed(),
                'createUrl' => $this->isUpdateAllowed() ? route('hotels.seasons.create', $hotel) : null,
            ]);
    }

    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return (new DefaultFormCreateAction($this->formFactory($hotel->id)))
            ->handle('Новый сезон')
            ->view('hotel.season.form.form');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($hotel->id)))
            ->handle(Season::class);
    }

    public function edit(Request $request, Hotel $hotel, Season $season)
    {
        $this->hotel($hotel);

        return (new DefaultFormEditAction($this->formFactory($hotel->id)))
            ->deletable()
            ->handle($season)
            ->view('hotel.season.form.form');
    }

    public function update(Hotel $hotel, Season $season): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($hotel->id)))
            ->handle($season);
    }

    public function destroy(Hotel $hotel, Season $season): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($season);
    }

    protected function formFactory(int $hotelId): FormContract
    {
        return Form::name('data')
            ->select('contract_id', ['label' => 'Договор', 'items' => $this->getContracts($hotelId), 'emptyItem' => '', 'required' => true])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->dateRange('period', ['label' => 'Период', 'required' => true]);
    }

    protected function gridFactory(int $hotelId): GridContract
    {
        return Grid::paginator(16)
            ->edit(
                fn(Season $season) => route(
                    'hotels.seasons.edit',
                    ['hotel' => $hotelId, 'season' => $season->id]
                )
            )
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('period', ['text' => 'Период', 'renderer' => fn($r, $t) => Format::period($t)])
            ->text('contract_number', ['text' => 'Договор', 'renderer' => fn($r, $t) => Format::contractNumber($t)])
            ->enum('contract_status', ['text' => 'Статус', 'enum' => StatusEnum::class, 'order' => true]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.seasons.index', $hotel), 'Сезоны');

        Sidebar::submenu(new HotelMenu($hotel, 'seasons'));
    }

    private function getContracts(int $hotelId): Collection
    {
        return Contract::whereHotelId($hotelId)
            ->get()
            ->map(function (Contract $contract) {
                $contract->name = Format::contractNumber($contract->id);
                return $contract;
            });
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('hotel');
    }
}
