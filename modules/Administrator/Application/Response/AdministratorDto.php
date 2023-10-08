<?php

declare(strict_types=1);

namespace Module\Administrator\Application\Response;

use Module\Administrator\Domain\Entity\Administrator;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class AdministratorDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $presentation,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $name,
        public readonly ?string $surname,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Administrator $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->presentation(),
            $entity->email(),
            $entity->phone(),
            $entity->name(),
            $entity->surname(),
        );
    }
}
