<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Module\Hotel\Domain\Entity\Hotel;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class HotelDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly TimeSettingsDto $timeSettings
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Hotel $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->name(),
            TimeSettingsDto::fromDomain($entity->timeSettings())
        );
    }
}
