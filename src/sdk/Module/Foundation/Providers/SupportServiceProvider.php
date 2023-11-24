<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Support\Context;
use Sdk\Module\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(ContextInterface::class, Context::class);
    }
}
