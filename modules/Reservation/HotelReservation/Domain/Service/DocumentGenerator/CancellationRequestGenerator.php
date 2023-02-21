<?php

namespace Module\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

use Module\Reservation\HotelReservation\Domain\Entity\Reservation;

class CancellationRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'cancellation_request.html';
    }

    protected function getReservationAttributes(Reservation $reservation): array
    {
        return [];
    }
}
