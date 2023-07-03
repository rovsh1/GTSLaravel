<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto\Details\RoomBooking;

use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ManualChangablePriceDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly float $value,
        public readonly bool $isManual
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|ManualChangablePrice $entity): static
    {
        return new static(
            $entity->value(),
            $entity->isManual()
        );
    }
}
