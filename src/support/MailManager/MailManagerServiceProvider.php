<?php

namespace Support\MailManager;

use Sdk\Module\Support\ServiceProvider;
use Sdk\Shared\Contracts\Adapter\MailAdapterInterface;
use Support\MailManager\Contracts\MailerInterface;
use Support\MailManager\Contracts\MailManagerInterface;
use Support\MailManager\Contracts\QueueStorageInterface;
use Support\MailManager\Service\Mailer;
use Support\MailManager\Service\MailManager;
use Support\MailManager\Storage\QueueStorage;

class MailManagerServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->app->singleton(MailAdapterInterface::class, MailAdapter::class);
        $this->app->singleton(MailManagerInterface::class, MailManager::class);
        $this->app->singleton(QueueStorageInterface::class, QueueStorage::class);

        $this->app->singleton(MailerInterface::class, Mailer::class);
    }
}
