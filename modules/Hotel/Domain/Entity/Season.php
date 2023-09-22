<?php

namespace Module\Hotel\Domain\Entity;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\ValueObject\ContractId;
use Module\Hotel\Domain\ValueObject\HotelId;
use Module\Hotel\Domain\ValueObject\SeasonId;
use Module\Shared\Domain\Entity\EntityInterface;

class Season implements EntityInterface
{
    public function __construct(
        private readonly SeasonId $id,
        public string $name,
        public CarbonPeriod $period,
        private HotelId $hotelId,
        private ContractId $contractId,
    ) {}

    public function id(): SeasonId
    {
        return $this->id;
    }

    public function hotelId(): HotelId
    {
        return $this->hotelId;
    }

    public function contractId(): ContractId
    {
        return $this->contractId;
    }
}
