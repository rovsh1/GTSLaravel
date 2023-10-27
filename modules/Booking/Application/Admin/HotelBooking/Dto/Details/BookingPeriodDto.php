<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details;

use Carbon\Carbon;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
