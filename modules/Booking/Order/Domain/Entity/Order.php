<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Entity;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;

final class Order implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private readonly int $clientId
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function clientId(): int
    {
        return $this->clientId;
    }
}
