<?php

namespace App\Shared\Providers;

use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $path = database_path('migrations');
        $paths = [
            $path,
            $path . DIRECTORY_SEPARATOR . 'install',
        ];
        $this->loadMigrationsFrom($paths);
    }
}
