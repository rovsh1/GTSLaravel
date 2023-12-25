<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Resources\Room;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use App\Hotel\View\Components\HotelRating;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sdk\Shared\Enum\Hotel\StatusEnum;
use Sdk\Shared\Enum\Hotel\VisibilityEnum;

class HotelController extends AbstractHotelController
{
    public function index(): LayoutContract
    {
        return Layout::title($this->getPageHeader())
            ->view('show.show', [
                'model' => $this->getHotel(),
                'editUrl' => null,
                'deleteUrl' => null,
                ...$this->getShowViewData()
            ]);
    }

    public function get(Request $request): JsonResponse
    {
        return response()->json(
            $this->getHotel()
        );
    }

    public function getRooms(Request $request): JsonResponse
    {
        return response()->json(
            Room::collection($this->getHotel()->rooms)
        );
    }

    public function update(Request $request): AjaxResponseInterface
    {
        $hotel = $this->getHotel();

        $hotel->update($request->toArray());

        return new AjaxSuccessResponse();
    }

    protected function getShowViewData(): array
    {
        return [
            'params' => $this->hotelParams($this->getHotel()),

            'notesUrl' => route('hotel.notes.edit'),

            'hotelServices' => $this->getHotel()->services,
            'servicesEditable' => true,
            'servicesUrl' => route('hotel.services.edit'),

            'hotelUsabilities' => $this->getHotel()->usabilities,
            'usabilitiesUrl' => route('hotel.usabilities.edit'),
            'usabilitiesEditable' => true,
        ];
    }

    private function hotelParams($model): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->text('type_name', 'Категория')
            ->enum('visibility', 'Видимость', VisibilityEnum::class)
            ->enum('status', 'Статус', StatusEnum::class)
            ->custom('rating', 'Рейтинг', fn($v) => (new HotelRating($v))->render())
            ->custom('country_name', 'Страна', fn($v, $o) => "{$o['country_name']} / {$o['city_name']}")
            ->text('address', 'Адрес')
            ->text('zipcode', 'Индекс')
            ->date('created_at', 'Создан', ['format' => 'datetime'])
            ->data($model);
    }
}