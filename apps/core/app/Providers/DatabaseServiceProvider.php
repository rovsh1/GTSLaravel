<?php

namespace App\Core\Providers;

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
            $path . DIRECTORY_SEPARATOR . 'deprecated',
        ];
        $this->loadMigrationsFrom($paths);
    }
}
