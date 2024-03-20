<?php

namespace Sdk\Booking\IntegrationEvent;

use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class DetailsFieldModified extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly string $field,
        public readonly mixed $value,
        public readonly mixed $valueBefore,
    ) {
        parent::__construct($bookingId);
    }
}
