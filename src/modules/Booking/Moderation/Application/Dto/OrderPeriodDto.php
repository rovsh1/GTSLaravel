<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Module\Booking\Moderation\Domain\Order\ValueObject\OrderPeriod;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class OrderPeriodDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CarbonInterface $dateFrom,
        public readonly CarbonInterface $dateTo,
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
