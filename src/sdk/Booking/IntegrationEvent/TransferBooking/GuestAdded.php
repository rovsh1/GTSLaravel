<?php

namespace Sdk\Booking\IntegrationEvent\TransferBooking;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class GuestAdded extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly int $carBidId,
        public readonly int $carId,
//        public readonly int $carModel,
        public readonly int $guestId,
        public readonly string $guestName
    ) {
        parent::__construct($bookingId);
    }
}
