<?php

namespace Module\Booking\Airport\Domain\Listener;

use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Airport\Domain\Service\PriceCalculator\BookingCalculator;
use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Airport\Domain\Event\PriceBecomeDeprecatedEventInterface;
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
