<?php

namespace App\Shared\Providers;

use Gsdk\FileStorage\FileStorageServiceProvider;
use Sdk\Module\Support\ServiceProvider;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Services\ApplicationsConstants\ApplicationConstantManager;
use Services\CompanyRequisites\CompanyRequisiteManager;
use Services\CurrencyRate\CurrencyRateServiceProvider;
use Services\IntegrationEventBus\IntegrationEventBusServiceProvider;
use Support\LocaleTranslator\TranslationServiceProvider;
use Support\MailManager\MailManagerServiceProvider;

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
    }

    private function registerSupport(): void
    {
        $this->app->register(MailManagerServiceProvider::class);
        $this->app->register(TranslationServiceProvider::class);
        $this->app->register(FileStorageServiceProvider::class);
    }
}
