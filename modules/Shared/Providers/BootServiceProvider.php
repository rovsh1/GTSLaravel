<?php

namespace Module\Shared\Providers;

use Module\Shared\Domain\Service\ConstantManager;
use Module\Shared\Domain\Service\TranslatorInterface;
use Module\Shared\Infrastructure\Service\Translator;

class BootServiceProvider extends \Sdk\Module\Foundation\Support\Providers\SharedServiceProvider
{
    public function boot()
    {
        $this->sharedSingleton(ConstantManager::class, ConstantManager::class);
        $this->sharedSingleton(TranslatorInterface::class, Translator::class);
    }
}
