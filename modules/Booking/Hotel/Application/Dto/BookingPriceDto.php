<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Hotel\Application\Dto\Details\RoomBooking\ManualChangablePriceDto;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class BookingPriceDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly float $netValue,
        public readonly ManualChangablePriceDto $hoValue,
        public readonly ManualChangablePriceDto $boValue
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|BookingPrice $entity): static
    {
        return new static(
            $entity->netValue(),
            ManualChangablePriceDto::fromDomain($entity->hoValue()),
            ManualChangablePriceDto::fromDomain($entity->boValue()),
        );
    }
}
