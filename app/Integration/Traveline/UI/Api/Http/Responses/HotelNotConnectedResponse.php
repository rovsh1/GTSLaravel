<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Responses;

class HotelNotConnectedResponse extends AbstractErrorResponse
{
    public function __construct()
    {
        $error = new TravelineError(361, 'Hotel with such credentials is not exist in channel');
        parent::__construct([$error]);
    }
}
