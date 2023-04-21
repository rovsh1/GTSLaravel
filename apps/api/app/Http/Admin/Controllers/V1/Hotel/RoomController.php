<?php

namespace App\Api\Http\Admin\Controllers\V1\Hotel;

use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Core\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function get(Request $request, Hotel $hotel, Room $room): JsonResponse
    {
        return response()->json($room);
    }
}
