<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Booking;

class CancellationRequestGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel/cancellation_request.html';
    }

    protected function getReservationAttributes(Booking $booking): array
    {
        return [];
    }
}
