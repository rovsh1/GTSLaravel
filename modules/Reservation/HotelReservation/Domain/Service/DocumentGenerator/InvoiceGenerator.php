<?php

namespace Module\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

use Module\Reservation\HotelReservation\Domain\Entity\Reservation;

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
