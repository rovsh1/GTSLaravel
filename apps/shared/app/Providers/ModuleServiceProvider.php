<?php

namespace App\Shared\Providers;

use App\Shared\Support\Module\Monolith\ModuleAdapterFactory;
use App\Shared\Support\Module\Monolith\SharedKernel;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    private array $modules = [
//        'booking' => 'Booking'
    ];

    public function register(): void
    {
        $kernel = new SharedKernel($this->app);
        $this->app->booting(function () use ($kernel) {
            $kernel->boot();
        });

        $modules = app()->modules();
        $monolithFactory = new ModuleAdapterFactory(
            modulesPath: base_path('modules'),
            modulesNamespace: 'Module',
            sharedContainer: $kernel->getContainer()
        );

        foreach (config('modules') as $name => $config) {
            $adapter = $monolithFactory->build($name, $config);
            $modules->register($adapter);
        }
//        foreach ($this->modules as $name => $path) {
//            $adapter = $monolithFactory->build($name, $path);
//            $modules->register($adapter);
//        }
    }

    public function boot(): void
    {
    }
}
