<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use GTS\Services\Traveline\Infrastructure\Facade\Hotel\Search\FacadeInterface;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;

class GetRoomsAndRatePlansAction
{

    public function __construct(private FacadeInterface $facade)
    {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        return $this->facade->getRoomsAndRatePlans($request->getHotelId());
    }

}
