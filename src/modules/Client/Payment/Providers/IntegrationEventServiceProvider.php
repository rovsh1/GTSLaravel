<?php

namespace Module\Client\Payment\Providers;

use Module\Client\Payment\Domain\Payment\Listener\UpdatePaymentLandingsListener;
use Sdk\Booking\IntegrationEvent\OrderRefunded;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        OrderRefunded::class => UpdatePaymentLandingsListener::class
    ];
}
