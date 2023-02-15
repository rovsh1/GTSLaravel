<?php

namespace GTS\Integration\Traveline\Domain\Api\Response\Error;

class HotelNotExistInChannel extends AbstractTravelineError
{
    public function __construct()
    {
        parent::__construct(
            361,
            'Hotel with such credentials is not exist in channel'
        );
    }
}
