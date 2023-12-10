<?php

namespace Module\Booking\Requesting\Providers;

use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
//        IntegrationEventMessages::BOOKING_STATUS_UPDATED => [
//            StoreOriginalDataListener::class
//        ],
    ];
}
