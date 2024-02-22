<?php

namespace Pkg\Supplier\Traveline\Http\Response;

use Pkg\Supplier\Traveline\Http\Response\Error\HotelNotConnectedToChannelManager;

class HotelNotConnectedToChannelManagerResponse extends ErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotConnectedToChannelManager()]);
    }
}
