<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking;

use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomPriceDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly ?float $boDayValue,
        public readonly ?float $hoDayValue,
        /** @var RoomDayPriceDto[] $dayPrices */
        public readonly array $dayPrices,
        public readonly int|float $boValue,
        public readonly int|float $hoValue,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomPrice $entity): static
    {
        return new static(
            $entity->boDayValue(),
            $entity->hoDayValue(),
            RoomDayPriceDto::collectionFromDomain($entity->dayPrices()->all()),
            $entity->boValue(),
            $entity->hoValue(),
        );
    }
}
