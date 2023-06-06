<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto\Details;

use Carbon\Carbon;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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
