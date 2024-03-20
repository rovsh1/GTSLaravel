<?php

namespace Sdk\Booking\IntegrationEvent\TransferBooking;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class GuestEdited extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly int $carBidId,
        public readonly int $guestId,
        public readonly string $guestName,
        public readonly string $oldGuestName,
    ) {
        parent::__construct($bookingId);
    }
}
