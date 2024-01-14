<?php

namespace Pkg\Supplier\Traveline\Dto;

use Carbon\CarbonInterface;

final class QuotaDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $roomId,
        public readonly CarbonInterface $date,
        public readonly bool $status,
        public readonly int $releaseDays,
        public readonly int $countTotal,
        public readonly int $countAvailable,
        public readonly int $countBooked,
        public readonly int $countReserved,
    ) {
    }
}
