<?php

namespace Module\Support\MailManager\Providers;

use Module\Support\MailManager\Domain\Service\MailerInterface;
use Module\Support\MailManager\Domain\Service\MailManager;
use Module\Support\MailManager\Domain\Service\MailManagerInterface;
use Module\Support\MailManager\Domain\Storage\QueueStorageInterface;
use Module\Support\MailManager\Infrastructure\Service\Mailer;
use Module\Support\MailManager\Infrastructure\Storage\QueueStorage;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->app->singleton(MailManagerInterface::class, MailManager::class);
        $this->app->singleton(QueueStorageInterface::class, QueueStorage::class);

        $this->app->singleton(MailerInterface::class, Mailer::class);
    }
}
