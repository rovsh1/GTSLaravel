<?php

namespace Module\Booking\HotelBooking\Domain\Listener;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\HotelBooking\Domain\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\BookingCalculator;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\RoomPriceEditor;
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

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof PriceBecomeDeprecatedEventInterface);

        $booking = $this->repository->find($event->bookingId());
        foreach ($booking->roomBookings() as $roomBooking) {
            $roomBooking->recalculatePrices($this->roomPriceEditor);
            $this->roomBookingRepository->store($roomBooking);
        }
        $booking->recalculatePrices($this->bookingCalculator);
        $this->bookingUpdater->store($booking);
    }
}
