<?php

namespace Pkg\Supplier\Traveline\Dto\Response\Error;

class InvalidRatePlan extends AbstractTravelineError
{
    public function __construct()
    {
        parent::__construct(
            436,
            'Invalid rate plan'
        );
    }
}
