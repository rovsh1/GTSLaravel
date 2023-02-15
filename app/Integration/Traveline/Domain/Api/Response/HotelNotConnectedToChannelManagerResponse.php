<?php

namespace GTS\Integration\Traveline\Domain\Api\Response;

use GTS\Integration\Traveline\Domain\Api\Response\Error\HotelNotConnectedToChannelManager;

class HotelNotConnectedToChannelManagerResponse extends AbstractErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotConnectedToChannelManager()]);
    }
}
