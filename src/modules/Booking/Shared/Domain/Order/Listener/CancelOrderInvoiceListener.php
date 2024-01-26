<?php

namespace Module\Booking\Shared\Domain\Order\Listener;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Adapter\InvoiceAdapterInterface;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class CancelOrderInvoiceListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly InvoiceAdapterInterface $invoiceAdapter
    ) {}

    public function handle(DomainEventInterface $event)
    {
        assert($event instanceof InvoiceBecomeDeprecatedEventInterface);

        $booking = $this->bookingRepository->find($event->bookingId());
        if ($booking === null) {
            return;
        }

        $this->invoiceAdapter->cancelInvoice($booking->orderId());
    }
}
