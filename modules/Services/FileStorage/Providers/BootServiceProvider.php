<?php

namespace Module\Services\FileStorage\Providers;

use Illuminate\Support\Facades\Storage;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Services\FileStorage\Domain\Service\PathGenerator;
use Module\Services\FileStorage\Domain\Service\PathGeneratorInterface;
use Module\Services\FileStorage\Domain\Service\UrlGenerator;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;
use Module\Services\FileStorage\Infrastructure\Repository\DatabaseRepository;
use Module\Services\FileStorage\Infrastructure\Repository\StorageRepository;

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
    }
}
