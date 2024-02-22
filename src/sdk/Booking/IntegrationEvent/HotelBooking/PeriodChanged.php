<?php

namespace Sdk\Booking\IntegrationEvent\HotelBooking;

use Sdk\Booking\Dto\PeriodDto;
use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class PeriodChanged extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly PeriodDto $before,
        public readonly PeriodDto $after,
    ) {
        parent::__construct($bookingId);
    }
}