<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\Dto\Details\RoomBooking;

use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomPriceDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly ?float $grossDayValue,
        public readonly ?float $netDayValue,
        /** @var RoomDayPriceDto[] $dayPrices */
        public readonly array $dayPrices,
        public readonly int|float $grossValue,
        public readonly int|float $netValue,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomPrice $entity): static
    {
        return new static(
            $entity->grossDayValue(),
            $entity->netDayValue(),
            RoomDayPriceDto::collectionFromDomain($entity->dayPrices()->all()),
            $entity->grossValue(),
            $entity->netValue(),
        );
    }
}
