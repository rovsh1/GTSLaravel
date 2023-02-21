<?php

namespace Module\Shared\Tests;

use App\Core\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AdminTestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../bootstrap/app.php';

        $app->instance(
            \Module\Shared\UI\Common\Contracts\UIServiceProvider::class,
            \Module\Shared\UI\Admin\Providers\BootServiceProvider::class
        );

//        $app->singleton(
//            \Illuminate\Contracts\Debug\ExceptionHandler::class,
//            \Ustabor\Domain\Site\Exceptions\Handler::class
//        );

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
