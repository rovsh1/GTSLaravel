<?php

namespace Module\Booking\Domain\HotelBooking\Listener;

use Module\Booking\Domain\HotelBooking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\BookingCalculator;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\RoomPriceEditor;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
//        private readonly BookingRepositoryInterface $repository,
//        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
//        private readonly BookingCalculator $bookingCalculator,
//        private readonly RoomPriceEditor $roomPriceEditor,
//        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        return;
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
