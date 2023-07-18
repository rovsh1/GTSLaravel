<?php

namespace Module\Booking\PriceCalculator\Domain\Listener;

use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\BookingCalculator;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\RoomPriceEditor;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly BookingCalculator $bookingCalculator,
        private readonly RoomPriceEditor $roomPriceEditor,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function handle(DomainEventInterface|PriceBecomeDeprecatedEventInterface $event): void
    {
        $booking = $this->repository->find($event->bookingId());
        foreach ($booking->roomBookings() as $roomBooking) {
            $roomBooking->recalculatePrices($this->roomPriceEditor);
            $this->roomBookingRepository->store($roomBooking);
        }
        $booking->recalculatePrices($this->bookingCalculator);
        $this->bookingUpdater->store($booking);
    }
}
