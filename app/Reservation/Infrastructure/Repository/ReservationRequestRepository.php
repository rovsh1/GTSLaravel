<?php

namespace GTS\Reservation\Infrastructure\Repository;

use GTS\Reservation\Domain\Repository\ReservationEventsRepositoryInterface;
use GTS\Reservation\Domain\Service\DocumentGenerator\HotelReservation\ReservationRequestGenerator;
use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;
use GTS\Reservation\Infrastructure\Models\ReservationEvent;

class ReservationRequestRepository implements ReservationEventsRepositoryInterface
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
