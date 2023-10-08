<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Shared\Contracts\Service\ApplicationConstantsInterface;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Module\Shared\Contracts\Service\SerializerInterface;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Infrastructure\Service\ApplicationsConstants\ApplicationConstantManager;
use Module\Shared\Infrastructure\Service\CompanyRequisites\CompanyRequisiteManager;
use Module\Shared\Infrastructure\Service\JsonSerializer;
use Module\Shared\Infrastructure\Service\TransactionalExecutor;
use Module\Support\LocaleTranslator\Translator;

class ServicesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(TranslatorInterface::class, Translator::class);
        $this->app->singleton(SerializerInterface::class, JsonSerializer::class);
        $this->app->singleton(SafeExecutorInterface::class, TransactionalExecutor::class);
        $this->app->singleton(ApplicationConstantsInterface::class, ApplicationConstantManager::class);
        $this->app->singleton(CompanyRequisitesInterface::class, CompanyRequisiteManager::class);
    }
}
