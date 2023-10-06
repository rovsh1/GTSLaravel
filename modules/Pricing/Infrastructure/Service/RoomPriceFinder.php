<?php

namespace Module\Pricing\Infrastructure\Service;

use DateTimeInterface;
use Module\Hotel\Infrastructure\Models\DatePrice;
use Module\Hotel\Infrastructure\Models\SeasonPrice;
use Module\Pricing\Domain\Hotel\Service\RoomPriceFinderInterface;

class RoomPriceFinder implements RoomPriceFinderInterface
{
    public function findForDate(
        int $roomId,
        int $seasonId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date,
    ): ?float {
        return $this->getDateValue(
            $roomId,
            $seasonId,
            $rateId,
            $isResident,
            $guestsCount,
            $date
        ) ?? $this->getSeasonValue(
            $roomId,
            $seasonId,
            $rateId,
            $isResident,
            $guestsCount
        );
    }

    private function getDateValue(
        int $roomId,
        int $seasonId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): ?float {
        return DatePrice::withGroup()
            ->whereSeasonId($seasonId)
            ->whereRoomId($roomId)
            ->whereDate('date', '=', $date->format('Y-m-d'))
            ->where('guests_count', '=', $guestsCount)
            ->where('is_resident', '=', $isResident)
            ->where('rate_id', '=', $rateId)
            ->value('price');
    }

    private function getSeasonValue(
        int $roomId,
        int $seasonId,
        int $rateId,
        bool $isResident,
        int $guestsCount
    ): ?float {
        return SeasonPrice::withGroup()
            ->whereSeasonId($seasonId)
            ->whereRoomId($roomId)
            ->where('guests_count', '=', $guestsCount)
            ->where('is_resident', '=', $isResident)
            ->where('rate_id', '=', $rateId)
            ->value('price');
    }
}