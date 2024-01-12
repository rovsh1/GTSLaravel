<?php

namespace Pkg\Supplier\Traveline\Http\Response;

use Pkg\Supplier\Traveline\Http\Response\Error\HotelNotExistInChannel;

class HotelNotExistInChannelResponse extends ErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotExistInChannel()]);
    }
}
