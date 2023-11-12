<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface BookingStatusEventInterface extends BookingEventInterface, IntegrationEventInterface
{
}
