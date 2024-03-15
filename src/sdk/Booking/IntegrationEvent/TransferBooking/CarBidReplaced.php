<?php

namespace Sdk\Booking\IntegrationEvent\TransferBooking;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class CarBidReplaced extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly int $carBidId,
        public readonly int $carId,
        public readonly string $carName,
        public readonly string $oldCarName,
    ) {
        parent::__construct($bookingId);
    }
}