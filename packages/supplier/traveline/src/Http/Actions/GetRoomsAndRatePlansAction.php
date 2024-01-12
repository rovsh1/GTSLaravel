<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Http\Request\GetRoomsAndRatePlansActionRequest;
use Pkg\Supplier\Traveline\Http\Response\GetRoomsAndRatePlansActionResponse;
use Pkg\Supplier\Traveline\Http\Response\HotelNotExistInChannelResponse;
use Pkg\Supplier\Traveline\Service\HotelService;

class GetRoomsAndRatePlansAction
{
    public function __construct(private readonly HotelService $hotelService) {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        try {
            $roomsAndRatePlans = $this->hotelService->getHotelRoomsAndRatePlans($request->getHotelId());
        } catch (HotelNotConnectedException $e) {
            return new HotelNotExistInChannelResponse();
        }

        return new GetRoomsAndRatePlansActionResponse($roomsAndRatePlans);
    }

}
