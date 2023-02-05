<?php

namespace GTS\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;

class CancellationRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel_cancellation_request.html';
    }

    protected function getReservationAttributes(Reservation $reservation): array
    {
        return [];
    }
}
