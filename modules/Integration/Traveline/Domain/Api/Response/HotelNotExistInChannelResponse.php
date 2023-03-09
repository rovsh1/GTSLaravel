<?php

namespace Module\Integration\Traveline\Domain\Api\Response;

use Module\Integration\Traveline\Domain\Api\Response\Error\HotelNotExistInChannel;

class HotelNotExistInChannelResponse extends ErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotExistInChannel()]);
    }
}
