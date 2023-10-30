<?php

namespace Module\Catalog\Domain\Hotel\Entity;

use Carbon\CarbonPeriod;
use Module\Catalog\Domain\Hotel\ValueObject\ContractId;

class Contract
{
    public function __construct(
        public readonly ContractId $id,
        public readonly int $number,
        public readonly CarbonPeriod $period,
    ) {}
}
