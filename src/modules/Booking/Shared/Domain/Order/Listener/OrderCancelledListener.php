<?php

namespace Module\Booking\Shared\Domain\Order\Listener;

use Module\Booking\Shared\Domain\Order\Adapter\InvoiceAdapterInterface;
use Module\Booking\Shared\Domain\Order\Event\OrderCancelled;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class OrderCancelledListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly InvoiceAdapterInterface $invoiceAdapter
    ) {
    }

    public function handle(DomainEventInterface $event)
    {
        assert($event instanceof OrderCancelled);

        $this->invoiceAdapter->cancelInvoice($event->order->id());
    }
}