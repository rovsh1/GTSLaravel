<?php

namespace App\Core\Events;

use App\Core\Support\ModulesRepositoryInterface;
use Sdk\Module\Contracts\Event\IntegrationEventDispatcherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventHandlerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class IntegrationEventHandler implements IntegrationEventHandlerInterface
{
    public function __construct(
        private readonly ModulesRepositoryInterface $modulesRepository
    ) {}

    public function handle(IntegrationEventInterface $event)
    {
        $this->broadcastModules($event);
    }

    private function broadcastModules(IntegrationEventInterface $event)
    {
        foreach ($this->modulesRepository->registeredModules() as $module) {
            if (!$module->is($event->module())) {
                $module->get(IntegrationEventDispatcherInterface::class)->dispatch($event);
            }
        }
    }
}
