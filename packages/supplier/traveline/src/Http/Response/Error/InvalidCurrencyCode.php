<?php

namespace Pkg\Supplier\Traveline\Http\Response\Error;

class InvalidCurrencyCode extends AbstractTravelineError
{
    public function __construct()
    {
        parent::__construct(
            360,
            'Invalid Сurrency Code'
        );
    }
}
