<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Repository;

use GTS\Reservation\Infrastructure\Repository\ReservationRequest;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationEventsRepositoryInterface;

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
