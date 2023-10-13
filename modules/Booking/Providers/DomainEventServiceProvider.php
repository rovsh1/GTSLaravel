<?php

namespace Module\Booking\Providers;

use Module\Booking\Application\Admin\Shared\Support\IntegrationEventMapper;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected string $integrationEventMapper = IntegrationEventMapper::class;
}
