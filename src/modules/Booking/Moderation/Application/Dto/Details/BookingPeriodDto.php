<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details;

use Carbon\Carbon;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;

class BookingPeriodDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly Carbon $dateFrom,
        public readonly Carbon $dateTo,
        public readonly int $nightsCount,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|BookingPeriod $entity): static
    {
        return new static(
            new Carbon($entity->dateFrom()),
            new Carbon($entity->dateTo()),
            $entity->nightsCount()
        );
    }
}
