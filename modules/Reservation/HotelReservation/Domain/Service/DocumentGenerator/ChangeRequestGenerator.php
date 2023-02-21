<?php

namespace Module\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

use Module\Reservation\HotelReservation\Domain\Entity\Reservation;

class ChangeRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'change_request.html';
    }

    protected function getReservationAttributes(Reservation $reservation): array
    {
        return [];
    }
}
