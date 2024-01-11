<?php

namespace App\Shared\Providers;

use Gsdk\FileStorage\FileStorageServiceProvider;
use Module\Shared\Infrastructure\Adapter\FileStorageAdapter;
use Sdk\Module\Support\ServiceProvider;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Services\ApplicationsConstants\ApplicationConstantManager;
use Services\CompanyRequisites\CompanyRequisiteManager;
use Services\CurrencyRate\CurrencyRateServiceProvider;
use Services\IntegrationEventBus\IntegrationEventBusServiceProvider;
use Shared\Contracts\Adapter\MailAdapterInterface;
use Shared\Support\Adapter\MailAdapter;
use Support\LocaleTranslator\TranslationServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerServices();
        $this->registerSupport();
    }

    private function registerServices(): void
    {
        $this->app->register(CurrencyRateServiceProvider::class);
        $this->app->register(IntegrationEventBusServiceProvider::class);

        $this->app->singleton(ApplicationConstantsInterface::class, ApplicationConstantManager::class);
        $this->app->singleton(CompanyRequisitesInterface::class, CompanyRequisiteManager::class);
        $this->app->singleton(MailAdapterInterface::class, MailAdapter::class);
    }

    private function registerSupport(): void
    {
        $this->app->register(TranslationServiceProvider::class);

        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);
        $this->app->register(FileStorageServiceProvider::class);
    }
}
