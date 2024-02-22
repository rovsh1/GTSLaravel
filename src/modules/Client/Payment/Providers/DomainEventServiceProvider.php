<?php

namespace Module\Client\Payment\Providers;

use Module\Client\Payment\Domain\Order\Listener\UpdateOrderStatus;
use Module\Client\Payment\Domain\Payment\Event\PaymentLandingsModified;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        PaymentLandingsModified::class => [
            UpdateOrderStatus::class,
        ],
    ];
}
