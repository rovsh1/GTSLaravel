<?php

namespace Module\Hotel\Quotation\Application\Dto;

use Carbon\CarbonImmutable;

final class QuotaDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $hotelId,
        public readonly int $roomId,
        public readonly CarbonImmutable $date,
        public readonly bool $status,
        public readonly int $releaseDays,
        public readonly int $countTotal,
        public readonly int $countAvailable,
        public readonly int $countBooked,
        public readonly int $countReserved,
    ) {}
}
