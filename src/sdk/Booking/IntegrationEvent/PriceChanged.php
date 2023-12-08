<?php

namespace Sdk\Booking\IntegrationEvent;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class PriceChanged extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
//        public readonly StatusEnum $status
    )
    {
        parent::__construct($bookingId);
    }
}