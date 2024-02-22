<?php

namespace Pkg\Supplier\Traveline\Http\Response;

class EmptySuccessResponse extends AbstractTravelineResponse
{
    public function __construct()
    {
        parent::__construct([]);
    }
}
