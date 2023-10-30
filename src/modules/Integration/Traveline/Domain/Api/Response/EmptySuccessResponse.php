<?php

namespace Module\Integration\Traveline\Domain\Api\Response;

class EmptySuccessResponse extends AbstractTravelineResponse
{
    public function __construct()
    {
        parent::__construct([]);
    }
}
