<?php

namespace Module\Booking\Shared\Infrastructure\Service\UnitOfWork;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Contracts\Entity\BookingPartInterface;

class IdentityMap
{
    private static array $identityMap = [];

    private static array $originalData = [];

    public function add(Booking|BookingPartInterface $entity): void
    {
        $id = $this->entityId($entity);
        if (isset($this->identityMap[$id])) {
            return;
        }
        self::$identityMap[$id] = $entity;
        self::$originalData[$id] = $entity->serialize();
    }

    public function shift(): Booking|BookingPartInterface|null
    {
        return array_shift(self::$identityMap);
    }

    public function isEmpty(): bool
    {
        return empty(self::$identityMap);
    }

    public function reset(): void
    {
        self::$identityMap = [];
        self::$originalData = [];
    }

    public function isChanged(Booking|BookingPartInterface $entity): bool
    {
        $id = $this->entityId($entity);

        return json_encode(self::$originalData[$id]) !== json_encode($entity->serialize());
    }

    private function entityId(Booking|BookingPartInterface $entity): string
    {
        return spl_object_hash($entity);
    }
}
