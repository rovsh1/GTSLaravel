<?php

namespace Module\Support\MailManager\Providers;

use Module\Support\MailManager\Domain\Repository\QueueRepositoryInterface;
use Module\Support\MailManager\Domain\Service\MailerInterface;
use Module\Support\MailManager\Domain\Service\QueueManager;
use Module\Support\MailManager\Domain\Service\QueueManagerInterface;
use Module\Support\MailManager\Infrastructure\Repository\QueueRepository;
use Module\Support\MailManager\Infrastructure\Service\Mailer;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(TemplateServiceProvider::class);
        $this->app->register(AdapterServiceProvider::class);
    }

    public function boot()
    {
        $this->app->singleton(QueueManagerInterface::class, QueueManager::class);
        $this->app->singleton(QueueRepositoryInterface::class, QueueRepository::class);

        $this->app->singleton(MailerInterface::class, Mailer::class);
    }
}