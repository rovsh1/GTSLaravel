<?php

namespace App\Admin\Http\Controllers\Hotel\Reference;

use App\Admin\Http\Resources\RoomType as Resource;
use App\Admin\Models\Hotel\Reference\RoomType;
use App\Admin\Support\Http\Controllers\AbstractEnumController;
use Illuminate\Http\JsonResponse;

class RoomTypeController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-room-type';
    }

    public function list(): JsonResponse
    {
        return response()->json(
            Resource::collection(
                RoomType::all()
            )
        );
    }
}
