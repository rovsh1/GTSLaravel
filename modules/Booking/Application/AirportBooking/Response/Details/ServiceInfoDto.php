<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Response\Details;

use Module\Booking\Domain\AirportBooking\ValueObject\Details\ServiceInfo;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ServiceInfoDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|ServiceInfo $entity): static
    {
        return new static(
            $entity->id(),
            $entity->name(),
            $entity->type()->value
        );
    }
}