<?php

namespace App\Shared\Providers;

use App\Shared\Support\Module\Monolith\ModuleAdapterFactory;
use App\Shared\Support\Module\Monolith\SharedKernel;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Shared\Contracts\Adapter\MailAdapterInterface;

/**
 * @see \Pkg\IntegrationEventBus\Service\MessageSender
 */
class ModuleServiceProvider extends ServiceProvider
{
    private array $modules = [
        'Administrator' => 'Administrator',
        'HotelModeration' => 'Hotel/Moderation',
        'HotelQuotation' => 'Hotel/Quotation',
        'HotelPricing' => 'Hotel/Pricing',
        'ClientModeration' => 'Client/Moderation',
        'ClientInvoicing' => 'Client/Invoicing',
        'ClientPayment' => 'Client/Payment',
        'BookingModeration' => 'Booking/Moderation',
        'BookingPricing' => 'Booking/Pricing',
        'BookingRequesting' => 'Booking/Requesting',
        'BookingNotification' => 'Booking/Notification',
        'BookingEventSourcing' => 'Booking/EventSourcing',
        'BookingInvoicing' => 'Booking/Invoicing',
        'SupplierModeration' => 'Supplier/Moderation',
//        'Traveline' => 'Traveline',
    ];

    protected array $shared = [
        TranslatorInterface::class,
        MailAdapterInterface::class,
        CurrencyRateAdapterInterface::class,
        FileStorageAdapterInterface::class,
        ApplicationConstantsInterface::class,
        CompanyRequisitesInterface::class,
    ];

    public function register(): void
    {
        $kernel = new SharedKernel($this->app);
        $this->app->booting(function () use ($kernel) {
            $kernel->boot();

            foreach ($this->shared as $abstract) {
                $kernel->getContainer()->bind($abstract, fn() => $this->app->get($abstract));
            }
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

    public function boot(): void {}
}
