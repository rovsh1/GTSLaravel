<?php

namespace Service\IntegrationEventGateway;

use Module\Shared\Contracts\Event\IntegrationEventInterface;
use Module\Shared\Contracts\Event\IntegrationEventSubscriberInterface;

class IntegrationEventDispatcher
{
    public function dispatch(IntegrationEventInterface $event): void
    {
        foreach (app()->modules() as $module) {
            try {
                $module->get(IntegrationEventSubscriberInterface::class)->handle($event);
            } catch (\Throwable $e) {
                throw $e;
            }
        }
    }
}