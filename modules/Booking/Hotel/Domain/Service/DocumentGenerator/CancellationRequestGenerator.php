<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Hotel\Domain\Entity\Reservation;

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
