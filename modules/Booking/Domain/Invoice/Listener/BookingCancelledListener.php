<?php

namespace Module\Booking\Domain\Invoice\Listener;

use Module\Booking\Domain\Invoice\Invoice;
use Module\Booking\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Booking\Domain\Invoice\Service\InvoiceAmountBuilder;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingCancelledListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly InvoiceAmountBuilder $paymentCalculator,
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
    ) {
    }

    public function handle(DomainEventInterface $event): void
    {
//        assert($event instanceof )

        $invoices = $this->invoiceRepository->getByBookingId($event->bookingId());

        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            $invoice->updatePaymentState($this->paymentCalculator);

            $this->domainEventDispatcher->dispatch(...$invoice->pullEvents());
        }
    }
}
