<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Services\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface;
use GTS\Services\Integration\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;
use GTS\Services\Integration\Traveline\UI\Api\Http\Responses\GetRoomsAndRatePlansActionResponse;

class GetRoomsAndRatePlansAction
{

    public function __construct(private HotelFacadeInterface $facade) {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        $roomsAndRatePlans = $this->facade->getRoomsAndRatePlans($request->getHotelId());
        return new GetRoomsAndRatePlansActionResponse($roomsAndRatePlans);
    }

}
