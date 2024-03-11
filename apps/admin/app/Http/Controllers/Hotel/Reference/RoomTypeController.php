<?php

namespace App\Admin\Http\Controllers\Hotel\Reference;

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
            $this->model->all()
        );
    }
}
