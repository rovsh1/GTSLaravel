<?php

namespace Module\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

use Module\Reservation\HotelReservation\Domain\Entity\Reservation;

class ReservationRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'reservation_request.html';
    }

    protected function getReservationAttributes(Reservation $reservation): array
    {
        return [];
    }
}
