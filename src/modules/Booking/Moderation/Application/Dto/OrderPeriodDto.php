<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\Carbon;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\OrderPeriod;

class OrderPeriodDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly Carbon $dateFrom,
        public readonly Carbon $dateTo,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof OrderPeriod);

        return new static(
            new Carbon($entity->dateFrom()),
            new Carbon($entity->dateTo()),
        );
    }
}
