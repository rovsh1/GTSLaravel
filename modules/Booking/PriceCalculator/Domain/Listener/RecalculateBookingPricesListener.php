<?php

namespace Module\Booking\PriceCalculator\Domain\Listener;

use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly RoomCalculator $roomCalculator
    ) {}

    public function handle(DomainEventInterface|PriceBecomeDeprecatedEventInterface $event)
    {
        $booking = $this->repository->find($event->bookingId());
        foreach ($booking->roomBookings() as $roomBooking) {
            $roomPrice = $this->roomCalculator->calculateByBooking(
                $booking,
                $roomBooking->roomInfo()->id(),
                $roomBooking->details()->rateId(),
                $roomBooking->details()->isResident(),
                $roomBooking->guests()->count(),
                $roomBooking->details()->earlyCheckIn()?->priceMarkup()->value(),
                $roomBooking->details()->lateCheckOut()?->priceMarkup()->value()
            );
            $roomBooking->setPrice($roomPrice);
            $this->roomBookingRepository->store($roomBooking);
        }
    }
}
