<?php

namespace Pkg\Supplier\Traveline\Http\Response\Error;

class InvalidRateAccomodation extends AbstractTravelineError
{
    public function __construct()
    {
        parent::__construct(
            368,
            'Invalid rate accomodation'
        );
    }
}
