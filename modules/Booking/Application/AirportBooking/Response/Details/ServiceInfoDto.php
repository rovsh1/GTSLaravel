<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Response\Details;

use Module\Booking\Deprecated\AirportBooking\ValueObject\Details\ServiceInfo;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
