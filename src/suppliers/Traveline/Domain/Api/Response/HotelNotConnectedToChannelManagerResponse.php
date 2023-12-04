<?php

namespace Supplier\Traveline\Domain\Api\Response;

use Supplier\Traveline\Domain\Api\Response\Error\HotelNotConnectedToChannelManager;

class HotelNotConnectedToChannelManagerResponse extends ErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotConnectedToChannelManager()]);
    }
}
