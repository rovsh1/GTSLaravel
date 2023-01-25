<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use GTS\Services\Traveline\Infrastructure\Api\Hotel\Search\ApiInterface;
use GTS\Services\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;

class GetRoomsAndRatePlansAction
{

    public function __construct(private ApiInterface $api)
    {}

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        return $this->api->getRoomsAndRatePlans($request->getHotelId());
    }

}
