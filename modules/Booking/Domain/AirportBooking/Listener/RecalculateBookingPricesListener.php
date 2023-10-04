<?php

namespace Module\Booking\Airport\Domain\Booking\Listener;

use Module\Booking\Airport\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Airport\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Airport\Domain\Booking\Service\PriceCalculator\BookingCalculator;
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
