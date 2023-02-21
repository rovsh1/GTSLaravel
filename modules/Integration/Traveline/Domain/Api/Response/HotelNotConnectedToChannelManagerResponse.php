<?php

namespace Module\Integration\Traveline\Domain\Api\Response;

use Module\Integration\Traveline\Domain\Api\Response\Error\HotelNotConnectedToChannelManager;

class HotelNotConnectedToChannelManagerResponse extends AbstractErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotConnectedToChannelManager()]);
    }
}
