<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\AddMarkupSettingsConditionsRequest;
use App\Admin\Http\Requests\Hotel\DeleteMarkupSettingsConditionsRequest;
use App\Admin\Http\Requests\Hotel\UpdateMarkupSettingsRequest;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Hotel\MarkupSettingsAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarkupSettingsController extends Controller
{
    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        $markupSettings = MarkupSettingsAdapter::getHotelMarkupSettings($hotel->id);
        return response()->json($markupSettings);
    }

    public function update(UpdateMarkupSettingsRequest $request, Hotel $hotel): AjaxResponseInterface
    {
        MarkupSettingsAdapter::updateMarkupSettings(
            hotelId: $hotel->id,
            key: $request->getKey(),
            value: $request->getValue(),
        );
        return new AjaxSuccessResponse();
    }

    public function addCondition(AddMarkupSettingsConditionsRequest $request, Hotel $hotel): AjaxResponseInterface
    {
        MarkupSettingsAdapter::addMarkupSettingsCondition(
            hotelId: $hotel->id,
            key: $request->getKey(),
            value: $request->getValue(),
        );
        return new AjaxSuccessResponse();
    }

    public function deleteCondition(DeleteMarkupSettingsConditionsRequest $request, Hotel $hotel): AjaxResponseInterface
    {
        MarkupSettingsAdapter::deleteMarkupSettingsCondition(
            hotelId: $hotel->id,
            key: $request->getKey(),
            index: $request->getIndex(),
        );
        return new AjaxSuccessResponse();
    }
}
