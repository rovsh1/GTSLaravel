<?php

namespace Module\Booking\Domain\HotelBooking\Service\DocumentGenerator;

use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Entity\Invoice;
use Module\Booking\Domain\Shared\Service\DocumentGenerator\AbstractInvoiceGenerator;

class InvoiceGenerator extends AbstractInvoiceGenerator
{
    protected function getTemplateName(): string
    {
        return 'hotel.invoice';
    }

    protected function getBookingAttributes(BookingInterface $booking): array
    {
        return [];
    }

    protected function getInvoiceAttributes(Invoice $invoice): array
    {
        return [];
    }
}
