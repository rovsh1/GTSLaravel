<?php

namespace Module\Booking\Shared\Infrastructure\Support;

use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Support\Context;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;

class ModuleContext extends Context
{
    public function __construct(
        ModuleInterface $module,
        private readonly ApplicationContextInterface $applicationContext
    ) {
        parent::__construct($module);
    }

    public function toArray(): array
    {
        return array_merge($this->applicationContext->toArray(), parent::toArray());
    }
}