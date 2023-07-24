<?php

namespace Module\Support\FileStorage\Providers;

use Illuminate\Support\Facades\Storage;
use Module\Support\FileStorage\Domain\Repository\CacheRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Support\FileStorage\Domain\Service\PathGenerator;
use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;
use Module\Support\FileStorage\Domain\Service\UrlGenerator;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;
use Module\Support\FileStorage\Infrastructure\Repository\CacheRepository;
use Module\Support\FileStorage\Infrastructure\Repository\DatabaseRepository;
use Module\Support\FileStorage\Infrastructure\Repository\StorageRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $config = $this->app->config();

        $this->app->singleton(PathGeneratorInterface::class, function () use ($config) {
            return new PathGenerator(
                Storage::disk($config['disk'])->path(''),
                DIRECTORY_SEPARATOR,
                $config['nesting_level'],
                $config['path_name_length'],
            );
        });

        $this->app->singleton(UrlGeneratorInterface::class, function ($app) use ($config) {
            return new UrlGenerator(
                $config['url'],
                $app->get(PathGeneratorInterface::class)
            );
        });

        $this->app->singleton(StorageRepositoryInterface::class, function ($app) use ($config) {
            return new StorageRepository($config, $app->get(PathGeneratorInterface::class));
        });
        $this->app->singleton(DatabaseRepositoryInterface::class, DatabaseRepository::class);
        $this->app->singleton(CacheRepositoryInterface::class, CacheRepository::class);
    }
}
