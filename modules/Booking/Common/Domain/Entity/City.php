<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;

class City implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private readonly string $name,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
