<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CancelConditionController extends Controller
{
    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        return response()->json();
    }
}
