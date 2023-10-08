<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPrice;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class RoomDayPriceDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CarbonImmutable $date,
        public readonly int|float $baseValue,
        public readonly int|float $grossValue,
        public readonly int|float $netValue,
        public readonly string $grossFormula,
        public readonly string $netFormula
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomDayPrice $entity): static
    {
        return new static(
            new CarbonImmutable($entity->date()),
            $entity->baseValue(),
            $entity->grossValue(),
            $entity->netValue(),
            $entity->grossFormula(),
            $entity->netFormula(),
        );
    }
}
