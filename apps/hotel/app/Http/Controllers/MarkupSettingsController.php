<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Support\Facades\MarkupSettingsAdapter;
use App\Hotel\Http\Requests\AddMarkupSettingsConditionsRequest;
use App\Hotel\Http\Requests\DeleteMarkupSettingsConditionsRequest;
use App\Hotel\Http\Requests\UpdateMarkupSettingsRequest;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarkupSettingsController extends AbstractHotelController
{
    public function get(Request $request): JsonResponse
    {
        $markupSettings = MarkupSettingsAdapter::getHotelMarkupSettings($this->getHotel()->id);

        return response()->json($markupSettings);
    }

    public function update(UpdateMarkupSettingsRequest $request): AjaxResponseInterface
    {
        MarkupSettingsAdapter::updateMarkupSettings(
            hotelId: $this->getHotel()->id,
            key: $request->getKey(),
            value: $request->getValue(),
        );

        return new AjaxSuccessResponse();
    }

    public function addCondition(AddMarkupSettingsConditionsRequest $request): AjaxResponseInterface
    {
        MarkupSettingsAdapter::addMarkupSettingsCondition(
            hotelId: $this->getHotel()->id,
            key: $request->getKey(),
            value: $request->getValue(),
        );

        return new AjaxSuccessResponse();
    }

    public function deleteCondition(DeleteMarkupSettingsConditionsRequest $request): AjaxResponseInterface
    {
        MarkupSettingsAdapter::deleteMarkupSettingsCondition(
            hotelId: $this->getHotel()->id,
            key: $request->getKey(),
            index: $request->getIndex(),
        );

        return new AjaxSuccessResponse();
    }
}
