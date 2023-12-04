<?php

namespace Supplier\Traveline\Domain\Api\Response\Error;

class HotelNotConnectedToChannelManager extends AbstractTravelineError
{
    public function __construct()
    {
        parent::__construct(
            375,
            'Hotel is not connected to TravelLine Channel Manager'
        );
    }
}
