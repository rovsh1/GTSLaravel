<?php

namespace Module\Hotel\Domain\Entity;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\ValueObject\ContractId;

class Contract
{
    public function __construct(
        public readonly ContractId $id,
        public readonly int $number,
        public readonly CarbonPeriod $period,
    ) {}
}
