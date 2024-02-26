<?php

namespace Module\Client\Payment\Providers;

use Module\Client\Payment\Domain\Payment\Listener\UpdatePaymentLandingsListener;
use Sdk\Booking\IntegrationEvent\Order\ClientPenaltyChanged;
use Sdk\Booking\IntegrationEvent\Order\OrderRefunded;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        OrderRefunded::class => UpdatePaymentLandingsListener::class,
        ClientPenaltyChanged::class => UpdatePaymentLandingsListener::class,
    ];
}
