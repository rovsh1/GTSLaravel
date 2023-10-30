<?php

namespace Module\Shared\Tests;

use App\Console\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AdminTestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../bootstrap/app.php';

//        $app->singleton(
//            \Illuminate\Contracts\Debug\ExceptionHandler::class,
//            \Ustabor\Domain\Site\Exceptions\Handler::class
//        );

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
