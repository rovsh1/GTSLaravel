<?php

namespace Sdk\Booking\IntegrationEvent;

use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class StatusUpdated extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly StatusEnum $status,
        public readonly ?string $reason = null
    ) {
        parent::__construct($bookingId);
    }
}