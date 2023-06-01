<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Hotel\Domain\Entity\Booking;

class CancellationRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'cancellation_request.html';
    }

    protected function getReservationAttributes(Booking $booking): array
    {
        return [];
    }
}
