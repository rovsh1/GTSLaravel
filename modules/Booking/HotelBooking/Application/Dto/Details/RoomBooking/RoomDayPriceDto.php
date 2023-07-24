<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking;

use Carbon\CarbonImmutable;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomDayPrice;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomDayPriceDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CarbonImmutable $date,
        public readonly int|float $netValue,
        public readonly int|float $boValue,
        public readonly int|float $hoValue,
        public readonly string $boFormula,
        public readonly string $hoFormula
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomDayPrice $entity): static
    {
        return new static(
            new CarbonImmutable($entity->date()),
            $entity->netValue(),
            $entity->boValue(),
            $entity->hoValue(),
            $entity->boFormula(),
            $entity->hoFormula(),
        );
    }
}
