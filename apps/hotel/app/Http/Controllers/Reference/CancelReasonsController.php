<?php

declare(strict_types=1);

namespace App\Hotel\Http\Controllers\Reference;

use App\Hotel\Models\Reference\CancelReason;
use App\Hotel\Support\Http\AbstractController;
use Illuminate\Http\JsonResponse;

class CancelReasonsController extends AbstractController
{
    public function list(): JsonResponse
    {
        return response()->json(
            CancelReason::all()
        );
    }
}
