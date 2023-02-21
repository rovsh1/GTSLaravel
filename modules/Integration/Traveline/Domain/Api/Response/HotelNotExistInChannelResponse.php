<?php

namespace Module\Integration\Traveline\Domain\Api\Response;

use Module\Integration\Traveline\Domain\Api\Response\Error\HotelNotExistInChannel;

class HotelNotExistInChannelResponse extends AbstractErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotExistInChannel()]);
    }
}
