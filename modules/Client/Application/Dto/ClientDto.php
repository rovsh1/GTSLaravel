<?php

declare(strict_types=1);

namespace Module\Client\Application\Dto;

use Module\Client\Domain\Entity\Client;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class ClientDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Client $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->name(),
            $entity->type()->value
        );
    }
}
