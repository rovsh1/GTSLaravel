<?php

namespace Module\Booking\Moderation\Providers;

use Module\Booking\Moderation\Domain\Booking\Listener\BookingsToProcessing;
use Module\Booking\Shared\Domain\Order\Event\OrderGuestEventInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        OrderGuestEventInterface::class => [
            BookingsToProcessing::class,
        ],
    ];
}
