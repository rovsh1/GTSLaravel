<?php

declare(strict_types=1);

namespace Sdk\Booking\Contracts\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface QuotaChangedEventInterface extends BookingEventInterface,
                                             PriceBecomeDeprecatedEventInterface,
                                             IntegrationEventInterface
{
}
