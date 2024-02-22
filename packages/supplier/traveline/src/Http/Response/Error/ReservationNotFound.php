<?php

namespace Pkg\Supplier\Traveline\Http\Response\Error;

class ReservationNotFound extends AbstractTravelineError
{
    public function __construct(int $reservationId)
    {
        parent::__construct(
            400,
            "Notification for booking '{$reservationId}' not found"
        );
    }
}
