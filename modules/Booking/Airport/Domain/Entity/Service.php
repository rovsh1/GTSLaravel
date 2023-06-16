<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Entity;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\Booking\ServiceTypeEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Service extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private string $name,
        private readonly ServiceTypeEnum $type,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): ServiceTypeEnum
    {
        return $this->type;
    }
}
