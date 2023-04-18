<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Hotel\Domain\Entity\Reservation;

class InvoiceGenerator extends AbstractGenerator
{
    protected function getTemplateName(): string
    {
        return 'invoice.html';
    }

    protected function getReservationAttributes(Reservation $reservation): array
    {
        return [];
    }
}
