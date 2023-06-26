<?php

namespace Module\Booking\PriceCalculator\Domain\Listener;

use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly RoomCalculator $roomCalculator
    ) {}

    public function handle(DomainEventInterface|PriceBecomeDeprecatedEventInterface $event)
    {
        $roomBookings = $this->roomBookingRepository->get($event->bookingId());
//        dd($roomBookings);
    }
}
