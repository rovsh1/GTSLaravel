<?php

namespace GTS\Hotel\Domain\Entity;

use Carbon\CarbonPeriod;

class Season
{
    public function __construct(
        public readonly int          $id,
        public readonly string       $name,
        public readonly CarbonPeriod $period,
        private ?Hotel               $hotel,
        private ?Contract            $contract,
    ) {}

    public function getHotelId(): int
    {
        return $this->hotel->id;
    }

    public function getContractId(): int
    {
        return $this->contract->id;
    }
}
