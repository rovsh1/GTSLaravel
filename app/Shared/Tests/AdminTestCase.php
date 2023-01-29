<?php

namespace GTS\Shared\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use GTS\Shared\UI\Console\Kernel;

abstract class AdminTestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../bootstrap/app.php';

        $app->instance(
            \GTS\Shared\UI\Common\Contracts\UIServiceProvider::class,
            \GTS\Shared\UI\Admin\Providers\BootServiceProvider::class
        );

//        $app->singleton(
//            \Illuminate\Contracts\Debug\ExceptionHandler::class,
//            \Ustabor\Domain\Site\Exceptions\Handler::class
//        );

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
