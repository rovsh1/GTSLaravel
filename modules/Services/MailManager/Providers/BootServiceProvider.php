<?php

namespace Module\Services\MailManager\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Services\MailManager\Domain\Repository\MailTemplateRepositoryInterface;
use Module\Services\MailManager\Domain\Repository\QueueRepositoryInterface;
use Module\Services\MailManager\Domain\Service\MailerInterface;
use Module\Services\MailManager\Domain\Service\QueueManager;
use Module\Services\MailManager\Domain\Service\QueueManagerInterface;
use Module\Services\MailManager\Infrastructure\Repository\MailTemplateRepository;
use Module\Services\MailManager\Infrastructure\Repository\QueueRepository;
use Module\Services\MailManager\Infrastructure\Service\Mailer;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(QueueManagerInterface::class, QueueManager::class);
        $this->app->singleton(QueueRepositoryInterface::class, QueueRepository::class);

        $this->app->singleton(MailTemplateRepositoryInterface::class, MailTemplateRepository::class);
        $this->app->singleton(MailerInterface::class, Mailer::class);
    }
}
