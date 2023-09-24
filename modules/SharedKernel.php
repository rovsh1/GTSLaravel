<?php

namespace Module;

use Module\Shared\Providers\BootServiceProvider;
use Sdk\Module\Foundation\SharedKernel as BaseKernel;

class SharedKernel extends BaseKernel
{
    protected function registerServiceProviders(): void
    {
        $this->register(BootServiceProvider::class);
    }
}
