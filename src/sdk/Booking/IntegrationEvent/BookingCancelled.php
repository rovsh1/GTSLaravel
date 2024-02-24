<?php

namespace Sdk\Booking\IntegrationEvent;

use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class BookingCancelled extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly StatusEnum $status
    ) {
        parent::__construct($bookingId);
    }
}
