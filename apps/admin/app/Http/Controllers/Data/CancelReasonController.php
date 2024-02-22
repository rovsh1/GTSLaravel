<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Models\Reference\CancelReason;
use App\Admin\Support\Http\Controllers\AbstractEnumController;
use Illuminate\Http\JsonResponse;

class CancelReasonController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'cancel-reason';
    }

    public function list(): JsonResponse
    {
        return response()->json(
            CancelReason::all()
        );
    }
}
