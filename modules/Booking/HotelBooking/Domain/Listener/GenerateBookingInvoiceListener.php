<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Listener;

use Module\Booking\Common\Domain\Event\Status\BookingPaid;
use Module\Booking\Common\Domain\Service\InvoiceCreator;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class GenerateBookingInvoiceListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly InvoiceCreator $invoiceCreator,
    ) {}

    public function handle(DomainEventInterface $event)
    {
        assert($event instanceof BookingPaid);

        $event->booking->generateInvoice($this->invoiceCreator);
    }
}
