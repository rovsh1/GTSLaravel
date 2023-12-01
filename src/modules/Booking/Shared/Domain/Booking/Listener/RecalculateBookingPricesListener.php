<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Shared\Domain\Booking\Adapter\PricingAdapterInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly PricingAdapterInterface $pricingAdapter
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof PriceBecomeDeprecatedEventInterface);

         $this->pricingAdapter->recalculate($event->bookingId()->value());
    }
}
