<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Response\Details;

use Module\Booking\Deprecated\AirportBooking\ValueObject\Details\AirportInfo;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
