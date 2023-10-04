<?php

namespace Module\Booking\Domain\AirportBooking\Listener;

use Module\Booking\Domain\AirportBooking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\AirportBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\AirportBooking\Service\PriceCalculator\BookingCalculator;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingCalculator $bookingCalculator,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof PriceBecomeDeprecatedEventInterface);

        $booking = $this->repository->find($event->bookingId());
        $booking->recalculatePrices($this->bookingCalculator);
        $this->bookingUpdater->store($booking);
    }
}
