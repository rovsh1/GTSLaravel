<?php

namespace Module\Booking\Shared\Infrastructure\Service\UnitOfWork;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Contracts\Entity\BookingPartInterface;

class IdentityMap
{
    private array $identityMap = [];

    private array $originalData = [];

    public function add(Booking|BookingPartInterface $entity): void
    {
        $id = $this->entityId($entity);
        if (isset($this->identityMap[$id])) {
            return;
        }
        $this->identityMap[$id] = $entity;
        $this->originalData[$id] = $entity->serialize();
    }

    public function shift(): Booking|BookingPartInterface|null
    {
        return array_shift($this->identityMap);
    }

    public function isEmpty(): bool
    {
        return empty($this->identityMap);
    }

    public function reset(): void
    {
        $this->identityMap = [];
        $this->originalData = [];
    }

    public function isChanged(Booking|BookingPartInterface $entity): bool
    {
        $id = $this->entityId($entity);

        return json_encode($this->originalData[$id]) !== json_encode($entity->serialize());
    }

    private function entityId(Booking|BookingPartInterface $entity): string
    {
        return spl_object_hash($entity);
    }
}