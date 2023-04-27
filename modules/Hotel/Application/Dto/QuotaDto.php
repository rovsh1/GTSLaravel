<?php

namespace Module\Hotel\Application\Dto;

use Carbon\CarbonInterface;
use Custom\Framework\Foundation\Support\Dto\Dto;

class QuotaDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly string $roomId,
        public readonly CarbonInterface $date,
        public readonly bool $status,
        public readonly int $releaseDays,
        public readonly int $countAvailable,
        public readonly int $countBooked,
        public readonly int $countReserved,
    ) {}
}
