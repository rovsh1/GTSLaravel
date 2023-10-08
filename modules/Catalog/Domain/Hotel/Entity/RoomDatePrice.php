<?php

namespace Module\Catalog\Domain\Hotel\Entity;

use Module\Hotel\Domain\ValueObject\RoomPrice as ValueObject;
use Sdk\Module\Support\Facades\DateTimeInterface;

class RoomDatePrice
{
    public function __construct(
        private readonly \Module\Catalog\Domain\Hotel\ValueObject\RoomPrice\Identifier $identifier,
//        $roomId,
//        $date,

        private readonly ValueObject\GuestsCriteria $guestsParams,
//        $guestsCount,
//        $residentType,
//        $residentType,

        private readonly ValueObject\Aggregator $aggregator,
//        $aggregatorId,

// Расчетные данные
//        $seasonId,
//        $rateId,
    ) {}

    public function identifier(): \Module\Catalog\Domain\Hotel\ValueObject\RoomPrice\Identifier
    {
        return $this->identifier;
    }

    public function id(): int
    {
        return $this->identifier->id();
    }

    public function roomId(): int
    {
        return $this->identifier->roomId();
    }

    public function date(): DateTimeInterface
    {
        return $this->identifier->date();
    }

    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function status(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function releaseDays(): int
    {
        return $this->releaseDays;
    }

    public function setReleaseDays(int $days): void
    {
        $this->releaseDays = $days;
    }
}
