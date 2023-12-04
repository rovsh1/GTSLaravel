<?php

namespace Supplier\Traveline\Domain\Api\Response;

use Supplier\Traveline\Domain\Api\Response\Error\HotelNotExistInChannel;

class HotelNotExistInChannelResponse extends ErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotExistInChannel()]);
    }
}
