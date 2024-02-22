<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Booking\Shared\Domain\Shared\Service\MailTemplateCompilerInterface;
use Module\Booking\Shared\Domain\Shared\Service\TemplateCompilerInterface;
use Module\Booking\Shared\Infrastructure\DbContext\BookingDbContext;
use Module\Booking\Shared\Infrastructure\Repository\BookingRepository;
use Module\Booking\Shared\Infrastructure\Service\BladeTemplateCompiler;
use Module\Booking\Shared\Infrastructure\Service\BookingStatusStorage;
use Module\Booking\Shared\Infrastructure\Service\BookingUnitOfWork;
use Module\Booking\Shared\Infrastructure\Service\PdfTemplateCompiler;
use Illuminate\Support\ServiceProvider;

class BookingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(BookingDbContextInterface::class, BookingDbContext::class);
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->singleton(BookingStatusStorageInterface::class, BookingStatusStorage::class);
        $this->app->singleton(BookingUnitOfWorkInterface::class, BookingUnitOfWork::class);
        $this->app->singleton(TemplateCompilerInterface::class, PdfTemplateCompiler::class);
        $this->app->singleton(MailTemplateCompilerInterface::class, BladeTemplateCompiler::class);
    }
}
