<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details;

use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\HotelBooking\HotelInfo;

class HotelInfoDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof HotelInfo);

        return new static(
            $entity->id(),
            $entity->name(),
            $entity->checkInTime()->value(),
            $entity->checkOutTime()->value(),
        );
    }
}
