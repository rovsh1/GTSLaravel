<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\AbstractBooking;

class ReservationRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel/reservation_request.html';
    }

    protected function getReservationAttributes(AbstractBooking $booking): array
    {
        return [];
    }
}
