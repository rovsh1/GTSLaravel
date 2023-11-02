<?php

namespace Module\Generic\Notification\Providers;

use Module\Generic\Notification\Domain\Shared\Adapter\MailAdapterInterface;
use Module\Generic\Notification\Infrastructure\Adapter\MailAdapter;
use Sdk\Module\Support\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(MailAdapterInterface::class, MailAdapter::class);
    }
}
