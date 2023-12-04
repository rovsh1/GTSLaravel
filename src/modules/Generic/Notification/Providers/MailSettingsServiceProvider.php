<?php

namespace Module\Generic\Notification\Providers;

use Module\Generic\Notification\Domain\Repository\MailSettingsRepositoryInterface;
use Module\Generic\Notification\Domain\Service\RecipientResolverInterface;
use Module\Generic\Notification\Infrastructure\Repository\MailSettingsRepository;
use Module\Generic\Notification\Infrastructure\Service\MailRecipientResolver;
use Sdk\Module\Support\ServiceProvider;

class MailSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(MailSettingsRepositoryInterface::class, MailSettingsRepository::class);
        $this->app->singleton(RecipientResolverInterface::class, MailRecipientResolver::class);
    }
}
