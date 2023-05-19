<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Entity;

use Module\Shared\Domain\Entity\EntityInterface;

final class Order implements EntityInterface
{
    public function __construct(
        private readonly int $id,
        private readonly int $clientId
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function clientId(): int
    {
        return $this->clientId;
    }
}
