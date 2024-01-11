<?php

namespace Pkg\MailManager;

use Illuminate\Support\ServiceProvider;
use Pkg\MailManager\Contracts\MailerInterface;
use Pkg\MailManager\Contracts\MailManagerInterface;
use Pkg\MailManager\Contracts\QueueStorageInterface;
use Pkg\MailManager\Queue\MailConnector;
use Pkg\MailManager\Service\Mailer;
use Pkg\MailManager\Service\MailManager;
use Pkg\MailManager\Storage\QueueStorage;
use Shared\Contracts\Adapter\MailAdapterInterface;
use Shared\Support\Adapter\MailAdapter;

class MailManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerManager();
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->registerMigrations();
        }

        $this->app->singleton(MailAdapterInterface::class, MailAdapter::class);
        $this->app->singleton(MailManagerInterface::class, MailManager::class);
        $this->app->singleton(QueueStorageInterface::class, QueueStorage::class);

        $this->app->singleton(MailerInterface::class, Mailer::class);
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerManager(): void
    {
        $this->app->resolving('queue', function ($manager) {
            $manager->addConnector('mail', function () {
                return app()->make(MailConnector::class);
            });
        });
    }
}
