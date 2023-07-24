<?php

namespace Module\Booking\Hotel\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Entity\Invoice;
use Module\Booking\Common\Domain\Service\DocumentGenerator\AbstractInvoiceGenerator;

class InvoiceGenerator extends AbstractInvoiceGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel.invoice';
    }

    protected function getReservationAttributes(BookingInterface $booking): array
    {
        return [];
    }

    protected function getInvoiceAttributes(Invoice $invoice): array
    {
        return [];
    }
}
