<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Module\Shared\Infrastructure\Service\ApplicationsConstants\ApplicationConstantManager;
use Module\Shared\Infrastructure\Service\CompanyRequisites\CompanyRequisiteManager;
use Module\Shared\Infrastructure\Service\TransactionalExecutor;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;

class ServicesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(SafeExecutorInterface::class, TransactionalExecutor::class);
        $this->app->singleton(ApplicationConstantsInterface::class, ApplicationConstantManager::class);
        $this->app->singleton(CompanyRequisitesInterface::class, CompanyRequisiteManager::class);
    }
}
