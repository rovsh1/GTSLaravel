<?php

namespace Module\Hotel\Domain\Entity;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\ValueObject\SeasonId;
use Module\Shared\Domain\Entity\EntityInterface;

class Season implements EntityInterface
{
    public function __construct(
        private readonly SeasonId $id,
        public readonly string $name,
        public readonly CarbonPeriod $period,
        private ?Hotel $hotel,
        private ?Contract $contract,
    ) {}

    public function id(): SeasonId
    {
        return $this->id;
    }

    public function getHotelId(): int
    {
        return $this->hotel->id()->value();
    }

    public function getContractId(): int
    {
        return $this->contract->id;
    }
}
