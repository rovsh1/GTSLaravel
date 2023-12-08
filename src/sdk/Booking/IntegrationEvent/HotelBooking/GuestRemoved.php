<?php

namespace Sdk\Booking\IntegrationEvent\HotelBooking;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class GuestRemoved extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly int $accommodationId,
        public readonly string $roomName,
        public readonly int $guestId,
        public readonly string $guestName,
    ) {
        parent::__construct($bookingId);
    }
}