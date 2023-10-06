<?php

namespace Module\Pricing\Domain\Hotel\Service;

use DateTimeInterface;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Hotel\ValueObject\SeasonId;

interface RoomPriceFinderInterface
{
    public function findForDate(
        RoomId $roomId,
        SeasonId $seasonId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date,
    ): ?float;
}
