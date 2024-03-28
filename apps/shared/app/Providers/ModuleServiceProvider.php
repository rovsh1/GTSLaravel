<?php

namespace App\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Adapter\AirportAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CityAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Adapter\RailwayStationAdapterInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventPublisherInterface;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Shared\Contracts\Adapter\MailAdapterInterface;
use Shared\Contracts\Adapter\TravelineAdapterInterface;
use Shared\Support\Module\Module;
use Shared\Support\Module\SharedContainer;

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
        'BookingNotification' => 'Booking/Notification',
        'BookingInvoicing' => 'Booking/Invoicing',
        'SupplierModeration' => 'Supplier/Moderation',
        'SupplierPayment' => 'Supplier/Payment',
    ];

    private array $pkgModules = [
        'BookingCommon' => 'Pkg\\Booking\\Common\\',
        'BookingRequesting' => 'Pkg\\Booking\\Requesting\\',
        'BookingEventSourcing' => 'Pkg\\Booking\\EventSourcing\\',
        'BookingReporting' => 'Pkg\\Booking\\Reporting\\',
        'HotelAdministration' => 'Pkg\\Hotel\\Administration\\',
        'CurrencyRate' => 'Pkg\\CurrencyRate\\',
        'MailManager' => 'Pkg\\MailManager\\',
        'IntegrationEventBus' => 'Pkg\\IntegrationEventBus\\',
        'TravelineApp' => 'Pkg\\App\\Traveline\\',
        'TravelineSupplier' => 'Pkg\\Supplier\\Traveline\\',
    ];

    protected array $shared = [
        TranslatorInterface::class,
        MailAdapterInterface::class,
        CurrencyRateAdapterInterface::class,
        FileStorageAdapterInterface::class,
        ApplicationConstantsInterface::class,
        CompanyRequisitesInterface::class,
        TravelineAdapterInterface::class,
        IntegrationEventPublisherInterface::class,
        AirportAdapterInterface::class,
        RailwayStationAdapterInterface::class,
        CountryAdapterInterface::class,
        CityAdapterInterface::class
    ];

    public function register(): void
    {
        $modules = app()->modules();
        $sharedContainer = $this->makeSharedContainer();

        $this->registerSrcModules($modules, $sharedContainer);
        $this->registerPackageModules($modules, $sharedContainer);
    }

    private function registerPackageModules($modules, SharedContainer $sharedContainer): void
    {
        foreach ($this->pkgModules as $name => $ns) {
            $modules->register(
                new Module(
                    name: $name,
                    namespace: $ns,
                    sharedContainer: $sharedContainer,
                    config: []
                )
            );
        }
    }

    private function registerSrcModules($modules, SharedContainer $sharedContainer): void
    {
        $modulesNamespace = 'Module';
        $configs = config('modules');
        foreach ($this->modules as $name => $path) {
            $modules->register(
                new Module(
                    name: $name,
                    namespace: $modulesNamespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $path) . '\\',
                    sharedContainer: $sharedContainer,
                    config: $configs[$name] ?? []
                )
            );
        }
    }

    protected function makeSharedContainer(): SharedContainer
    {
        $container = new SharedContainer();
        foreach ($this->shared as $abstract) {
            $container->bind($abstract, fn() => $this->app->get($abstract));
        }

        return $container;
    }

    public function boot(): void {}
}
