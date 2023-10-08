<?php

namespace Module\Booking\Domain\HotelBooking\Listener;

use Module\Booking\Domain\HotelBooking\Adapter\PriceCalculatorAdapterInterface;
use Module\Booking\Domain\HotelBooking\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly PriceCalculatorAdapterInterface $priceCalculatorAdapter,
    ) {
    }

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof PriceBecomeDeprecatedEventInterface);

        $this->priceCalculatorAdapter->recalculate($event->bookingId());
    }
}
