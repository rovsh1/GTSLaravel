<?php

namespace Module\Booking\Infrastructure\HotelBooking\Repository;

use Module\Booking\Infrastructure\BookingRequest\Repository\ReservationRequest;
use Module\Booking\Domain\HotelBooking\Repository\ReservationEventsRepositoryInterface;

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
