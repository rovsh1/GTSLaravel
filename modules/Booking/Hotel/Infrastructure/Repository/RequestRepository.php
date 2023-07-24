<?php

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Module\Booking\Common\Infrastructure\Repository\ReservationRequest;
use Module\Booking\Hotel\Domain\Repository\ReservationEventsRepositoryInterface;

class RequestRepository implements ReservationEventsRepositoryInterface
{
    public function create(int $reservationId, int $reservationType, int $requestType, string $file)
    {
        $requestModel = ReservationRequest::create([
            'reservation_id' => $reservationId,
            'reservation_type' => $reservationType,
            'type' => $requestType,
            'file' => $file
        ]);
        //new ReservationRequestGenerator()
    }
}