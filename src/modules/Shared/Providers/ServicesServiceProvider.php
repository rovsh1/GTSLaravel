<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Services\ApplicationsConstants\ApplicationConstantManager;
use Services\CompanyRequisites\CompanyRequisiteManager;

class ServicesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(ApplicationConstantsInterface::class, ApplicationConstantManager::class);
        $this->app->singleton(CompanyRequisitesInterface::class, CompanyRequisiteManager::class);
    }
}
