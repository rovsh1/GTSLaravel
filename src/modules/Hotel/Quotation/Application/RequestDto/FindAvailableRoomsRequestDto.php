<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\RequestDto;

use Carbon\CarbonPeriod;

final class FindAvailableRoomsRequestDto
{
    public function __construct(
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
    ) {
    }
}
