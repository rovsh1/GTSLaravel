<?php

namespace GTS\Services\FileStorage\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use GTS\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use GTS\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use GTS\Services\FileStorage\Domain\Service as DomainService;
use GTS\Services\FileStorage\Infrastructure\Facade;
use GTS\Services\FileStorage\Infrastructure\Repository\DatabaseRepository;
use GTS\Services\FileStorage\Infrastructure\Repository\StorageRepository;
use GTS\Services\FileStorage\Infrastructure\Services\FileUploader;
use Illuminate\Support\Facades\Storage;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $config = $this->app->config();

        $this->app->singleton(Facade\ReaderFacadeInterface::class, Facade\ReaderFacade::class);
        $this->app->singleton(Facade\WriterFacadeInterface::class, Facade\WriterFacade::class);

        $this->app->singleton(DomainService\PathGeneratorInterface::class, function () use ($config) {
            return new DomainService\PathGenerator(
                Storage::disk($config['disk'])->path(''),
                DIRECTORY_SEPARATOR,
                $config['nesting_level'],
                $config['path_name_length'],
            );
        });

        $this->app->singleton(DomainService\UrlGeneratorInterface::class, function () use ($config) {
            return new DomainService\UrlGenerator(
                $config['url']
            );
        });

        $this->app->singleton(StorageRepositoryInterface::class, function ($app) use ($config) {
            return new StorageRepository($config, $app->get(DomainService\PathGeneratorInterface::class));
        });
        $this->app->singleton(DatabaseRepositoryInterface::class, DatabaseRepository::class);

        $this->app->bind('fileUploader', FileUploader::class);
    }
}
