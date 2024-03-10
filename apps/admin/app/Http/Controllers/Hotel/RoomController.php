<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\SearchRoomsRequest;
use App\Admin\Http\Resources\Room as RoomResource;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\BedType;
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
use App\Shared\Http\Responses\AjaxRedirectResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return Layout::title('Номера отеля')
            ->view('hotel.rooms.rooms', [
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

        $form->submitOrFail(route('hotels.rooms.create', $hotel));

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
                'text' => $room->text,
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

        $form->submitOrFail(route('hotels.rooms.edit', [$hotel, $room]));

        $data = $form->getData();
        $room->update($data);
        $room->updateBeds($data['beds'] ?? []);

        return redirect(route('hotels.rooms.index', $hotel));
    }

    public function destroy(Request $request, Hotel $hotel, Room $room): AjaxResponseInterface
    {
        $room->delete();

        return new AjaxRedirectResponse(route('hotels.rooms.index', $hotel));
    }

    public function position(Request $request, Hotel $hotel): array
    {
        $hotel->updateRoomsPositions($request->input('indexes'));

        return [];
    }

    public function get(Request $request, Hotel $hotel, Room $room): JsonResponse
    {
        return response()->json(RoomResource::make($room));
    }

    public function getRoomNames(Request $request, string $lang): JsonResponse
    {
        return response()->json(Room::getRoomNames($lang));
    }

    public function search(SearchRoomsRequest $request): JsonResponse
    {
        $roomsQuery = Room::query();
        if ($request->getHotelIds() !== null) {
            $roomsQuery->whereIn('hotel_rooms.hotel_id', $request->getHotelIds());
        }

        return response()->json(
            RoomResource::collection($roomsQuery->get())
        );
    }

    private function formFactory(): FormContract
    {
        return Form::hidden('beds')
            ->select('type_id', [
                'label' => 'Тип номера',
                'required' => true,
                'items' => RoomType::get(),
                'emptyItem' => ''
            ])
            ->localeText(
                'name',
                ['label' => 'Наименование', 'required' => true]
            )
            ->hidden('text')
            ->number('rooms_number', ['label' => 'Кол-во номеров', 'required' => true])
            ->number('guests_count', ['label' => 'Вместимость номера', 'required' => true])
            ->number('square', ['label' => 'Площадь']);
    }

    private function formLayout($form): LayoutContract
    {
        return Layout::view('hotel.room-form.room-form', [
            'form' => $form,
            'bedTypes' => BedType::get()->map(fn($r) => ['id' => $r->id, 'name' => $r->name])
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
