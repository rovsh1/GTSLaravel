<?php

namespace Pkg\App\Traveline\Http\Response;

class EmptySuccessResponse extends AbstractTravelineResponse
{
    public function __construct()
    {
        parent::__construct([]);
    }
}
