<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\Booking;

class InvoiceGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel/invoice.html';
    }

    protected function getReservationAttributes(Booking $booking): array
    {
        return [];
    }
}
