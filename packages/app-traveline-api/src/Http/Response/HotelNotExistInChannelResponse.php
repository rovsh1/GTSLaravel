<?php

namespace Pkg\App\Traveline\Http\Response;

use Pkg\Supplier\Traveline\Dto\Response\Error\HotelNotExistInChannel;

class HotelNotExistInChannelResponse extends ErrorResponse
{
    public function __construct()
    {
        parent::__construct([new HotelNotExistInChannel()]);
    }
}
