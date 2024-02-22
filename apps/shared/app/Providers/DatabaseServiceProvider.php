<?php

namespace App\Shared\Providers;

use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $path = database_path('migrations');
        $paths = [
            $path,
            $path . DIRECTORY_SEPARATOR . 'install',
        ];
        $this->loadMigrationsFrom($paths);
    }
}
