<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Hotel\ResidenceConditionAdapter;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResidenceConditionController extends Controller
{
    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        $conditions = ResidenceConditionAdapter::getResidenceConditions($hotel->id);
        return response()->json($conditions);
    }

    public function store(Request $request, Hotel $hotel): AjaxResponseInterface
    {
        return new AjaxSuccessResponse();
    }

    public function update(Request $request, Hotel $hotel, $condition): AjaxResponseInterface
    {
        return new AjaxSuccessResponse();
    }

    public function destroy(Request $request, Hotel $hotel, $condition): AjaxResponseInterface
    {
        return new AjaxSuccessResponse();
    }
}
