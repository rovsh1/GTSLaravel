<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto\Details\RoomBooking;

use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomPriceDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly float $netValue,
        public readonly float $avgDailyValue,
        public readonly float $hoValue,
        public readonly float $boValue,
        public readonly ?string $hoNote,
        public readonly ?string $boNote,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomPrice $entity): static
    {
        return new static(
            $entity->netValue(),
            $entity->avgDailyValue(),
            $entity->hoValue(),
            $entity->boValue(),
            $entity->calculationNotes()?->hoNote(),
            $entity->calculationNotes()?->boNote(),
        );
    }
}
