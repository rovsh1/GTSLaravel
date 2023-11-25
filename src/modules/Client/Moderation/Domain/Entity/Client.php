<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Entity;

use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\TypeEnum;

class Client extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly ClientId $id,
        private string $name,
        private readonly TypeEnum $type,
        private ResidencyEnum $residency,
    ) {}

    public function id(): ClientId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function type(): TypeEnum
    {
        return $this->type;
    }

    public function residency(): ResidencyEnum
    {
        return $this->residency;
    }

    public function setResidency(ResidencyEnum $residency): void
    {
        $this->residency = $residency;
    }
}
