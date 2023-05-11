<?php

namespace Module\Hotel\Application\Query;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Enums\QuotaAvailabilityEnum;

class GetQuotas implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
        public readonly ?int $roomId = null,
        public readonly ?QuotaAvailabilityEnum $availability = null
    ) {}
}
