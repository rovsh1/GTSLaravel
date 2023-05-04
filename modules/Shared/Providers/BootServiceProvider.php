<?php

namespace Module\Shared\Providers;

use Custom\Framework\Foundation\Support\Providers\SharedServiceProvider;
use Module\Shared\Domain\Service\ConstantManager;

class BootServiceProvider extends SharedServiceProvider
{
    public function boot()
    {
        $this->sharedSingleton(ConstantManager::class, ConstantManager::class);
    }
}
