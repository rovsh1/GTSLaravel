<?php

namespace App\Shared\Providers;

use App\Shared\Support\Context\ConsoleContextManager;
use Illuminate\Support\ServiceProvider;
use Pkg\IntegrationEventBus\Service\IntegrationEventPublisher;
use Sdk\Shared\Contracts\Adapter\AirportAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CityAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Adapter\RailwayStationAdapterInterface;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventPublisherInterface;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Services\ApplicationsConstants\ApplicationConstantManager;
use Services\CompanyRequisites\CompanyRequisiteManager;
use Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Shared\Contracts\Adapter\MailAdapterInterface;
use Shared\Contracts\Adapter\TravelineAdapterInterface;
use Shared\Support\Adapter\AirportAdapter;
use Shared\Support\Adapter\CityAdapter;
use Shared\Support\Adapter\CountryAdapter;
use Shared\Support\Adapter\CurrencyRateAdapter;
use Shared\Support\Adapter\FileStorageAdapter;
use Shared\Support\Adapter\MailAdapter;
use Shared\Support\Adapter\RailwayStationAdapter;
use Shared\Support\Adapter\TravelineAdapter;
use Support\LocaleTranslator\TranslationServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->app->singleton(ContextInterface::class, ConsoleContextManager::class);
        }
    }

    public function boot(): void
    {
        $this->registerServices();
        $this->registerSupport();
        $this->registerAdapters();
    }

    private function registerServices(): void
    {
        $this->app->singleton(ApplicationConstantsInterface::class, ApplicationConstantManager::class);
        $this->app->singleton(CompanyRequisitesInterface::class, CompanyRequisiteManager::class);
        $this->app->singleton(MailAdapterInterface::class, MailAdapter::class);
        $this->app->singleton(CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);
        $this->app->singleton(TravelineAdapterInterface::class, TravelineAdapter::class);
        $this->app->singleton(IntegrationEventPublisherInterface::class, IntegrationEventPublisher::class);
    }

    private function registerSupport(): void
    {
        $this->app->register(TranslationServiceProvider::class);

        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);
    }

    private function registerAdapters(): void
    {
        $this->app->singleton(AirportAdapterInterface::class, AirportAdapter::class);
        $this->app->singleton(RailwayStationAdapterInterface::class, RailwayStationAdapter::class);
        $this->app->singleton(CountryAdapterInterface::class, CountryAdapter::class);
        $this->app->singleton(CityAdapterInterface::class, CityAdapter::class);
    }
}
