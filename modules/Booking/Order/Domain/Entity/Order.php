<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Entity;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;

final class Order implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private readonly Id $clientId,
        private readonly ?Id $legalId
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function clientId(): Id
    {
        return $this->clientId;
    }

    public function legalId(): ?Id
    {
        return $this->legalId;
    }
}
