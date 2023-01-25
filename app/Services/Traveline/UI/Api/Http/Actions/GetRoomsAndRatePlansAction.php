<?php

namespace GTS\Services\Traveline\UI\Api\Http\Actions;

use GTS\Services\Traveline\UI\Api\Http\Requests\GetRoomsAndRatePlansActionRequest;

class GetRoomsAndRatePlansAction
{

    public function __construct()
    {
    }

    public function handle(GetRoomsAndRatePlansActionRequest $request)
    {
        return ['id' => $request->getHotelId()];
    }

}
