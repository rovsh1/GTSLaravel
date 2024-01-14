<?php

namespace App\Shared\Providers;

use Module\Shared\Infrastructure\Adapter\FileStorageAdapter;
use Sdk\Module\Support\ServiceProvider;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Services\ApplicationsConstants\ApplicationConstantManager;
use Services\CompanyRequisites\CompanyRequisiteManager;
use Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Shared\Contracts\Adapter\MailAdapterInterface;
use Shared\Contracts\Adapter\TravelineAdapterInterface;
use Shared\Support\Adapter\CurrencyRateAdapter;
use Shared\Support\Adapter\MailAdapter;
use Shared\Support\Adapter\TravelineAdapter;
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
        $this->app->singleton(ApplicationConstantsInterface::class, ApplicationConstantManager::class);
        $this->app->singleton(CompanyRequisitesInterface::class, CompanyRequisiteManager::class);
        $this->app->singleton(MailAdapterInterface::class, MailAdapter::class);
        $this->app->singleton(CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);
        $this->app->singleton(TravelineAdapterInterface::class, TravelineAdapter::class);
    }

    private function registerSupport(): void
    {
        $this->app->register(TranslationServiceProvider::class);

        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);
    }
}
