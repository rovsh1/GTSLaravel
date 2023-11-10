<?php

namespace App\Shared\Providers;

use App\Shared\Support\Module\Monolith\ModuleAdapterFactory;
use App\Shared\Support\Module\Monolith\SharedKernel;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    private array $modules = [
        'Administrator' => 'Administrator',
        'HotelModeration' => 'Hotel/Moderation',
        'HotelQuotation' => 'Hotel/Quotation',
        'HotelPricing' => 'Hotel/Pricing',
        'ClientModeration' => 'Client/Moderation',
        'ClientInvoicing' => 'Client/Invoicing',
        'BookingModeration' => 'Booking/Moderation',
        'BookingPricing' => 'Booking/Pricing',
        'BookingRequesting' => 'Booking/Requesting',
        'BookingShared' => 'Booking/Shared',
        'Supplier' => 'Supplier',
        'Logging' => 'Generic/Logging',
        'CurrencyRate' => 'Generic/CurrencyRate',
        'Notification' => 'Generic/Notification',
        'FileStorage' => 'Support/FileStorage',
        'MailManager' => 'Support/MailManager',
        'IntegrationEventBus' => 'Support/IntegrationEventBus',
        'Scheduler' => 'Support/Scheduler',
//        'Traveline' => 'Traveline',
    ];

    public function register(): void
    {
        $kernel = new SharedKernel($this->app);
        $this->app->booting(function () use ($kernel) {
            $kernel->boot();
        });

        $modules = app()->modules();
        $monolithFactory = new ModuleAdapterFactory(
            modulesPath: $this->app->modulesPath(),
            modulesNamespace: 'Module',
            sharedContainer: $kernel->getContainer()
        );

        $configs = config('modules');
        foreach ($this->modules as $name => $path) {
            $adapter = $monolithFactory->build($name, $path, $configs[$name] ?? []);
            $modules->register($adapter);
        }
    }

    public function boot(): void
    {
    }
}
