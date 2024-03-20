<?php

namespace Sdk\Booking\IntegrationEvent\TransferBooking;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class CarBidAdded extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly int $carBidId,
        public readonly int $carId,
        public readonly string $carName,
    ) {
        parent::__construct($bookingId);
    }
}
