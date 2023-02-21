<?php

namespace Module\Services\FileStorage\Providers;

use Illuminate\Support\Facades\Storage;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Services\FileStorage\Infrastructure\Repository\DatabaseRepository;
use Module\Services\FileStorage\Infrastructure\Repository\StorageRepository;
use Module\Services\FileStorage\Infrastructure\Services\FileUploader;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $config = $this->app->config();

        $this->app->singleton(\Module\Services\FileStorage\Infrastructure\Facade\ReaderFacadeInterface::class, \Module\Services\FileStorage\Infrastructure\Facade\ReaderFacade::class);
        $this->app->singleton(\Module\Services\FileStorage\Infrastructure\Facade\WriterFacadeInterface::class, \Module\Services\FileStorage\Infrastructure\Facade\WriterFacade::class);

        $this->app->singleton(\Module\Services\FileStorage\Domain\Service\PathGeneratorInterface::class, function () use ($config) {
            return new \Module\Services\FileStorage\Domain\Service\PathGenerator(
                Storage::disk($config['disk'])->path(''),
                DIRECTORY_SEPARATOR,
                $config['nesting_level'],
                $config['path_name_length'],
            );
        });

        $this->app->singleton(\Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface::class, function () use ($config) {
            return new \Module\Services\FileStorage\Domain\Service\UrlGenerator(
                $config['url']
            );
        });

        $this->app->singleton(StorageRepositoryInterface::class, function ($app) use ($config) {
            return new StorageRepository($config, $app->get(\Module\Services\FileStorage\Domain\Service\PathGeneratorInterface::class));
        });
        $this->app->singleton(DatabaseRepositoryInterface::class, DatabaseRepository::class);

        $this->app->bind('fileUploader', FileUploader::class);
    }
}
