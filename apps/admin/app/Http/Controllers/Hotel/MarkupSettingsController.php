<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\UpdateClientMarkupsRequest;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Hotel\MarkupSettingsAdapter;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarkupSettingsController extends Controller
{
    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        $markupSettings = MarkupSettingsAdapter::getHotelMarkupSettings($hotel->id);
        return response()->json($markupSettings);
    }

    public function updateClientMarkups(UpdateClientMarkupsRequest $request, Hotel $hotel): AjaxResponseInterface
    {
        MarkupSettingsAdapter::updateClientMarkups(
            hotelId: $hotel->id,
            individual: $request->getIndividual(),
            OTA: $request->getOTA(),
            TA: $request->getTA(),
            TO: $request->getTO()
        );
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
