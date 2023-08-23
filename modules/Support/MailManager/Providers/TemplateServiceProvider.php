<?php

namespace Module\Support\MailManager\Providers;

use Module\Support\MailManager\Domain\Service\DataBuilder\DataBuilderInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\AddressResolverInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientsFinderInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\TemplateRendererInterface;
use Module\Support\MailManager\Infrastructure\Service\DataBuilder\CommonDataBuilder;
use Module\Support\MailManager\Infrastructure\Service\MailTemplateRenderer;
use Module\Support\MailManager\Infrastructure\Service\RecipientsFinder\AddressResolver;
use Module\Support\MailManager\Infrastructure\Service\RecipientsFinder\RecipientsFinder;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class TemplateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(TemplateRendererInterface::class, MailTemplateRenderer::class);
        $this->app->singleton(RecipientsFinderInterface::class, RecipientsFinder::class);
        $this->app->singleton(AddressResolverInterface::class, AddressResolver::class);
        $this->app->singleton(DataBuilderInterface::class, CommonDataBuilder::class);
    }
}
