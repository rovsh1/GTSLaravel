<?php

namespace Module\Booking\Domain\HotelBooking\Listener;

use Module\Booking\Domain\HotelBooking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
    ) {
    }

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof PriceBecomeDeprecatedEventInterface);

        $this->priceCalculator->calculate(new BookingId($event->bookingId()));
    }
}
