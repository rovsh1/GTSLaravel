<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Hotel\Service;

use DateTimeInterface;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;

interface BaseDayValueFinderInterface
{
    public function find(
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): ?float;

    public function findOrFail(
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): float;
}
