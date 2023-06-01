<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Hotel\Domain\Entity\Booking;

class ReservationRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'reservation_request.html';
    }

    protected function getReservationAttributes(Booking $booking): array
    {
        return [];
    }
}
