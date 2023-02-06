<?php

namespace GTS\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;

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
