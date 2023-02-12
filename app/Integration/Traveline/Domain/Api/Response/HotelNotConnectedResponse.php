<?php

namespace GTS\Integration\Traveline\Domain\Api\Response;

class HotelNotConnectedResponse extends AbstractErrorResponse
{
    public function __construct()
    {
        $error = new TravelineError(361, 'Hotel with such credentials is not exist in channel');
        parent::__construct([$error]);
    }
}
