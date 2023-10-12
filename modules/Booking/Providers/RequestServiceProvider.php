<?php

namespace Module\Booking\Providers;

use Module\Booking\Domain\BookingRequest\Repository\RequestRepositoryInterface;
use Module\Booking\Domain\BookingRequest\Service\TemplateCompilerInterface;
use Module\Booking\Infrastructure\BookingRequest\Repository\RequestRepository;
use Module\Booking\Infrastructure\Service\RequestTemplateCompiler;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(TemplateCompilerInterface::class, RequestTemplateCompiler::class);
    }
}