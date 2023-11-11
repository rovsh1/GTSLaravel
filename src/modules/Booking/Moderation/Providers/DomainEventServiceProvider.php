<?php

namespace Module\Booking\Moderation\Providers;

use Module\Booking\Moderation\Application\Support\IntegrationEventMapper;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected string $integrationEventMapper = IntegrationEventMapper::class;

    protected array $listen = [
    ];
}
