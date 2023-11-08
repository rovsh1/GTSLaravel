<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Application\Support\IntegrationEventMapper;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected string $integrationEventMapper = IntegrationEventMapper::class;

    protected array $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
    ];
}
