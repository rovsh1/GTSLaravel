<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Response\Details;

use Module\Booking\Airport\Domain\Booking\ValueObject\Details\AirportInfo;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class AirportInfoDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|AirportInfo $entity): static
    {
        return new static(
            $entity->id(),
            $entity->name(),
        );
    }
}
