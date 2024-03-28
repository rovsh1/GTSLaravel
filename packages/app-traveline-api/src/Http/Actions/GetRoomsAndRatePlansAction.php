<?php

namespace Pkg\App\Traveline\Http\Actions;

use Pkg\App\Traveline\Http\Request\GetRoomsAndRatePlansActionRequest;
use Pkg\App\Traveline\Http\Response\GetRoomsAndRatePlansActionResponse;
use Pkg\App\Traveline\Http\Response\HotelNotExistInChannelResponse;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\UseCase\GetHotelRoomsAndRatePlans;

class GetRoomsAndRatePlansAction
{
    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        try {
            $roomsAndRatePlans = app(GetHotelRoomsAndRatePlans::class)->execute($request->getHotelId());
        } catch (HotelNotConnectedException $e) {
            return new HotelNotExistInChannelResponse();
        }

        return new GetRoomsAndRatePlansActionResponse($roomsAndRatePlans);
    }

}
