<?php

namespace Module\Shared\Providers;

use Module\Shared\Domain;
use Module\Shared\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public $singletons = [
        Domain\Repository\ConstantRepositoryInterface::class => Infrastructure\Repository\ConstantRepository::class,
    ];

    public function register() {}
}
