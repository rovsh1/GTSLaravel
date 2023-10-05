<?php

namespace Module\Pricing\Providers;

use Module\Administrator\Domain;
use Module\Administrator\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public $singletons = [
//        Domain\Repository\AdministratorRepositoryInterface::class => Infrastructure\Repository\AdministratorRepository::class,
    ];

    public function boot() {}
}
