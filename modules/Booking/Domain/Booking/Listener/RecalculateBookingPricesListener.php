<?php

namespace Module\Booking\Domain\Booking\Listener;

use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Booking\Service\PriceCalculator;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof PriceBecomeDeprecatedEventInterface);

         $this->priceCalculator->calculate($event->bookingId());
    }
}
