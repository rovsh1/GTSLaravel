<?php

namespace Sdk\Booking\IntegrationEvent;

use Sdk\Booking\Dto\PriceDto;
use Sdk\Booking\Support\IntegrationEvent\AbstractBookingEvent;

final class ClientPriceChanged extends AbstractBookingEvent
{
    public function __construct(
        int $bookingId,
        public readonly PriceDto $before,
        public readonly PriceDto $after
    ) {
        parent::__construct($bookingId);
    }
}