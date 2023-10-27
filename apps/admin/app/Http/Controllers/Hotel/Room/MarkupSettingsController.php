<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Hotel\Room;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Hotel\MarkupSettingsAdapter;
use Illuminate\Http\JsonResponse;

class MarkupSettingsController extends Controller
{
    public function get(Hotel $hotel, Room $room): JsonResponse
    {
        $roomSettings = MarkupSettingsAdapter::getRoomMarkupSettings($hotel->id, $room->id);

        return response()->json(
            $roomSettings
        );
    }
}
