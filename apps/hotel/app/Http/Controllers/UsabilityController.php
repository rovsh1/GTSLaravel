<?php

namespace App\Hotel\Http\Controllers;

use App\Admin\Models\Hotel\Reference\Usability;
use App\Admin\Repositories\Hotel\UsabilitiesRepository;
use App\Hotel\Services\HotelService;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsabilityController extends AbstractHotelController
{
    public function __construct(
        HotelService $hotelService,
        private readonly UsabilitiesRepository $repository
    ) {
        parent::__construct($hotelService);
    }

    public function edit(): View
    {
        return view('show._modals.usabilities', [
            'usabilities' => Usability::all(),
            'hotelUsabilities' => $this->getHotel()->usabilities,
            'usabilitiesUrl' => route('hotel.usabilities.update'),
            'rooms' => $this->getHotel()->rooms,
        ]);
    }

    public function update(Request $request): AjaxResponseInterface
    {
        $usabilitiesData = \Arr::get($request->toArray(), 'data.usabilities');

        $usabilityUpdateData = [];
        foreach ($usabilitiesData as $usabilityId => $roomsData) {
            foreach ($roomsData as $roomId => $value) {
                $isForAllRooms = $roomId === 'all' && (bool)$value === true;
                $hasAnotherRooms = count($roomsData) > 1;
                if ($isForAllRooms && $hasAnotherRooms) {
                    continue;
                }
                $usabilityUpdateData[] = [
                    'usability_id' => $usabilityId,
                    'hotel_id' => $this->getHotel()->id,
                    'room_id' => $isForAllRooms ? null : $roomId
                ];
            }
        }

        $this->repository->update($this->getHotel()->id, $usabilityUpdateData);

        return new AjaxReloadResponse();
    }
}
