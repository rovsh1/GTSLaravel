<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Entity;

use Carbon\CarbonPeriod;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\ContractId;

class Contract
{
    public function __construct(
        public readonly ContractId $id,
        public readonly int $number,
        public readonly CarbonPeriod $period,
    ) {}
}
