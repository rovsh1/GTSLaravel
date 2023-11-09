<?php

namespace Module\Booking\Moderation\Providers;

use Module\Booking\Moderation\Domain\Booking\Listener\UpdateHotelQuotaListener;
use Module\Booking\Shared\Application\Support\IntegrationEventMapper;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected string $integrationEventMapper = IntegrationEventMapper::class;

    protected array $listen = [
        QuotaChangedEventInterface::class => UpdateHotelQuotaListener::class,
    ];
}
