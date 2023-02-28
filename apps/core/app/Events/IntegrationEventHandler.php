<?php

namespace App\Core\Events;

use App\Core\Support\ModulesRepositoryInterface;
use Custom\Framework\Contracts\Event\IntegrationEventDispatcherInterface;
use Custom\Framework\Contracts\Event\IntegrationEventHandlerInterface;
use Custom\Framework\Contracts\Event\IntegrationEventInterface;

class IntegrationEventHandler implements IntegrationEventHandlerInterface
{
    public function __construct(
        private readonly ModulesRepositoryInterface $modulesRepository
    ) {}

    public function handle(IntegrationEventInterface $event)
    {
        foreach ($this->modulesRepository->registeredModules() as $module) {
            if (!$module->is($event->module())) {
                $module->get(IntegrationEventDispatcherInterface::class)->dispatch($event);
            }
        }
    }
}
