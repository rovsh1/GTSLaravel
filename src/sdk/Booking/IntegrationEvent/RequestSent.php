<?php

namespace Sdk\Booking\IntegrationEvent;

use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class RequestSent extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly int $requestId,
        public readonly RequestTypeEnum $requestType,
        public readonly string $fileGuid
    ) {
        parent::__construct($bookingId);
    }
}