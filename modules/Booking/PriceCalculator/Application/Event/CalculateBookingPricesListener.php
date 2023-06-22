<?php

namespace Module\Booking\PriceCalculator\Application\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\CalculationChangesEventInterface;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\RoomCalculator;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class CalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly RoomCalculator $roomCalculator
    ) {}

    public function handle(BookingEventInterface|CalculationChangesEventInterface $event)
    {
        dd($event);
    }
}
