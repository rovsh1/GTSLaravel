<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Requests\UpdateSettingsRequest;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\Facades\SettingsAdapter;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends AbstractHotelController
{
    public function index(Request $request): LayoutContract
    {
        return Layout::title('Условия размещения')
            ->view('settings.settings', [
                'model' => $this->getHotel(),
                'contract' => $this->getHotel()->contracts()->active()->first(),
            ]);
    }

    public function get(Request $request, int $id): JsonResponse
    {
        $hotelSettings = SettingsAdapter::getHotelSettings($this->getHotel()->id);

        return response()->json($hotelSettings);
    }

    public function update(UpdateSettingsRequest $request): AjaxResponseInterface
    {
        SettingsAdapter::updateHotelTimeSettings(
            $this->getHotel()->id,
            $request->getCheckInAfter(),
            $request->getCheckOutBefore(),
            $request->getBreakfastPeriodFrom(),
            $request->getBreakfastPeriodTo()
        );

        return new AjaxSuccessResponse();
    }
}
