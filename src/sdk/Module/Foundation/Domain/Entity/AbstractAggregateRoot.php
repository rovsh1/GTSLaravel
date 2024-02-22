<?php

declare(strict_types=1);

namespace Sdk\Module\Foundation\Domain\Entity;

use Sdk\Module\Contracts\Event\DomainEventInterface;

abstract class AbstractAggregateRoot
{
    /** @var DomainEventInterface[] */
    private array $domainEvents = [];

    /**
     * @return DomainEventInterface[]
     */
    public function pullEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function pushEvent(DomainEventInterface $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
