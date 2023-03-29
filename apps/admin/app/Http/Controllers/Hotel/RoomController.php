<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\BedType;
use App\Admin\Models\Hotel\Reference\RoomName;
use App\Admin\Models\Hotel\Reference\RoomType;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return Layout::title('Номера отеля')
            ->ss('hotel/rooms')
            ->view('hotel.rooms', [
                'hotel' => $hotel,
                'editAllowed' => true,
                'deleteAllowed' => true,
                'createUrl' => Acl::isCreateAllowed('hotel') ? route('hotels.rooms.create', $hotel) : null,
                'rooms' => $hotel->rooms
            ]);
    }

    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        Breadcrumb::add('Новый номер');

        $form = $this->formFactory()
            ->method('post')
            ->action(route('hotels.rooms.store', $hotel));

        return $this->formLayout($form)
            ->title('Новый номер');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post');

        $form->trySubmit(route('hotels.rooms.create', $hotel));

        $data = $form->getData();
        $data['hotel_id'] = $hotel->id;
        Room::create($data);

        return redirect(route('hotels.rooms.index', $hotel));
    }

    public function edit(Request $request, Hotel $hotel, Room $room): LayoutContract
    {
        $this->hotel($hotel);

        Breadcrumb::add((string)$room);

        $form = $this->formFactory()
            ->method('put')
            ->action(route('hotels.rooms.update', [$hotel, $room]))
            ->data($room);

        return $this->formLayout($form)
            ->title((string)$room)
            ->data([
                'cancelUrl' => route('hotels.rooms.index', $hotel),
                'deleteUrl' => route('hotels.rooms.destroy', [$hotel, $room])
            ]);
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function update(Request $request, Hotel $hotel, Room $room): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('put');

        $form->trySubmit(route('hotels.rooms.edit', [$hotel, $room]));

        $data = $form->getData();
        $room->update($data);
        $room->updateBeds($data['beds']);

        return redirect(route('hotels.rooms.index', $hotel));
    }

    public function destroy(Request $request, Hotel $hotel, Room $room): RedirectResponse
    {
        $room->delete();

        return redirect(route('hotels.rooms.index', $hotel));
    }

    private function formFactory(): FormContract
    {
        return Form::select('type_id', [
            'label' => 'Тип номера',
            'required' => true,
            'items' => RoomType::get(),
            'emptyItem' => ''
        ])
            ->select('name_id', [
                'label' => 'Наименование',
                'required' => true,
                'items' => RoomName::get(),
                'emptyItem' => ''
            ])
            ->text('custom_name', ['label' => 'Наименование (уникальное)', 'hint' => 'Внутреннее наименования для отеля'])
            ->number('rooms_number', ['label' => 'Кол-во номеров', 'required' => true])
            ->number('guests_number', ['label' => 'Вместимость номера', 'required' => true])
            ->number('square', ['label' => 'Площадь'])
            ->hidden('beds');
    }

    private function formLayout($form): LayoutContract
    {
        return Layout::addMetaVariable('bed_types', BedType::get()->map(fn($r) => ['id' => $r->id, 'name' => $r->name]))
            ->ss('hotel/room-form')
            ->view('hotel.room-form', [
                'form' => $form
            ]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.rooms.index', $hotel), 'Номера');

        Sidebar::submenu(new HotelMenu($hotel, 'rooms'));
    }
}
