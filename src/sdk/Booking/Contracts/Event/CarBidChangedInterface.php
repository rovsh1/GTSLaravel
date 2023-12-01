<?php

namespace Sdk\Booking\Contracts\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface CarBidChangedInterface extends BookingEventInterface,
                                         PriceBecomeDeprecatedEventInterface,
                                         IntegrationEventInterface
{
}