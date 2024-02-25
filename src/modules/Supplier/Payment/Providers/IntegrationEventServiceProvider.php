<?php

namespace Module\Supplier\Payment\Providers;

use Module\Supplier\Payment\Domain\Payment\Listener\UpdatePaymentLandingsListener;
use Sdk\Booking\IntegrationEvent\BookingCancelled;
use Sdk\Booking\IntegrationEvent\SupplierPriceChanged;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingCancelled::class => UpdatePaymentLandingsListener::class,
        SupplierPriceChanged::class => UpdatePaymentLandingsListener::class,
    ];
}
