<?php

namespace Module\Shared\Testing;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    public function createApplication()
    {
        define('APP_ROOT', realpath(__DIR__ . '/../../../'));
        defined('LARAVEL_START') || define('LARAVEL_START', microtime(true));
        $app = require APP_ROOT . '/bootstrap/app.php';

        $kernel = $app->make(Kernel::class);
        $app->setBasePath(APP_ROOT);
        $app->setNamespace('App\\Core\\');

        $kernel->bootstrap();

        return $app;
    }
}
