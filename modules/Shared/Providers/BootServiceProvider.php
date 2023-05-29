<?php

namespace Module\Shared\Providers;

use Module\Shared\Domain\Service\ConstantManager;

class BootServiceProvider extends \Sdk\Module\Foundation\Support\Providers\SharedServiceProvider
{
    public function boot()
    {
        $this->sharedSingleton(ConstantManager::class, ConstantManager::class);
    }
}
