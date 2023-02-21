<?php

namespace Module\Hotel\Domain\Entity;

use Custom\Framework\Support\DateTimeInterface;
use Module\Hotel\Domain\ValueObject\RoomPrice as ValueObject;

class RoomDatePrice
{
    public function __construct(
        private readonly \Module\Hotel\Domain\ValueObject\RoomPrice\Identifier $identifier,
//        $roomId,
//        $date,

        private readonly ValueObject\GuestsCriteria $guestsParams,
//        $guestsNumber,
//        $residentType,
//        $residentType,

        private readonly ValueObject\Aggregator $aggregator,
//        $aggregatorId,

// Расчетные данные
//        $seasonId,
//        $rateId,
    ) {}

    public function identifier(): \Module\Hotel\Domain\ValueObject\RoomPrice\Identifier
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
