<?php

namespace Module\Integration\Traveline\UI\Api\Http\Actions;

use Module\Integration\Traveline\Domain\Api\Response\GetRoomsAndRatePlansActionResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotExistInChannelResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface;
use Module\Integration\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;

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
