<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Integration\Traveline\Domain\Api\Response\GetRoomsAndRatePlansActionResponse;
use GTS\Integration\Traveline\Domain\Api\Response\HotelNotExistInChannelResponse;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface;
use GTS\Integration\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;

class GetRoomsAndRatePlansAction
{

    public function __construct(private HotelFacadeInterface $facade) {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        try {
            $roomsAndRatePlans = $this->facade->getRoomsAndRatePlans($request->getHotelId());
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotExistInChannelResponse();
        }
        return new GetRoomsAndRatePlansActionResponse($roomsAndRatePlans);
    }

}
