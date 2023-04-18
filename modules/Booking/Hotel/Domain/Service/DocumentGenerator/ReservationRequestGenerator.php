<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Hotel\Domain\Entity\Reservation;

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
