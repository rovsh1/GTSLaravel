<?php

namespace Module\Hotel\Domain\Entity;

use Carbon\CarbonPeriod;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;

class Season implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        public readonly string $name,
        public readonly CarbonPeriod $period,
        private ?Hotel $hotel,
        private ?Contract $contract,
    ) {}

    public function id(): Id
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
