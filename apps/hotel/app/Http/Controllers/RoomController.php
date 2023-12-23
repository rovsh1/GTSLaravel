<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Resources\Room as RoomResource;
use App\Hotel\Models\Hotel;
use App\Hotel\Models\Room;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends AbstractHotelController
{
    public function index(Request $request): LayoutContract
    {
        return Layout::title('Номера отеля')
            ->view('rooms.rooms', [
                'hotel' => $this->getHotel(),
                'editAllowed' => false,
                'deleteAllowed' => false,
                'createUrl' => null,
                'rooms' => $this->getHotel()->rooms
            ]);
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
}
