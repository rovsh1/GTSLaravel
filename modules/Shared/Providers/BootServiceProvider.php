<?php

namespace Module\Shared\Providers;

use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Domain\Service\ApplicationConstantsInterface;
use Module\Shared\Domain\Service\ApplicationContextInterface;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;
use Module\Shared\Infrastructure\Adapter\CurrencyRateAdapter;
use Module\Shared\Infrastructure\Service\ApplicationContext\ApplicationContextManager;
use Module\Shared\Infrastructure\Service\ApplicationsConstants\ApplicationConstantManager;
use Module\Shared\Infrastructure\Service\CompanyRequisites\CompanyRequisiteManager;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ApplicationContextInterface::class, ApplicationContextManager::class);
    }

    public function boot()
    {
        $this->app->singleton(CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);

        $this->app->singleton(ApplicationConstantsInterface::class, ApplicationConstantManager::class);
        $this->app->singleton(CompanyRequisitesInterface::class, CompanyRequisiteManager::class);
    }
}
