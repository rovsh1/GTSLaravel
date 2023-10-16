<?php

namespace Module\Booking\Providers;

use Module\Booking\Application\Admin\Shared\Support\IntegrationEventMapper;
use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected string $integrationEventMapper = IntegrationEventMapper::class;

    protected array $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
    ];
}
