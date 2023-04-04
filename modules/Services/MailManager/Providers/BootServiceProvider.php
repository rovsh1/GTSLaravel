<?php

namespace Module\Services\MailManager\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Services\MailManager\Domain\Repository\MailRepositoryInterface;
use Module\Services\MailManager\Domain\Service\LoggerInterface;
use Module\Services\MailManager\Domain\Service\SenderInterface;
use Module\Services\MailManager\Infrastructure\Repository\MailRepository;
use Module\Services\MailManager\Infrastructure\Service\Logger;
use Module\Services\MailManager\Infrastructure\Service\Sender;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(MailRepositoryInterface::class, MailRepository::class);
        $this->app->singleton(SenderInterface::class, Sender::class);
        $this->app->singleton(LoggerInterface::class, Logger::class);
    }
}
