<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\PriceRate;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $query = PriceRate::whereHotelId($hotel->id);
        $grid = $this->gridFactory($hotel->id)->data($query);

        return Layout::title('Тарифы')
            ->view('default.grid.grid', [
                'grid' => $grid,
                'hotel' => $hotel,
                'editAllowed' => $this->isUpdateAllowed(),
                'deleteAllowed' => $this->isUpdateAllowed(),
                'createUrl' => $this->isUpdateAllowed() ? route('hotels.rates.create', $hotel) : null,
            ]);
    }

    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return (new DefaultFormCreateAction($this->formFactory($hotel->id)))
            ->handle('Новый тариф');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($hotel->id)))
            ->handle(PriceRate::class);
    }

    public function edit(Request $request, Hotel $hotel, PriceRate $rate)
    {
        $this->hotel($hotel);

        return (new DefaultFormEditAction($this->formFactory($hotel->id)))
            ->deletable()
            ->handle($rate);
    }

    public function update(Hotel $hotel, PriceRate $rate): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($hotel->id)))
            ->handle($rate);
    }

    public function destroy(Hotel $hotel, PriceRate $rate): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($rate);
    }

    protected function formFactory(int $hotelId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->localeText('name', ['label' => 'Наименование', 'required' => true])
            ->localeTextarea('description', ['label' => 'Описание', 'required' => true])
            ->select(
                'room_ids',
                ['label' => 'Комнаты', 'multiple' => true, 'items' => Room::whereHotelId($hotelId)->get()]
            );
    }

    protected function gridFactory(int $hotelId): GridContract
    {
        return Grid::paginator(16)
            ->edit(
                fn(PriceRate $rate) => route(
                    'hotels.rates.edit',
                    ['hotel' => $hotelId, 'rate' => $rate->id]
                )
            )
            ->text('name', ['text' => 'Наименование', 'order' => true]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.rates.index', $hotel), 'Тарифы');

        Sidebar::submenu(new HotelMenu($hotel, 'rates'));
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('hotel');
    }
}
