<?php

namespace Module\Catalog\Application\Admin\Response;

use Carbon\CarbonInterface;
use Sdk\Module\Foundation\Support\Dto\Dto;

class QuotaDto extends Dto
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
    ) {}
}
