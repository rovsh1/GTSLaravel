<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\Dto;

use Module\Client\Moderation\Domain\Entity\Client;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Enum\Client\ResidencyEnum;
use Module\Shared\Enum\Client\TypeEnum;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

final class ClientDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
        public readonly ResidencyEnum $residency,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Client $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->name(),
            $entity->type()->value,
            $entity->residency()
        );
    }

    public function isLegal(): bool
    {
        return $this->type === TypeEnum::LEGAL_ENTITY->value;
    }
}
