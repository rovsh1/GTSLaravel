<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Contracts;

use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

interface IntegrationEventDispatcherInterface
{
    public function listen(string $eventClass, string $listenerClass): void;

    public function dispatch(IntegrationEventInterface $event): void;
}
