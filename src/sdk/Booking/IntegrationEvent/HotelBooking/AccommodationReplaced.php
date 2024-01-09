<?php

namespace Sdk\Booking\IntegrationEvent\HotelBooking;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class AccommodationReplaced extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly int $accommodationId,
        public readonly int $roomId,
        public readonly string $roomName,
        public readonly string $oldRoomName,
    ) {
        parent::__construct($bookingId);
    }
}
