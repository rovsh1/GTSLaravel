<?php

namespace Pkg\App\Traveline\Http\Response;

use Pkg\Supplier\Traveline\Dto\Response\Error\HotelNotConnectedToChannelManager;

class HotelNotConnectedToChannelManagerResponse extends ErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotConnectedToChannelManager()]);
    }
}
