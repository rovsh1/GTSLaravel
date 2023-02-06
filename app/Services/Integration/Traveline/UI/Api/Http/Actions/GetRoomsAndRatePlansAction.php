<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Actions;

use GTS\Services\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface;
use GTS\Services\Integration\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;

class GetRoomsAndRatePlansAction
{

    public function __construct(private HotelFacadeInterface $facade) {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        return $this->facade->getRoomsAndRatePlans($request->getHotelId());
    }

}
