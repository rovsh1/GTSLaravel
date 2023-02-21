<?php

namespace Module\Integration\Traveline\Application\Service;

use Module\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Module\Integration\Traveline\Domain\Api\Request\Reservation;

class Booking
{
    public function __construct(private ReservationAdapterInterface $adapter) {}

    public function confirmReservations(array $reservations)
    {
        $reservationRequests = Reservation::collectionFromArray($reservations);
        foreach ($reservationRequests as $reservationRequest) {
            $this->adapter->confirmReservation($reservationRequest->number);
        }
    }
}
