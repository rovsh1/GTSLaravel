<?php

namespace Module\Booking\Shared\Infrastructure\Service\UnitOfWork;

use Sdk\Booking\Contracts\Entity\BookingPartInterface;

class IdentityMap
{
    private array $identityMap = [];

    private array $originalData = [];

    public function add(BookingPartInterface $entity): void
    {
        $id = $this->entityId($entity);
        if (isset($this->identityMap[$id])) {
            return;
        }
        $this->identityMap[$id] = $entity;
        $this->originalData[$id] = $entity->serialize();
    }

    public function shift(): ?BookingPartInterface
    {
        $entity = array_shift($this->identityMap);
        if ($this->isDirty($entity)) {
            return $entity;
        }

        return null;
    }

    public function reset(): void
    {
        $this->identityMap = [];
        $this->originalData = [];
    }

    private function entityId(BookingPartInterface $entity): string
    {
        return spl_object_hash($entity);
    }

    private function isDirty(BookingPartInterface $entity): bool
    {
        $id = $this->entityId($entity);

        return json_encode($this->originalData[$id]) !== json_encode($entity->serialize());
    }
}