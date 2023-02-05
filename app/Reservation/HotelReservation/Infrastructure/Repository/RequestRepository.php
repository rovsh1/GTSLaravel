<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Repository;

use GTS\Reservation\HotelReservation\Domain\Repository\ReservationEventsRepositoryInterface;
use GTS\Reservation\Infrastructure\Repository\ReservationRequest;

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
